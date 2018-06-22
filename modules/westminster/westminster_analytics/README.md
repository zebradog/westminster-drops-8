# westminster_analytics

Provides an integration with Google Analytics and tools for creating data visualization.

## Prerequisites

Please follow **Step 1** of the [PHP quckstart quide](https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php):

1. Ensure that the Analytics API is enabled in Google API Console.
2. Create or use an existing project in Google API Console.
3. Create a new service account for the project.
4. Create a new service account key for the new service account. Select the JSON format. It should download automatically.
5. Add the service account to the Google Analytics property with readonly permissions. The email address is its service account ID.

## Configuration

Install the module. It is not installed by default.

Navigate to the configuration form (`/admin/config/westminster/analytics`) to manage your credentials.
Please upload the service account key that was downloaded during **Prerequisite 4** above.

Redacted credentials will be displayed here in a textarea for verification purposes.
Once an access token is fetched using these credentials, it will also be displayed here in redacted form.
This may require a page refresh.

Clearing the credentials will also clear the current access token.

## Creating frontend experiences

Once the credentials are uploaded, GAPI is attached to all pages for logged in users.
It is automatically configured with a fresh access token using the uploaded credentials.
Additionally, the Visualization API is automatically loaded with the most common packages.

You will be responsible for using GAPI to fetch data from Google Analytics and the Visualization API draw charts.
Wherever you can inject unfiltered HTML is an opportunity for a chart, e.g. blocks, pages, views, or even custom modules.

### Example code

```html
<div id="chart"></div>
<script>
  // NOTE: Authentication is handled by the module. Just start writing.
  if (window.gapi && window.google) {
    gapi.analytics.ready(function onReady() {
      const chartElement = document.getElementById('chart');

      const chart = new google.visualization.PieChart(chartElement),
            report = new gapi.analytics.report.data({
              query: {
                ids: 'ga:XXXXXXXXXX',
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                // TODO: metrics, dimensions, filters, sort, etc.
                output: 'dataTable',
              },
            });

      let data;

      report.on('success', function onSuccess(response) {
        data = new google.visualization.DataTable(response.dataTable);

        // TODO: Manipulate data

        draw();
      });

      report.execute();

      function draw() {
        if (!data) {
          return;
        }

        chart.draw(data, {
          // TODO: Chart options
        });
      }

      window.addEventListener('orientationchange', draw);
      window.addEventListener('resize', draw);
    });
  }
</script>
```

## Resources

Google provides helpful documentation of its libraries:

### Google Analytics

- [Hello Analytics API: PHP quickstart for service accounts](https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php)
- [Hello Analytics API: Javascript quickstart for web applications](https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/web-js)
- [Common Queries](https://developers.google.com/analytics/devguides/reporting/core/v3/common-queries)
- [Core Reporting API](https://developers.google.com/analytics/devguides/reporting/core/v3/reference)

### Google Charts

- [Google Charts Quick Start](https://developers.google.com/chart/interactive/docs/quick_start)
- [Bar Chart](https://developers.google.com/chart/interactive/docs/gallery/barchart)
- [Line Chart](https://developers.google.com/chart/interactive/docs/gallery/linechart)
- [Pie Chart](https://developers.google.com/chart/interactive/docs/gallery/piechart)
