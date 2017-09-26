$(document).ready(function()
{
    $('#prawo').on('click', '.ewoluuj', function()
    {
        var id = $(this).attr('name');
        $('.panel-body').laduj(URL+'wymiana/oddaj/'+id+'/?ajax',1); 
    });
    $('#prawo').on('click', '.oddaj', function()
    {
        var id = $(this).attr('name');
        $('.panel-body').laduj(URL+'wymiana/ewo/'+id+'/?ajax',1); 
    });
});