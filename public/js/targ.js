$(document).ready(function()
{
    function targ_kup_przedmiot(id)
    {
        var ilosc =  $('#ilosc_'+id).val();
        if (ilosc == '') ilosc = 1;
        var przedmiot = $('#przedmiot').text();
        var tekst = URL+"targ/kup/"+id + "/" + ilosc + "/"+przedmiot+"/?ajax";
        $('#zawartosc').load(tekst, function()
        {
            $('#tabelka').load(URL+'lewo');
        });
    }
    $('#prawo').on('click', '.btn', function()
    {
       $('.btn').removeClass('active');
       $(this).addClass('active'); 
    });
    $('#prawo').on('click', '.przedmiot', function()
    {
          var id = this.id;//nazwa przedmiotu
          var rysuj = URL+"targ/szukaj/" + id+'/?ajax';
          $('#zawartosc').load(rysuj);
          history.replaceState(null, null, URL+"targ/szukaj/" + id);
    });
    $('#prawo').on('click','.strona',function()
    {
      var przedmiot = $('#przedmiot').text();
      var a = this.id;
      var tekst = "targ.php?szukaj&ajax&przedmiot="+przedmiot+"&p="+a;
      $('#zawartosc').load(tekst);
    });
  $('#prawo').on('click','.kup',function()
  {
    var id = this.id;
    targ_kup_przedmiot(id);
  });
  $('#prawo').on('keydown','.targ_ilosc',function( event)
  {
    if ( event.which === 13 )
    {
      var dlugosc = this.id.length;
      var id = new Array(dlugosc-6);
      id = this.id.substr( 6 );
      targ_kup_przedmiot(id);
    }
  });
    function pokemony_rysuj()
    {
        var id = $('#id_poka').val();
        var tekst = URL+"targ/pokemon/szukaj/";
        if(id == '') tekst += "0";
        else tekst += "/"+id;
        var min_poz = $('#min_poziom').val();
        if(min_poz != '') tekst += "/"+min_poz;
        else tekst += "/"+0;
        var max_poz = $('#max_poziom').val();
        if(max_poz != '') tekst += "/"+max_poz;
        else tekst += "/"+0;
        var min_cena = $('#min_cena').val();
        if(min_cena != '') tekst += "/"+min_cena;
        else tekst += "/"+0;
        var max_cena = $('#max_cena').val();
        if(max_cena != '') tekst += "/"+max_cena;
        else tekst += "/"+0;
        tekst += "/?ajax";
        $('#prawo').load(tekst, function(){if($('#tooltip').length) $('[data-toggle="tooltip"]').tooltip({html: true});});
    }
    $(document).on('click', '.szukaj', function(){
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
        var tekst = URL+'targ/pokemon/kup/' + id + '/?ajax';
        $('#zawartosc').load(tekst, function(){if($('#tooltip').length) $('[data-toggle="tooltip"]').tooltip({html: true}); $('#tabelka').load(URL+'lewo');});
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
    function kliknij_wystaw(id)
    {
        var cena = $('#cena_pok_'+id).val();
        if(cena == "Cena w Y" || cena == 0) alert('Wpisz cenę!');
        else {
            var wiadomosc = $('#wiadomosc_pok_'+id).val();
            var params = {};
            params['cena'] = cena;
            params['wiadomosc'] = wiadomosc;
            params['id'] = id;
            $.post(URL+'targ/wystawianie/pokemon/?ajax', params, function(data){
                $(".tab-content").html(data);
                $.scrollTo("0%", 300);
            });
        }
    }

    $('#prawo').on('keydown','.cena_pok',function( event)
    {
        if ( event.which == 13 )
        {
            var dlugosc = this.id.length;
            var id = new Array(dlugosc-9);
            id = this.id.substr( 9 );
            kliknij_wystaw(id);
        }
    });
    $('#prawo').on('keydown','.wiadomosc_pok',function( event)
    {
        if ( event.which == 13 )
        {
            var dlugosc = this.id.length;
            var id = new Array(dlugosc-14);
            id = this.id.substr( 14 );
            kliknij_wystaw(id);
        }
    });
    $('#prawo').on('click','.wystaw_poka',function()
    {
        var id = this.id;
        kliknij_wystaw(id);
    });

    $('#prawo').on('click','.wycofaj_poka',function()
    {
        var id = this.id;
        var tekst = URL+"targ/wycofaj/pokemon/"+id+"/?active=2&ajax";
        $('.tab-content').load(tekst);
    });
    $('#prawo').on('click', '.data_pok_info', function()
    {
        $('.modal-body').load(URL+'pokemon/'+$(this).attr("data-pok-id")+'/?ajax&modal');
        $('#pokemon_modal').modal("show");
        /*
        $.getJSON('pokemon.php?ajax&modal&t&id='+$(this).attr("data-pok-id"), function(json)
        {
            $('.modal-title').text(json.title);
            $('.modal-body').html(json.body);
        });
        $('#pokemon_modal').modal("show");*/
    });
    $('#prawo').on('click','.wycofaj',function()
    {
        var id = this.id;
        var tekst = URL+"targ/wycofaj/przedmiot/"+id+"/?ajax&active=2";
        $('.tab-content').load(tekst);
    });
    function kliknij_wystaw_przedmiot(id)
    {
        var ilosc =  $('#ilosc_'+id+'').val();
        if(ilosc <= 0) alert("Błędna ilość");
        else
        {
            var cena =  $('#cena_'+id+'').val();
            if(cena <= 0) alert("Błędna cena");
            else
            {
                var params = {};
                params['cena'] = cena;
                params['ilosc'] = ilosc;
                params['nazwa'] = id;
                $.post(URL+'targ/wystawianie/przedmiot/?ajax', params, function(data){
                    $(".tab-content").html(data);
                    $.scrollTo("0%", 300);
                });
            }
        }
    }
    $('#prawo').on('click','.wystaw_przedmiot',function()
    {
        var id = this.id;//Cheri_berry itp.
        kliknij_wystaw_przedmiot(id);
    });
});