$(document).ready(function()
{
    var u = 0;
    $('#prawo').on('click', '.usun', function()
    {
        u = 1;
        var id = this.id.slice(5, this.id.length);
        $("#info").load(URL+'raporty/usun/'+id+'/?ajax', function(){
            $('.panel-body').load(URL+'poczta/?ajax');
        });
        setTimeout( function(){u = 0;}, 1000);
    });
    $('#prawo').on('click', '.usun_w', function()
    {
        $("#info").laduj(URL+'raporty/usunAll/?ajax', 1);
    });
    $('#prawo').on('click', '.tak', function()
    {
        $("#info").load(URL+'raporty/usunAll/?ajax&potw=1', function (){
            $('.panel-body').load(URL+'poczta/?ajax');
        });
    });
    $('#prawo').on('click', '.nie', function()
    {
        $("#info").text('');
    });
    $('#prawo').on('click', '.wiadomosc', function()
    {
        if(u == 0)
        {
            var id = this.id;
            $('.modal-body').text("ŁADOWANIE");
            $('.modal-title').text("");
            $.getJSON(URL+'raporty/id/'+id+'/?ajax', function(json)
            {
                $('.modal-title').text(json.title);
                $('.modal-body').html(json.body);
            });
            $("#raport_modal").modal("show");
        }
    });
    
});