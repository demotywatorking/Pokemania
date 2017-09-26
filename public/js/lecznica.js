$(document).ready(function() {

    $('#prawo').on('click', '.wylecz', function(event)
    {
        event.preventDefault();
        var co = $(this).attr('href');
        $('#wylecz').load(co  + '?ajax', function()
        {
            $('#tabelka').load(URL+'lewo');
            $('#lecznica').load(URL+'lecznica/?ajax #lecznica');
        });
        history.replaceState(null, null, co);
    });

});
