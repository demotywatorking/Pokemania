$(document).ready(function()
{
    $('#prawo').on('click', '.wyswietl', function()
    {
        $('.panel-body').laduj(URL+'zglos//wyswietl/?ajax', 1, URL+'zglos/wyswietl');
    });
    $('#prawo').on('click', '.np', function()
    {
        $('.panel-body').laduj('?wyswietl&np&ajax', 1, '?wyswietl&np');
    });
    $('#prawo').on('click', '.powrot', function()
    {
        $('.panel-body').laduj(URL+'zglos/?ajax', 1, URL+'zglos');
    });
    $('#prawo').on('click', '.zglos', function()
    {
        var params = {};
        params['opis'] = $('textarea[name="opis"]').val();
        params['tytul'] = $('textarea[name="tytul"]').val();
        $.post(URL+'zglos/zglos/?ajax', params, function(data){
            $(".panel-body").html(data);
            $.scrollTo("0%", 300);
            history.replaceState(null, null, URL+'zglos/zglos');
        });
    });
    $('#prawo').on('click', '.usun', function()
    {
        var id = $(this).attr('button-id');
        $('.modal').modal("hide");
        $('.modal').on('hidden.bs.modal', function () {
            $('.panel-body').laduj(URL+'/zglos/usun/'+id+'/?ajax', 1, URL+'/zglos/usun/'+id);
        });
    });
    $('#prawo').on('click', '.popraw', function()
    {
        var id = $(this).attr('button-id');
        $('.modal').modal("hide");
        $('.modal').on('hidden.bs.modal', function () {
            $('.panel-body').laduj(URL+'zglos/popraw/'+id+'/?ajax', 1, URL+'zglos/popraw/'+id);
        });
    });
});