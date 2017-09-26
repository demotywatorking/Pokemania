$(document).ready(function()
{
    $('#prawo').on('click', '.btn', function()
    {
        if(!$(this).hasClass('walka_button'))
            $('#prawo').laduj(URL+'sale/'+this.id+'/?ajax', 1 , URL+'sale/'+this.id);
    });
});