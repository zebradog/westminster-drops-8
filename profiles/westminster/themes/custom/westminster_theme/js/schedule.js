$(function() {
  var DEFAULT_VIEW = "agendaWeek";
  var FORM_DATE_FORMAT_MOMENT = "YYYY-MM-DD";
  var FORM_TIME_FORMAT_MOMENT = "HH:mm";
  var CMS_DATE_FORMAT = "YYYY-MM-DDTHH:mm:ss";
  var DOMAIN = window.location.protocol + "//" + window.location.hostname;
  var HEX_VALS = {
    '0': 0,
    '1': 1,
    '2': 2,
    '3': 3,
    '4': 4,
    '5': 5,
    '6': 6,
    '7': 7,
    '8': 8,
    '9': 9,
    'A': 10,
    'a': 10,
    'B': 11,
    'b': 11,
    'C': 12,
    'c': 12,
    'D': 13,
    'd': 13,
    'E': 14,
    'e': 14,
    'F': 15,
    'f': 15
  };

  var SCENARIOS_CMS = BASE_PATH+"rest/schedule/events?";
  var CALENDAR_CMS = BASE_PATH+"rest/schedule/calendar";
  var COLORS = [
    "aqua",
    "blue",
    "light-blue",
    "teal",
    "yellow",
    "orange",
    "green",
    "lime",
    "red",
    "purple",
    "fuchsia",
    "muted",
    "navy"
  ];

  var token;
  var $scenarios = $('#scenarios');
  var $myModal = $('#myModal');
  var calEvents = [];

  getToken();

  $myModal.on('hidden.bs.modal', function() {
    //if($('body').hasClass('refresh')) {
      window.location.reload();
    //}
  });

  /*
   * Gets authentication token.
   */
   function getToken(refresh) {
     if (!token || refresh) {
       token = $.ajax({
         url: BASE_PATH + 'rest/session/token',
         dataType: 'text',
         async: false,
         error: function(data) {
           alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText);
         }
       }).responseText;
     }
     return token;
   }

  $('#add-new-scenario').on('click', function(e) {
    if(!$('#new-scenario-url').val() || !$('#new-scenario-url').val().length) {
      $('#new-scenario-url').focus();
      return false;
    }
    $('#new-scenario-box').find('input').prop('disabled', true);
    createNewScenario(createScenarioFormData(), function(formData){
      window.location.reload();
    });
  });

  $('#edit-submit').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var origEventId = $myModal.data('orig-event-id');
    var $errorMsg = $('#edit-error');
    $errorMsg.text("");
    var sDate = $('#sDate').val();
    var sTime = $('#sTime').val();
    var eDate = $('#eDate').val();
    var eTime = $('#eTime').val();
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
  });
  $('#edit-cancel').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $myModal.modal('hide');
  });
  $('#edit-remove').on('click', function(e) {
    if($('body').hasClass('refresh')){
      $('body').removeClass('refresh');
    }
    e.preventDefault();
    e.stopPropagation();
    deleteEvent($myModal.data('event'));
  });
  $('.datepicker').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });

  populate_schedule_data();


  /* get schedule data from cms
   -----------------------------------------------------------------*/
  function populate_schedule_data() {
    populateScheduledContent();
    populateScenarios();
  }

  function populateScheduledContent() {
    $.getJSON(CALENDAR_CMS, function(data) {
      var eventObj;
      $('#calendar').fullCalendar('removeEvents');
      $.each(data, function(i, r) {
        eventObj = {
          title: r.title,
          start: moment(r.start),
          end: moment(r.end),
          scenarioUrl: r.url,
          backgroundColor: r.color,
          borderColor: r.color,
          nid: r.nid,
          scenarioNid: r.scenario_nid,
        };
        $('#calendar').fullCalendar('renderEvent', eventObj, true);
      });
    }).fail(function(data){
      console.error(data);
    });
  }
  function populateScenarios() {
    $.getJSON(SCENARIOS_CMS, function(data) {
      // Populate the draggable events
      var scenarioString = "";
      $.each(data, function(i, r) {
        var color = r.color ? r.color : COLORS[getRandomInt(0, COLORS.length)];
        r.color = color;
        var textColor = getHighVisFontColor(color);
        scenarioString += '<div class="external-event draggable-scenario" style="background-color:' + color + ';border-color:' + color + ';color:' + textColor + ';" data-title="' + r.title + '" data-url="' + r.url + '" data-nid="' + r.nid + '" data-color="' + color + '">' + r.title + '</div>';
      });
      if(scenarioString){
        $scenarios.removeClass('hidden').prepend(scenarioString).parent().parent().removeClass('hidden');
        ini_events($('#scenarios').find('div.draggable-scenario'));
      }
    }).fail(function(data){
      console.error(data);
    });
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

  /* determines if the event exists in the cms or has to be created,
   * then calls the appropriate function.
   -----------------------------------------------------------------*/
  function saveEvent(e) {
    var l = Ladda.create($('#edit-submit').get(0));
    l.start();
    formData = createFormData(e);
    if(e.nid) {
      updateExistingEvent(formData, l);
    }
    else {
      createNewEvent(formData, l);
    }
  }

  /* saves the event currently being edited in the modal popup as a
   * new node in the cms.
   -----------------------------------------------------------------*/
  function createNewEvent(formData, l) {
    var e = $myModal.data('event');
    var eid = $myModal.data('orig-event-id');
    $.ajax({
      method: "POST",
      url: BASE_PATH + "entity/node",
      data: JSON.stringify(formData),
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/hal+json",
        "X-CSRF-Token": getToken()
      },
      error: function(data) {
        alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText);
      }
    }).always(function(data) {
      l.stop();
      $myModal.modal('hide');
    });
  }

  /* updates the corresponding node in the cms
   -----------------------------------------------------------------*/
  function updateExistingEvent(formData, l) {
    $.ajax({
      method: "PATCH",
      url: BASE_PATH + "node/" + formData.nid[0].value,
      data: JSON.stringify(formData),
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/hal+json",
        "X-CSRF-Token": getToken()
      },
      success: function() {},
      error: function(data) {
        alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText);
      }
    }).always(function(data) {
      l.stop();
      $myModal.modal('hide');
    });
  }

  /* deletes the node from the cms (if applicable) or the calendar
   -----------------------------------------------------------------*/
  function deleteEvent(e) {
    var l = Ladda.create($('#edit-remove').get(0));
    l.start();
    if(e.nid) {
      var formData = createFormData(e);
      $.ajax({
        method: "DELETE",
        url: BASE_PATH + "node/" + formData.nid[0].value,
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/hal+json",
          "X-CSRF-Token": getToken()
        },
        success: function() {
          removeEventFromCal($myModal.data('orig-event-id'));
        },
        error: function(data) {
          alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText);
        }
      }).always(function(data) {
        l.stop();
        $myModal.modal('hide');
      });
    }
    else {
      removeEventFromCal($myModal.data('orig-event-id'));
      l.stop();
      $myModal.modal('hide');
    }
  }

  /* re-renders the event on the calendar with the new dates
   -----------------------------------------------------------------*/
  function updateEventOnCal(id, e) {
    $('#calendar').fullCalendar('removeEvents', id);
    var eventObj = {};
    eventObj.start = e.start;
    eventObj.end = e.end;
    eventObj.backgroundColor = e.backgroundColor;
    eventObj.borderColor = e.borderColor;
    eventObj.title = e.title;
    eventObj.scenarioNid = e.scenarioNid;
    eventObj.scenarioUrl = e.scenarioUrl;
    eventObj.allDay = e.allDay;
    $('#calendar').fullCalendar('renderEvent', eventObj, true);
  }

  /* removes the event from the calendar
   -----------------------------------------------------------------*/
   function removeEventFromCal(id) {
     $('#calendar').fullCalendar('removeEvents', id);
   }

  /* takes an event and returns the JSON form data for an ajax call
   -----------------------------------------------------------------*/
  function createFormData(e) {
    var formData = {
      "_links":{
        "type":{
          "href":DOMAIN+BASE_PATH+"rest/type/node/scheduled_content"
        }
      },
      "title":[
        {
          "value":e.title
        }
      ]
    };
    formData['field_content'] = [{'target_id': e.scenarioNid, 'url': BASE_PATH + 'node/' + e.scenarioNid}];
    formData['field_start_date'] = [{'value':e.start.format(CMS_DATE_FORMAT)}];
    formData['field_end_date'] = [{'value':e.end.format(CMS_DATE_FORMAT)}];
    if(e.nid) formData['nid'] = [{'value':e.nid}];
    return formData;
  }

  /* returns the JSON form data for an ajax call to create an event (scenario)
   -----------------------------------------------------------------*/
  function createScenarioFormData() {
    var type = 'external_content';
    var url = $('#new-scenario-url').val();
    if(url.indexOf('http://') < 0 && url.indexOf('https://') < 0) {
      url = 'http://' + url;
    }
    var formData = {
      "_links":{
        "type":{
          "href":DOMAIN + BASE_PATH + "rest/type/node/" + type
        }
      },
      "title":[
        {
          "value":url
        }
      ]
    };
    formData['field_color'] = [{"value": $('#add-new-scenario').css('background-color')}];
    formData['field_url'] = [{"uri": url}];
    return formData;
  }

  function createNewScenario(formData, callback) {
    var l = Ladda.create($('#add-new-scenario').get(0));
    l.start();
    $.ajax({
      method: "POST",
      url: BASE_PATH + "entity/node",
      data: JSON.stringify(formData),
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/hal+json",
        "X-CSRF-Token": getToken()
      },
      success: function(data, status, xhr) {
        /*console.log(data);
        console.log(status);
        console.log(xhr);*/
      },
      error: function(data) {
        alert("Encountered an error while trying to save:\n" + data.status + ": " + data.statusText + " Invalid URL");
      }
    }).always(function() {
      l.stop();
      $('#new-scenario-box').find('input').prop('disabled', false).val('');
      if(callback) callback(formData);
    });
  }

  /* Fills the event param with the date values in the modal popup.
   -----------------------------------------------------------------*/
  function updateEventValues(e, callback) {
    e.start = moment($('#sDate').val() + ' ' + $('#sTime').val() + ' UTC');
    e.end = moment($('#eDate').val() + ' ' + $('#eTime').val() + ' UTC');
    if(callback) callback(e);
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

  /* initialize the external events
   -----------------------------------------------------------------*/
  function ini_events(ele) {
    ele.each(function() {

      // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
      // it doesn't need to have a start or end
      var eventObject = {
        title: $.trim($(this).text()) // use the element's text as the event title
      };

      // store the Event Object in the DOM element so we can get to it later
      $(this).data('eventObject', eventObject);

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 1070,
        revert: true, // will cause the event to go back to its
        revertDuration: 0 //  original position after the drag
      });

    });
  }



  /* fill in the modal popup
   -----------------------------------------------------------------*/
   function fillModal(e) {
     $myModal.find('#sDate').val(e.start.format(FORM_DATE_FORMAT_MOMENT));
     $myModal.find('#sTime').val(e.start.format(FORM_TIME_FORMAT_MOMENT));
     $myModal.find('#eDate').val(e.end.format(FORM_DATE_FORMAT_MOMENT));
     $myModal.find('#eTime').val(e.end.format(FORM_TIME_FORMAT_MOMENT));
   }

  /* initialize the calendar
   -----------------------------------------------------------------*/
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
    droppable: true, // this allows things to be dropped onto the calendar !!!
    drop: function(date, jsEvent, ui) { // this function is called when something is dropped
      $('body').addClass('refresh');
      var allDay = date._ambigTime ? true : false;
      // retrieve the dropped element's stored Event Object
      var originalEventObject = $(this).data('eventObject');

      // we need to copy it, so that multiple events don't have a reference to the same object
      var copiedEventObject = $.extend({}, originalEventObject);

      // assign it the date that was reported
      copiedEventObject.allDay = allDay;
      if(allDay) {
        copiedEventObject.start = moment(date).startOf('day');
        copiedEventObject.end = moment(date).add(1, 'd').startOf('day');
      }
      else {
        copiedEventObject.start = moment(date);
        copiedEventObject.end = moment(date).add(2,'h');
      }
      copiedEventObject.backgroundColor = $(this).data('color');
      copiedEventObject.borderColor = copiedEventObject.backgroundColor;

      copiedEventObject.scenarioNid = $(this).data('nid');
      copiedEventObject.title = $(this).data('title');
      copiedEventObject.scenarioUrl = $(this).data('url');
      // This will be a new item, so it won't have its own nid yet.

      fillModal(copiedEventObject);
      $myModal.data('event', copiedEventObject);
      $('#edit-cancel').addClass('hidden');
      $myModal.modal('show');

      // render the event on the calendar
      // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
      var newEventObj = $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
      $myModal.data('orig-event-id', newEventObj._id);
    },
    eventClick: function(event, jsEvent, view) {
      $('#edit-cancel').removeClass('hidden').unbind('click').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        $myModal.modal('hide');
      });
      fillModal(event);
      $myModal.data('event', event);
      $myModal.data('orig-event-id', event._id);
      $myModal.modal('show');
    },
    eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
      $('#edit-cancel').removeClass('hidden').unbind('click').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        if(revertFunc) revertFunc();
        $myModal.modal('hide');
      });
      fillModal(event);
      $myModal.data('event', event);
      $myModal.data('orig-event-id', event._id);
      $myModal.modal('show');
    },
    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
      $('#edit-cancel').removeClass('hidden').unbind('click').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        if(revertFunc) revertFunc();
        $myModal.modal('hide');
      });
      fillModal(event);
      $myModal.data('event', event);
      $myModal.data('orig-event-id', event._id);
      $myModal.modal('show');
    }
  });

  /* ADDING EVENTS */
  var currColor = "#3c8dbc"; //Red by default
  //Color chooser button
  var colorChooser = $("#color-chooser-btn");
  $("#color-chooser > li > a").click(function(e) {
    e.preventDefault();
    //Save color
    currColor = $(this).css("color");
    //Add color effect to button
    $('#add-new-scenario').css({
      "background-color": currColor,
      "border-color": currColor
    });
  });
});
