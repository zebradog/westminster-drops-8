if (gapi && gapi.analytics && gapi.analytics.ready) {
  gapi.analytics.ready(function() {

    if (drupalSettings && drupalSettings.westminster_analytics && drupalSettings.westminster_analytics.serverAuth) {
      gapi.analytics.auth.authorize({
        'serverAuth': drupalSettings.westminster_analytics.serverAuth
      });
    }

  });
}
