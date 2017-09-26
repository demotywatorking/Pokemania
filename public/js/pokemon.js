$(document).ready(function()
{
    $('#prawo').on('click', '.zapisz', function()
    {
        var id = this.id;
        var wart = $('textarea[id=opis_t_'+id+']').htmlcode();
        wart = encodeURIComponent(wart);
        $('#pok_content_'+id).load('pokemon.php?ajax&id='+id+'&opis='+wart, function(){$('#tabelka').load('lewo.php');});
    });
    $('#prawo').on('click', '.nazwa', function()
    {
       var id = $(this).attr('name');
       var imie = $('input[name=nazwa_'+id+']').val();
       $('#pok_content_'+id).load('pokemon.php?ajax&id='+id+'&imie='+imie, function(){$('#tabelka').load('lewo.php');});
    });
    $('#prawo').on('click', '.btn', function()
    {
        var id = this.id;
        var idd = id.slice(0, id.indexOf('&'));
        $('#pok_content_'+idd).load('pokemon.php?ajax&id='+id, function(){$('#tabelka').load('lewo.php');});
    });
    $('#prawo').on('click', '.nakarm', function()
    {
        var id = $(this).attr('pokemon-id');
        $('#nakarm_'+id).load('pokemon.php?ajax&nakarm='+id);
    });
});