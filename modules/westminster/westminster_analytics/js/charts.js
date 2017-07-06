if (google && google.charts && google.charts.load) {
  google.charts.load('current', {
    packages: [
      'bar',
      'corechart',
      'line',
      'table'
    ]
  });
} else if (google && google.load) {
  google.load('visualization', '1.0', {
    packages: [
      'bar',
      'corechart',
      'line',
      'table'
    ]
  });
}
