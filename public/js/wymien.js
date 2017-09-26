$(document).ready(function()
{
    $('#prawo').on('click', '.wymien', function()
    {
        var id = this.id;
        $('.tab-content').laduj(URL+'wymien/skamielina/'+id+'/?ajax', 1);
    });
    $('#prawo').on('click', '.nie', function()
    {
        $('.tab-content').laduj(URL+'wymien/?ajax', 1);
    });
    $('#prawo').on('click', '.tak', function()
    {
        var id = this.id;
        $('.tab-content').laduj(URL+'wymien/skamielina/'+id+'/?ajax&tak', 1);
    });
     $('#prawo').on('click', '.oddaj', function()
    {
        var id = $(this).attr('name');
        $('.tab-content').laduj(URL+'wymien/oddaj/'+id+'/?ajax', 1);
    });
    $('#prawo').on('click', '.wymien_d', function()
    {
        var id = this.id;
        $('.tab-content').laduj(URL+'wymien/wymien/'+id+'/?ajax&active=2', 1);
    });
});