$(document).ready(function()
{
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
                var tekst = "targ_wystaw.php?ajax&nazwa="+id+"&ilosc="+ilosc+"&cena="+cena;
                $('.tab-content').load(tekst);
            }
        }
    }
    $('#prawo').on('click','.wycofaj',function()
    {
        var id = this.id;
        var tekst = "targ_wystaw.php?ajax&wycofaj&active=2&id="+id;
        $('.tab-content').load(tekst);
    });
    $('#prawo').on('click','.wystaw',function()
    {
      var id = this.id;//Cheri_berry itp.
      kliknij_wystaw_przedmiot(id);
    });
    $('#prawo').on('keydown','.wystaw_przedmiot_ilosc',function( event)
    {
      if ( event.which == 13 )
      {
        var dlugosc = this.id.length;
        var id = new Array(dlugosc-6);
        id = this.id.substr( 6 );
        kliknij_wystaw_przedmiot(id);
      }
    });
    $('#prawo').on('keydown','.wystaw_przedmiot_cena',function( event)
    {
      if ( event.which == 13 )
      {
        var dlugosc = this.id.length;
        var id = new Array(dlugosc-5);
        id = this.id.substr( 5 );
        kliknij_wystaw_przedmiot(id);
      }
    });
});