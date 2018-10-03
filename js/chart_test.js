$(document).ready(function() {

});

function plot_chart() {
  $.post('../php/graphing/test_plot.php', function(result) {
    parsed = JSON.parse(result);

    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [{
          data: parsed['values']
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: parsed['labels']
      },
      options: {}
    });
  });

};
