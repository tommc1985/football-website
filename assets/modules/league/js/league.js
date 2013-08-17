$('#submit-form-table').click(function() {
    return fetchFormTable();
});

function fetchFormTable()
{
    $('#form-table-wrapper').prepend('<div class="progress progress-striped active"><div class="bar" style="width: 90%;"></div></div>');

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
    $('#fixtures-and-results-wrapper').prepend('<div class="progress progress-striped active"><div class="bar" style="width: 90%;"></div></div>');

    $.post(baseURL + 'league/view/id/' + leagueId, $('#league-form').serialize() + '&view=fixtures-and-results', function (data) {
        $('#fixtures-and-results-wrapper').html(data);
    });

    return false;
}

$(document).ready(function() {
    $('.alternative-league-table-wrapper, .position-progress-wrapper').hide();

    $('#league-table-button').click(function() {
        $(this).addClass('active');
        $('#alternative-league-table-button, #position-progress-button').removeClass('active');

        $('.alternative-league-table-wrapper, .position-progress-wrapper').hide();
        $('.league-table-wrapper').show();

        return false;
    });

    $('#alternative-league-table-button').click(function() {
        $(this).addClass('active');
        $('#league-table-button, #position-progress-button').removeClass('active');

        $('.league-table-wrapper, .position-progress-wrapper').hide();
        $('.alternative-league-table-wrapper').show();

        return false;
    });

    $('#position-progress-button').click(function() {
        $(this).addClass('active');
        $('#league-table-button, #alternative-league-table-button').removeClass('active');

        $('.league-table-wrapper, .alternative-league-table-wrapper').hide();
        $('.position-progress-wrapper').show();
        resizeChartCanvas(charts['position-progress'].id);
        generateChart(charts['position-progress']);

        return false;
    });
});


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