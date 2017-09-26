$(document).ready(function()
{
  function kliknij_wystaw(id)
  {
    var cena = $('#cena_pok_'+id).val();
    if(cena == "Cena w Y") alert('Wpisz cenę!')
    else
    {
      var wiadomosc = $('#wiadomosc_pok_'+id).val();
      wiadomosc = encodeURIComponent(wiadomosc);
      var tekst = "targ_wystaw_pokemon.php?ajax&wystaw_id="+id+"&cena="+cena+"&opis="+wiadomosc;
      $('.tab-content').load(tekst);
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
    var tekst = "targ_wystaw_pokemon.php?active=2&ajax&wycofaj_id="+id;
    $('.tab-content').load(tekst);
  });
  $('#prawo').on('click', '.data_pok_info', function()
    {
        $.getJSON('pokemon.php?ajax&modal&t&id='+$(this).attr("data-pok-id"), function(json)
        {
            $('.modal-title').text(json.title);
            $('.modal-body').html(json.body);
        });
    $('#pokemon_modal').modal("show");
    });


});