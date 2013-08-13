$(document).ready(function() {
    $('.statistics-expand').show();

    $('.statistics-expand').click(function() {
        var season = $(this).attr('data-season');
        $('.season-breakdown-' + season).addClass('expanded');
        $('.season-breakdown-' + season).removeClass('collapsed');

        $('#' + season + '-expand').hide();
        $('#' + season + '-collapse').show();

        return false;
    });

    $('.statistics-collapse').click(function() {
        var season = $(this).attr('data-season');
        $('.season-breakdown-' + season).removeClass('expanded');
        $('.season-breakdown-' + season).addClass('collapsed');

        $('#' + season + '-expand').show();
        $('#' + season + '-collapse').hide();

        return false;
    });

    $('tr.season-breakdown').addClass('collapsed');
});