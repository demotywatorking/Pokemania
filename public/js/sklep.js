$(document).ready(function() {

var lad = 0;
  function laduj(adres)
  {
      if(lad == 0 && $('.alert').width) $('.alert').hide();
      lad++;
        $('.tab-content').load(adres, function()
        {
         $('#tabelka').load(URL+'lewo');
       });
  }
 
  function kup_pokeball(id)
  {
    var ilosc = $('#'+id+'_ilosc').val();
    if(ilosc == '')ilosc = 1;
    laduj(URL+'sklep/pokeball/'+id+'/'+ilosc+'/?ajax');
    history.replaceState(null, null, URL+'sklep/pokeballe/'+id+'/'+ilosc);
  }
  function kup_przedmiot(id)
  {
    var ilosc = '';
    if($('#'+id+'_ilosc').length)
    {
      ilosc = $('#'+id+'_ilosc').val();
      if(ilosc == '')ilosc = 1;
      ilosc = ilosc;
    }   
    laduj(URL+'sklep/kup/'+id+'/'+ilosc+'/?ajax&active=2');
    history.replaceState(null, null, URL+'sklep/kup/'+id+'/'+ilosc+'/?active=2');
  }
  $('#prawo').on('click', '.kup_pokeball', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
       kup_pokeball(co);
    });
  });
  $('#prawo').on('click', '.kup_przedmiot', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    kup_przedmiot(co);
    });
  });
  $('#prawo').on('keydown','.ilosc_kup',function( event)
  {
    if ( event.which == 13 )
    {
      var id = this.id.slice( 0, -6 );
      $("#" + id + '_opis').modal("hide");
      $("#" + id + '_opis').on('hidden.bs.modal', function () {
            kup_pokeball(id);
    });
    }
  });
  $('#prawo').on('keydown','.ilosc_kup_przedmiot',function( event)
  {
    if ( event.which == 13 )
    {
      var id = this.id.slice( 0, -6 );
      $("#" + id + '_opis').modal("hide");
      $("#" + id + '_opis').on('hidden.bs.modal', function () {
    kup_przedmiot(id);
    });
    }
  });
 $('#prawo').on('shown.bs.modal', function()
  {
      $(this).find('input').focus();
  });
});
