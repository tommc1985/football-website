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