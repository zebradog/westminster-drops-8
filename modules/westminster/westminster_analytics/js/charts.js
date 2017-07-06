if (google && google.charts && google.charts.load) {
  google.charts.load('current', {
    packages: [
      'corechart',
      'line',
      'table'
    ]
  });
} else if (google && google.load) {
  google.load('visualization', '1.0', {
    packages: [
      'corechart',
      'line',
      'table'
    ]
  });
}
