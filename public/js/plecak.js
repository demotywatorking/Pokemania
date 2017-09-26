$(document).ready(function()
{
function select()
{
  $(function() {
    $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
      _renderItem: function( ul, item ) {
        var li = $( "<li>", { text: item.label } );
 
        if ( item.disabled ) {
          li.addClass( "ui-state-disabled" );
        }
        $( "<span>", {
          style: item.element.attr( "data-style" ),
          "class": "ui-icon " + item.element.attr( "data-class" )
        })
          .appendTo( li );
 
        return li.appendTo( ul );
      }
    });

    $( "#ciastko_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#baton_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#karma_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#ogniste_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#wodne_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#gromu_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#ksiezycowe_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#roslinne_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Cheri_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Wiki_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Pecha_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Aguav_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Leppa_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Oran_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Persim_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Lum_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Sitrus_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#Figy_Berry_pok" )
      .iconselectmenu()
      .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );
    $( "#candy_pok" )
          .iconselectmenu()
          .iconselectmenu( "menuWidget" )
            .addClass( "ui-menu-icons customicons" );
  });
}
  
  select();
  function laduj(adres)
  {
     $('.tab-content').load(adres, function()
     {
      $('#tabelka').load(URL+'lewo');
      select();
    });
  }
  function wypij(co)
  {
    var ilosc = 1;
    if(co != 'lemoniada')
    {
         ilosc = $('#'+co+'_ilosc').val();
    }
    if(ilosc == '')ilosc = 1;
    var poke = 0;
    if(co == 'karma' || co == 'baton' || co == 'ciastko')
    {
         var pok = $('#'+co+'_pok').val();
         poke = pok;
    }
    if(co == 'candy')
    {
        var pok = $('#'+co+'_pok').val();
        poke = pok;
    }
    laduj(URL+'plecak/rodzaj/'+co+'/'+ilosc+'/'+poke + '/?ajax');
   }
  function kamien(co)
  {
   co += '/' + $('#'+co+'_pok').val();
   laduj('plecak/kamien/'+co+'/?ajax&active=4');
  }
  function jagoda(co)
  {
    var ilosc = $('#'+co+'_ilosc').val();
    if(ilosc == '')ilosc = 1;
    var pok = '';
    if($('#'+co+'_pok').length)
    {
      pok = '/' + $('#'+co+'_pok').val();
    }
    co += '/' + ilosc; 
    laduj('plecak/jagoda/' + co + pok + '/?active=3&ajax');
  }
  function jagoda_all(co)
  {
   laduj('plecak/jagoda/'+co+'/all/?active=3&ajax');
  }
  function jagoda_max(co)
  {
    if($('#'+co+'_pok').length)
    {
      co += '/max/' + $('#'+co+'_pok').val();
    } else {
        co += '/all';
    }
   laduj('plecak/jagoda/'+co+'/?active=3&ajax');
  }
  $('#prawo').on('click', '.potwieredzeniewypicia', function()
  {
      $('.tab-content').load(this.id);
  });
  $('#prawo').on('click', '.karta', function()
  {
      var co =  this.id;
    var adres =  $(this).attr('name');
    $("#" + co + '_opis').modal("hide");
    $("#" + co + '_opis').on('hidden.bs.modal', function () {
     laduj(adres+'/?active=7');
     });
   
  });
  $('#prawo').on('click', '.wypij', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    wypij(co);
    });
  });
  $('#prawo').on('click', '.wypij_all', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    laduj(URL+'plecak/rodzaj/karma/max/?ajax');
    });
  });
  $('#prawo').on('click', '.jagoda', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    jagoda(co);
    });
  });
  $('#prawo').on('click', '.jagoda_all', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    jagoda_all(co);
    });
  });
  $('#prawo').on('click', '.jagoda_max', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    jagoda_max(co);
    });
  });
  $('#prawo').on('click', '.kamien', function()
  {
   var co =  this.id;
   $("#" + co + '_opis').modal("hide");
   $("#" + co + '_opis').on('hidden.bs.modal', function () {
    kamien(co);
    });
  });
  $('#prawo').on('shown.bs.modal', function()
  {
      $(this).find('input').focus();
  });
  $('#prawo').on('keydown','.ilosc_jagoda',function( event)
  {
    if ( event.which == 13 )
    {
      var id = this.id.slice( 0, -6 );
      $("#" + id + '_opis').modal("hide");
      $("#" + id + '_opis').on('hidden.bs.modal', function () {
      jagoda(id);
    });
     
    }
  });
  $('#prawo').on('keydown','.ilosc_wypij',function( event)
  {
    if ( event.which == 13 )
    {
      var id = this.id.slice( 0, -6 );
      $("#" + id + '_opis').modal("hide");
      $("#" + id + '_opis').on('hidden.bs.modal', function () {
    wypij(id);
    });
      
    }
  });
  $('#prawo').on('click', '.wymien', function()
  { 
      var active = $(this).attr('name');
      window.location='wymien/?active='+active;
  });
});