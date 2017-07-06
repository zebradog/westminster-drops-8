if (google && google.charts && google.charts.load) {
  google.charts.load('current', {
    packages: [
      'corechart',
      'table'
    ]
  });
} else if (google && google.load) {
  google.load('visualization', '1.0', {
    packages: [
      'corechart',
      'table'
    ]
  });
}
