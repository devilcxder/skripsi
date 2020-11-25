<html>
  <body>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(() => {
        // Instead of hard-coding the initial table data,
        // you could fetch it from your server.
        const dataTable = google.visualization.arrayToDataTable([
          ['Year', 'Price'],
          [2013, 400],
          [2014, 460],
        ]);
        const chart = new google.visualization.AreaChart(
          document.getElementById('chart_div'));
        const options = {
          title: '1 BTC in USD',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          animation:{ duration: 100, easing: 'out' }
        };
        chart.draw(dataTable, options);
        let year = 2015;
        Pusher.logToConsole = true;
        const pusher = new Pusher('d6dc82355927033668cd', { // Replace with 'key' from dashboard
          cluster: 'ap1',              // Replace with 'cluster' from dashboard
          forceTLS: true
        });
        const channel = pusher.subscribe('price-btcusd');
        channel.bind('new-price', data => {
          const row = [year++, data.value];
          dataTable.addRow(row);
          chart.draw(dataTable, options);
        });
      });
    </script>
  </body>
</html>