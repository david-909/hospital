<script>
  // !!NEDELJNA ZARADA CHART
  // setup

  const dataweek = {
    labels: ['Ukupna nedeljna zarada', 'Nedeljna zarada', 'Nedeljni trošak'],
    datasets: [{
      label: 'Ukupno novca',
      data: [<?php
              global $totalIncomeWeek;
              global $profitWeek;
              global $totalExpensesWeek;
              if ($totalIncomeWeek) {
                echo $totalIncomeWeek;
              } else {
                echo "";
              } ?>,
        <?php if ($profitWeek) {
          echo $profitWeek;
        } else {
          echo "";
        } ?>,
        <?php if ($totalExpensesWeek) {
          echo $totalExpensesWeek;
        } else {
          echo "";
        } ?>
      ],
      backgroundColor: [
        'rgba(36, 149, 255, 0.5)',
        'rgba(68, 255, 162, 0.5)',
        'rgba(255, 68, 68, 0.5)'
      ],
      borderColor: [
        'rgba(92, 203, 255, 1)',
        'rgba(60, 211, 136, 1)',
        'rgba(255, 68, 68, 1)'
      ],
      borderWidth: 1
    }]
  }
  // config
  const config = {
    type: 'bar',
    data: dataweek,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  };
  // render
  const myChart = new Chart(
    document.getElementById('myChart').getContext('2d'),
    config
  );
  // data myChart2
  const dataday = {
    labels: ['Ukupna dnevna zarada', 'Dnevna zarada', 'Dnevni trošak'],
    datasets: [{
      label: 'Ukupno novca',
      data: [<?php
              global $totalIncomeToday;
              global $profitToday;
              global $totalExpensesToday;
              if ($totalIncomeToday) {
                echo $totalIncomeToday;
              } else {
                echo "";
              } ?>,
        <?php if ($profitToday) {
          echo $profitToday;
        } else {
          echo "";
        } ?>,
        <?php if ($totalExpensesToday) {
          echo $totalExpensesToday;
        } else {
          echo "";
        } ?>
      ],
      backgroundColor: [
        'rgba(36, 149, 255, 0.5)',
        'rgba(68, 255, 162, 0.5)',
        'rgba(255, 68, 68, 0.5)'
      ],
      borderColor: [
        'rgba(92, 203, 255, 1)',
        'rgba(60, 211, 136, 1)',
        'rgba(255, 68, 68, 1)'
      ],
      borderWidth: 1
    }]
  }
  //config myChart2
  const configday = {
    type: 'bar',
    data: dataday,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  };
  // render myChart2
  const myChart2 = new Chart(
    document.getElementById('myChart2').getContext('2d'),
    configday
  );
  // data myChart3
  const datamonth = {
    labels: ['Ukupna mesečna zarada', 'Mesečna zarada', 'Mesečni trošak'],
    datasets: [{
      label: 'Ukupno novca',
      data: [<?php
              global $totalIncomeMonth;
              global $profitMonth;
              global $totalExpensesMonth;
              if ($totalIncomeMonth) {
                echo $totalIncomeMonth;
              } else {
                echo "";
              } ?>,
        <?php if ($profitMonth) {
          echo $profitMonth;
        } else {
          echo "";
        } ?>,
        <?php if ($totalExpensesMonth) {
          echo $totalExpensesMonth;
        } else {
          echo "";
        } ?>
      ],
      backgroundColor: [
        'rgba(36, 149, 255, 0.5)',
        'rgba(68, 255, 162, 0.5)',
        'rgba(255, 68, 68, 0.5)'
      ],
      borderColor: [
        'rgba(92, 203, 255, 1)',
        'rgba(60, 211, 136, 1)',
        'rgba(255, 68, 68, 1)'
      ],
      borderWidth: 1
    }]
  }
  //config myChart3
  const configmonth = {
    type: 'pie',
    data: datamonth,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  };
  // render myChart3
  const myChart3 = new Chart(
    document.getElementById('myChart3').getContext('2d'),
    configmonth
  );
</script>

