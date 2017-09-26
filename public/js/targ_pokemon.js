$(document).ready(function()
{
    function pokemony_rysuj()
    {
        var id = $('#id_poka').val();
        var tekst = "targ_pokemon.php?szukaj&ajax&";
        if(id == '') tekst += "id=0";
        else tekst += "id="+id;
        var min_poz = $('#min_poziom').val();
        if(min_poz != '') tekst += "&min_poz="+min_poz;
        var max_poz = $('#max_poziom').val();
        if(max_poz != '') tekst += "&max_poz="+max_poz;
        var min_cena = $('#min_cena').val();
        if(min_cena != '') tekst += "&min_cena="+min_cena;
        var max_cena = $('#max_cena').val();
        if(max_cena != '') tekst += "&max_cena="+max_cena;
        $('#zawartosc').load(tekst, function(){if($('#tooltip').length) $('[data-toggle="tooltip"]').tooltip({html: true});});  
    }

    $('.szukaj').click(function()
    {
        pokemony_rysuj();
    });
    $('#prawo').on('keydown', '.form-control', function(event)
    {
       if(event.which === 13)
       {
           pokemony_rysuj();
       }
    });
    $('#prawo').on('click', '.kup_pokemona', function()
    {
        var id = this.id;
        var tekst = 'targ_pokemon.php?szukaj&ajax&kup_id=' + id;
        $('#zawartosc').load(tekst, function(){if($('#tooltip').length) $('[data-toggle="tooltip"]').tooltip({html: true}); $('#tabelka').load('lewo.php');}); 
    });
    $('#prawo').on('click', '.data_pok_info', function()
    {
        $.getJSON('pokemon.php?ajax&modal&m&id='+$(this).attr("data-pok-id"), function(json)
        {
            $('.modal-title').text(json.title);
            $('.modal-body').html(json.body);
        });
    $('#pokemon_modal').modal("show");
    });
});
