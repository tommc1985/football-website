$(document).ready(function() {
    $('.expand').show();

    $('.expand').click(function() {
        var season = $(this).attr('data-season');
        $('.season-breakdown-' + season).css('display', $(window).width() <= 767 ? 'block' : 'table-row');

        $('#' + season + '-expand').hide();
        $('#' + season + '-collapse').show();

        return false;
    });

    $('.collapse').click(function() {
        var season = $(this).attr('data-season');
        $('.season-breakdown-' + season).css('display', 'none');

        $('#' + season + '-expand').show();
        $('#' + season + '-collapse').hide();

        return false;
    });

    $('tr.season-breakdown').css('display', 'none');
});