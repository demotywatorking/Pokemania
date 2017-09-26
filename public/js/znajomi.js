$(document).ready(function()
{
    $('#prawo').on('click', '.zaakceptuj', function()
    {
        var id = this.id;
        $('.panel-body').laduj(URL+'znajomi/zaakceptuj/'+id+'?ajax', 1);
    });
    $('#prawo').on('click', '.usun', function()
    {
        var id = this.id;
        $('.panel-body').laduj(URL+'znajomi/usun/'+id+'?ajax', 1);
    });
    $('#prawo').on('click', '.nie, .powrot', function()
    {
        $('.panel-body').laduj(URL+'znajomi?ajax', 1);
    });
    $('#prawo').on('click', '.tak', function()
    {
        var id = $(this).attr('name');
        $('.panel-body').laduj(URL+'znajomi/usun/'+id+'/1&ajax', 1);
    });
    $('#prawo').on('click', '.nakarm', function()
    {
        var id = this.id;
        $('#karmienie').addClass('margin-top');
        $('#karmienie').laduj(URL+'pokemon/nakarm/'+id+'?ajax', 1);
    });
     $('#prawo').on('click', '.odrzuc', function()
    {
        var id = this.id;
        $('.panel-body').laduj(URL+'znajomi/odrzuc/'+id+'?ajax', 1);
    });
    $('#prawo').on('click', '.anuluj', function()
    {
        var id = this.id;
        $('.panel-body').laduj(URL+'znajomi/anuluj/'+id+'?ajax', 1);
    });
});
