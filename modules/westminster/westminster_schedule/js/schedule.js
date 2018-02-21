$(function() {
  var DEFAULT_VIEW = "agendaWeek";
  var FORM_DATE_FORMAT_MOMENT = "YYYY-MM-DD";
  var FORM_TIME_FORMAT_MOMENT = "HH:mm";
  var CMS_DATE_FORMAT = "YYYY-MM-DDTHH:mm:ss";
  var DOMAIN = "http://" + window.location.hostname;

  var $myModal = $('#myModal');
  var calEvents = [];
  var token = getToken();
  var revertFunction;

  $myModal.on('hidden.bs.modal', function() {
    if (revertFunction) {
      revertFunction();
    }
    //if($('body').hasClass('refresh')) {
      //window.location.reload();
    //}
  });

  function getToken(refresh) {
    if (!token || refresh) {
      token = $.ajax({
        url: Drupal.url("rest/session/token"),
        dataType: 'text',
        async: false,
        error: function(data) {
          alert("Encountered an error while grabbing a token:\n" + data.status + ": " + data.statusText);
        }
      }).responseText;
    }
    return token;
  }

  populateScheduledContent();

  function populateScheduledContent() {
    if (drupalSettings.scheduleItems) {
      $('.datepicker').datepicker({
        zIndex: 9999,
        format: 'yyyy-MM-dd'
      });
      $('#edit-submit').on('click', function(e) {
        submitItem(e);
      });
      $('#edit-remove').on('click', function(e) {
        deleteItem($myModal.data('event'));
      });
      var $selectList = $('#scheduled-item-entity');
      var optionsString = '';
      $.each(drupalSettings.scheduleChoices, function(i, c) {
        optionsString += '<option id="scheduled-item-option-' + i + '" value="' + i + '">' + c.title + '</option>';
      });
      $selectList.append(optionsString).select2({ width: '75%' });
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        buttonText: {
          today: 'today',
          month: 'month',
          week: 'week',
          day: 'day'
        },
        timezone: 'UTC',
        slotDuration: '00:15:00',
        defaultView: DEFAULT_VIEW,
        editable: true,
        eventClick: function(event, jsEvent, view) {
          fillModal(event);
          $myModal.data('event', event);
          $myModal.data('orig-event-id', event._id);
          $myModal.modal('show');
        },
        eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
          if (revertFunc) {
            revertFunction = revertFunc;
          }
          fillModal(event);
          $myModal.data('event', event);
          $myModal.data('orig-event-id', event._id);
          $myModal.modal('show');
        },
        eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
          if (revertFunc) {
            revertFunction = revertFunc;
          }
          fillModal(event);
          $myModal.data('event', event);
          $myModal.data('orig-event-id', event._id);
          $myModal.modal('show');
        },
        dayClick: function(date, jsEvent, view) {
          var event = {
            start: date,
            end: moment(date).add(2, 'hours'),
            title: ''
          };
          fillModal(event);
          $myModal.data('event', event);
          $myModal.modal('show');
        }
      });
      $('#calendar').fullCalendar('removeEvents');
      $.each(drupalSettings.scheduleItems, function(i, r) {
        var eventObj = {
          title: r.title,
          nid: r.id,
          start: r.start,
          end: r.end,
          scheduledItem: r.scheduled_item
        }
        $('#calendar').fullCalendar('renderEvent', eventObj, true);
      });
    }
  }

  /* convert text to most legible grayscale value
   -----------------------------------------------------------------*/
  function getHighVisFontColor(color) {
    var rgb;
    if(color.indexOf('#') >= 0) {
      rgb = hexStrToRgbObj(color);
    }
    else {
      rgb = rgbStrToRgbObj(color);
    }
    var gsVal = Math.floor((rgb['r'] + rgb['g'] + rgb['b'])/3 + .5);
    var textGsVal = gsVal > 127 ? '0' : '255';
    return "rgb(" + textGsVal + "," + textGsVal + "," + textGsVal + ")";
  }

  function hexStrToRgbObj(color) {
    // Remove # from hex str
    color = color.substring(1);
    if(color.length == 3) {
      color = '' + color[0] + color[0] + color[1] + color[1] + color[2] + color[2];
    }
    // We now have a 6 char color
    var rgb = {};
    rgb['r'] = HEX_VALS[color[0]] * 16 + HEX_VALS[color[1]];
    rgb['g'] = HEX_VALS[color[2]] * 16 + HEX_VALS[color[3]];
    rgb['b'] = HEX_VALS[color[4]] * 16 + HEX_VALS[color[5]];
    return rgb;
  }

  function rgbStrToRgbObj(color) {
    //remove parens
    color = color.substring(color.indexOf('(') + 1, color.length - 1);
    color = color.split(',');
    var rgb = {};
    rgb['r'] = parseInt(color[0]);
    rgb['g'] = parseInt(color[1]);
    rgb['b'] = parseInt(color[2]);
    return rgb;
  }

  /* gets a random int between two given values (min inclusive, max exclusive)
   -----------------------------------------------------------------*/
  function getRandomInt(min, max) {
    if (min === max) {
      return min; // TODO: Invalid options, handle this.
    } else if (min === (max - 1)) {
      return min; // Since end is exclusive, beggining inclusive, just return min.
    } else if (min > max) {
      // Flip min and max if in wrong order.
      max = min - max;
      min = min - max;
      max = min + max;
    }
    return Math.floor(Math.random() * (max - min)) + min;
  }

  function fillModal(e) {
    $myModal.find('#sDate').val(e.start.format(FORM_DATE_FORMAT_MOMENT));
    $myModal.find('#sTime').val(e.start.format(FORM_TIME_FORMAT_MOMENT));
    $myModal.find('#eDate').val(e.end.format(FORM_DATE_FORMAT_MOMENT));
    $myModal.find('#eTime').val(e.end.format(FORM_TIME_FORMAT_MOMENT));
    $myModal.find('#scheduled-item-title').val(e.title || '');
    if (e.scheduledItem) {
      $myModal.find('#scheduled-item-option-' + e.scheduledItem.id).prop('selected', true);
    }
  }

  function deleteItem(e) {
    var l = Ladda.create($('#edit-remove').get(0));
    l.start();
    if (e.nid) {
      var formData = createFormData(e);
      formData.action = 'delete';
      updateEvent(formData, l);
    }
  }

  function submitItem(e) {
    var origEventId = $myModal.data('orig-event-id');
    var $errorMsg = $('#edit-error');
    $errorMsg.text("");
    var sDate = $('#sDate').val();
    var sTime = $('#sTime').val();
    var eDate = $('#eDate').val();
    var eTime = $('#eTime').val();
    var targetId = $('#scheduled-item-entity > option:selected').val();
    var title = $('#scheduled-item-title').val();
    if (!title || !title.length) {
      $('#scheduled-item-title').focus();
      $errorMsg.text('Missing title.');
      return false;
    }
    if(!targetId || targetId < 0) {
      $('#scheduled-item-entity').focus();
      $errorMsg.text("Missing content to schedule.");
      return false;
    }
    if(!sDate.length){
      $('#sDate').focus();
      $errorMsg.text("Missing start date.");
      return false;
    }
    if(!sTime.length){
      $('#sTime').focus();
      $errorMsg.text("Missing start time.");
      return false;
    }
    if(!eDate.length){
      $('#eDate').focus();
      $errorMsg.text("Missing end date.");
      return false;
    }
    if(!eTime.length){
      $('#eTime').focus();
      $errorMsg.text("Missing end time.");
      return false;
    }
    var sdt = moment(sDate + " " + sTime);
    var edt = moment(eDate + " " + eTime);
    if(!sdt.isBefore(edt)){
      $('#sDate').focus();
      $errorMsg.text("Start date/time must be an earlier date/time than end date/time.");
      return false;
    }
    updateEventValues($myModal.data('event'), function(ev) {
      updateEventOnCal(origEventId, ev);
      saveEvent(ev);
    });
  }

  function updateEventValues(e, callback) {
    e.start = moment($('#sDate').val() + ' ' + $('#sTime').val());
    e.end = moment($('#eDate').val() + ' ' + $('#eTime').val());
    e.title = $('#scheduled-item-title').val();
    e.scheduledItem = drupalSettings.scheduleChoices[$('#scheduled-item-entity > option:selected').val()];
    if(callback) callback(e);
  }

  function updateEventOnCal(id, e) {
    if (id) {
      var eventObj =  $('#calendar').fullCalendar('clientEvents', id);
      if (eventObj && eventObj.length) {
        eventObj = eventObj[0];
        eventObj.start = e.start;
        eventObj.end = e.end;
        eventObj.title = e.title;
        eventObj.scheduledItem = e.scheduledItem;
        eventObj.nid = e.nid;
        $('#calendar').fullCalendar('updateEvent', eventObj);
      }
    }
  }

  function saveEvent(e) {
    var l = Ladda.create($('#edit-submit').get(0));
    l.start();
    updateEvent(createFormData(e), l);
  }

  function createFormData(e) {
    var formData = {
      "title": e.title,
      "nid": (e.nid ? e.nid : -1),
      "target_id": e.scheduledItem.id,
      "start": e.start.format(CMS_DATE_FORMAT),
      "end": e.end.format(CMS_DATE_FORMAT),
      "action": "create"
    };
    return formData;
  }

  function updateEvent(formData, l) {
    $.ajax({
      method: "POST",
      url: Drupal.url('scheduling/ajax'),
      data: formData,
      dataType: 'json',
      error: function(data) {
        alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText);
        $myModal.modal('hide');
      }
    }).done(function(data) {
      revertFunction = undefined;
      if (formData.action === "delete") {
        $('#calendar').fullCalendar('removeEvents', $myModal.data('orig-event-id'));
      } else if (formData.action === "create") {
        var eventObj = {
          title: data.title,
          nid: data.id,
          start: data.start,
          end: data.end,
          scheduledItem: data.scheduled_item
        }
        if (formData.nid > 0) {
          updateEventOnCal($myModal.data('orig-event-id'), eventObj);
        } else {
          // new event, add new event to calendar
          $('#calendar').fullCalendar('renderEvent', eventObj, true);
        }
      }
    }).always(function(data) {
      l.stop();
      $myModal.modal('hide');
    });
  }
});
