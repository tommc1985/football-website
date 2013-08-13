$('#leaderboard-submit').click(function() {
    return fetchLeaderboardTable();
});

function fetchLeaderboardTable()
{
    $.post(baseURL + 'fantasy-football/view', $('#fantasy-football-form').serialize() + '&view=leaderboard', function (data) {
        $('#leaderboard-wrapper').html(data);
    });

    return false;
}

$('#best-lineup-submit').click(function() {
    return fetchBestLineUp();
});

function fetchBestLineUp()
{
    $.post(baseURL + 'fantasy-football/view', $('#fantasy-football-form').serialize() + '&view=best-lineup', function (data) {
        $('#best-lineup-wrapper').html(data);
    });

    return false;
}