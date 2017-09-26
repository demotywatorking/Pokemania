$(document).ready(function()
{
    var co = '';
    if($('#white').length) co = 'white';
    else if($('#black').length) co = 'black';
    $('#prawo').on('click', '.black', function()
    {
        if(co != 'black')
        {
            $('#white').remove();
            $('head').append( $('<link rel="stylesheet" type="text/css" />').attr('href', URL+'public/css/style_black.css').attr('id', 'black') );
            co = 'black';
        }
    });
    $('#prawo').on('click', '.white', function()
    {
        if(co != 'white')
        {
            $('#black').remove();
            $('head').append( $('<link rel="stylesheet" type="text/css" />').attr('href', URL+'public/css/style_white.css').attr('id', 'white') );
            co = 'white';
        }
    });
    $('#prawo').on('click', '.potwierdz1', function()
    {
        $('.panel-body').load(URL+'samouczek/wybor/1/'+co+'/?ajax');
    });
    $('#prawo').on('click', '.pomin', function()
    {
        $('.panel-body').load('samouczek.php?ajax&samouczek=pomin');
    });
});