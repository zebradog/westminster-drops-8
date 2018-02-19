$(function() {
  var DEFAULT_VIEW = "agendaWeek";
  var FORM_DATE_FORMAT_MOMENT = "YYYY-MM-DD";
  var FORM_TIME_FORMAT_MOMENT = "HH:mm";
  var CMS_DATE_FORMAT = "YYYY-MM-DDTHH:mm:ss";
  var DOMAIN = "http://" + window.location.hostname;

  var $myModal = $('#myModal');
  var calEvents = [];

  $myModal.on('hidden.bs.modal', function() {
    //if($('body').hasClass('refresh')) {
      window.location.reload();
    //}
  });

  populateScheduledContent();

  function populateScheduledContent() {
    if (drupalSettings.scheduleItems) {
      /*var eventObj;
      $('#calendar').fullCalendar('removeEvents');
      $.each(data, function(i, r) {
        eventObj = {
          title: r.title,
          start: moment(r.start),
          end: moment(r.end),
          nid: r.nid,
        };
        $('#calendar').fullCalendar('renderEvent', eventObj, true);
      });*/
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

    },
    eventClick: function(event, jsEvent, view) {

    },
    eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {

    },
    eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {

    }
  });
});
