$(document).ready(function() {
    for (i in charts) {
        resizeChartCanvas(charts[i].id);
        generateChart(charts[i]);
    }
});

$(window).resize(function() {
    for (i in charts) {
        resizeChartCanvas(charts[i].id);
        generateChart(charts[i]);
    }
});

function generateChart(chart) {
    var ctx = $("#" + chart.id).get(0).getContext("2d");

    switch (chart.type) {
        case 'line':
            new Chart(ctx).Line(chart.data, chart.options);
            break;
        case 'radar':
            new Chart(ctx).Radar(chart.data, chart.options);
            break;
        case 'polar':
            new Chart(ctx).PolarArea(chart.data, chart.options);
            break;
        case 'pie':
            new Chart(ctx).Pie(chart.data, chart.options);
            break;
        case 'doughnut':
            new Chart(ctx).Doughnut(chart.data, chart.options);
            break;
        default :
            new Chart(ctx).Bar(chart.data, chart.options);
    }
}

function resizeChartCanvas(id) {
    var width = $('#' + id).parent().width();

    var height = width * 0.66;

    if (height < 400) {
        height = 400;
    }

    $('#' + id).attr('width', width);
    $('#' + id).attr('height', height);
}