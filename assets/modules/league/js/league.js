$('#submit-form-table').click(function() {
    return fetchFormTable();
});

function fetchFormTable()
{
    $.post(baseURL + 'league/view/id/' + leagueId, $('#league-form').serialize() + '&view=form', function (data) {
        $('#form-table-wrapper').html(data);
    });

    return false;
}

$('#submit-fixtures-and-results').click(function() {
    return fetchFixturesAndResults();
});

function fetchFixturesAndResults()
{
    $.post(baseURL + 'league/view/id/' + leagueId, $('#league-form').serialize() + '&view=fixtures-and-results', function (data) {
        $('#fixtures-and-results-wrapper').html(data);
    });

    return false;
}

var progressionStatus = new Array();
for (var i = 0; i < oppositionCount; i++) {
    progressionStatus[i] = true;
}

$('.legend-opposition').click(function() {
    var index = $(this).attr('data-index');

    if (progressionStatus[index]) {
        $('.legend-identifier[data-index="' + index + '"]').css('visibility', 'hidden');
        progressionStatus[index] = false;
    } else {
        $('.legend-identifier[data-index="' + index + '"]').css('visibility', 'visible');
        progressionStatus[index] = true;
    }

    charts['position-progress'].options.animation = false;
    for (var i = 0; i < oppositionCount; i++) {
        if(!progressionStatus[i]) {
            charts['position-progress'].data.datasets[i].data = new Array();
        } else {
            charts['position-progress'].data.datasets[i].data = charts['position-progress'].originalData.datasets[i].data;
        }
    }

    generateChart(charts['position-progress']);

    charts['position-progress'].options.animation = true;

    return false;
});