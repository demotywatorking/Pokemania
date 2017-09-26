
jQuery.fn.extend(
{
    laduj: function(adres, scroll, replace) 
    {
      $(this).load(adres, function()
      {
          if($('#wyloguj').length) window.location='/wyloguj';
          $('#tabelka').load('http://pokemania.ml/lewo', function(){ tooltip_f(); if(scroll) $.scrollTo('0%', 300);});
          if(replace) history.replaceState( null,null, replace );
      });
    }
});
const URL = 'http://pokemania.ml/';
var klucz = null;
klucz_default = function(e)
{
    if(e.keyCode == 27)
            $('.modal').modal("hide");
    //if(e.keyCode == 9)
            //alert('Wciśnięto TAB');
};
klucz_polowanie = function(e)
{
    if(e.keyCode == 9)
            alert('Wciśnięto TAB');
    if (e.keyCode == 49)
    {
      var i = 1;
    }
    else if(e.keyCode == 27)
        $('.modal').modal("hide");
    else if (e.keyCode == 50)
    {
        var i = 2;
    }
    else if (e.keyCode == 51)
    {
        var i = 3;
    }
    else if (e.keyCode == 52)
    {
        var i = 4;
    }
    else if (e.keyCode == 53)
    {
        var i = 5;
    }
    else if (e.keyCode == 54)
    {
        var i = 6;
    }
    else if (e.keyCode == 82)//kontynuuj(r)
    {
        var id = $('#dzicz_ajax').text();
        if(kon_w == 1)kontynuuj(id);
        else setTimeout(kontynuuj(id), 200);
        kon_w = 0;
    }
    else if (e.keyCode == 69)//leczenie (e)
    {
      $('#leczenie_pokaz').show().html(lad).load(URL+'stopka/wylecz', function(){$('#tabelka').load(URL+'lewo');});
      setTimeout(function(){$('#leczenie_pokaz').fadeOut();}, 4000);
    }
    if(i > 0 && ($('.polowanie_wlasny_pok').length))
    {
      var id_poka = $('button[name="'+i+'"]');
      var id = $(id_poka).attr('id');
      if(id > 0)
      {
        var dzicz = $('#dzicz_ajax').text();
        $('.ladowanie').html(lad);
        $.scrollTo('0%', 300);
        $('#prawo').load('polowanie/dzicz/'+dzicz+'/walka/'+id+'/i/'+i+'/&ajax', function()
        {
            $('.ladowanie').html(zal);
            $('#dzicz_panel').html("POLOWANIE - " + dziczz());
            $('#tabelka').load('lewo.php', function(){tooltip_f();});

            kon_w = 1;

        });
      }
      i = 0;
    }
}
function tooltip_f()
{
    if($('#tooltip').length) $('[data-toggle="tooltip"]').tooltip({html: true});
}
//var wgrane = [];
//var ilosc = 1;
$(document).ready(function()
{
    klucz = klucz_default;
    $(document).keydown(function(e)
    {
        klucz(e);
    });
    
    if($('#nagroda_modal').length)
        $('#nagroda_modal').modal("show");
    
    ////////////////////test menu ajax//////////////////////////////////////////
    /*var mode1 = $('#mode').text();
    wgrane[0] = mode1;
    $.getScript('js/'+ mode1 +'.js');
    if(mode1 == 'polowanie') klucz = klucz_polowanie;
    else klucz = klucz_default;
    $('#menu_div, #stopka').on('click', 'a', function(e)
    {
        e.preventDefault();
        var co = $(this).attr('href');
        var co1 = co;
        if(co.indexOf('?') != -1) co += '&ajax=2';
        else co += '?ajax=2';
        var mode = $('#mode').text();
        if (typeof klucz !== 'undefined' && $.isFunction(klucz))klucz = null;
        $('#prawo').load(co, function()
        {
            $.scrollTo('0%', 300);
            history.replaceState( null,null, co1 );
            mode1 = $('#mode').text();
            var mozliwosc = 1;
            for(i = 0 ; i < ilosc ; i++)
                if(wgrane[i] == mode1){mozliwosc = 0; break;}
            if(mozliwosc)
            {
                $.getScript('js/'+ mode1 +'.js');
                wgrane[ilosc] = mode1;
                ilosc++;
            }
            if(mode1 == 'polowanie') klucz = klucz_polowanie;
            else klucz = klucz_default;
            tooltip_f();
        });
    });*/
    ////////////////////////////////////////////////////////////////////////////
    var id_poka_konst = 0;
    var id_i = 0;
    var imie = '';
    $('.dropdown-menu').dropdown("toggle");
    $('#tabelka').on('contextmenu', 'form[id="pokemony"]', function(event)
    {
        id_poka_konst = $(this).attr('name');
        id_i = $(this).attr('pok-i');
        imie = $(this).attr('pok-imie');
        $('.dropdown_imie').text(imie);
        var mousex = event.clientX + 10;
        var mousey = event.clientY + 10;
        //event.pageX + ", " + event.pageY;
        $('#menu_pokemona').css({ top: mousey, left: mousex }).fadeIn(200);
        $( '#menu_pokemona' ).each( function() 
        {
            var windowHeight = $(window).innerHeight();
            var pageScroll = $(window).scrollTop();
            var offset = $( this ).offset().top;
            var space = windowHeight - ( offset - pageScroll );
            if( space < 270 ) {
                $( this ).addClass( "dropup" );
            } else  {
                $( this ).removeClass( "dropup" );
            }
        });
        //$('#menu_pokemona').fadeIn();
        return false;
    });
     $('.dropdown-menu[id=menu_pokemon_list]').on('click', '.wylecz_centrum', function()
    {
        $('.modal-body[name="_modal"]').load(URL+'lecznica/wylecz/'+id_i+'/?komunikat&ajax', function()
        {
            $('.modal-title[name="_modal"]').text(imie+' - LECZENIE W CENTRUM POKEMON');
            $('#tabelka').load(URL+'lewo/?ajax', function(){tooltip_f();});
            zamknij_menu();
            $('#__modal').modal("show");
        });
    });
    $('.dropdown-menu[id=menu_pokemon_list]').on('click', '.info', function()
    {
        $('.modal-body[name="_modal"]').load(URL+'pokemon/'+id_poka_konst+'/?ajax&modal', function() {
            zamknij_menu();
            $('#__modal').modal("show");
        });
        /*$.getJSON(URL+'pokemon/'+id_poka_konst+'/?ajax&modal', function(json)
        {
            $('.modal-title[name="_modal"]').text(json.title);
            $('.modal-body[name="_modal"]').html(json.body);
        });*/
    });
    $('.dropdown-menu[id=menu_pokemon_list]').on('click', '.sala', function()
    {
        window.location = URL+'sala/'+id_poka_konst;
        zamknij_menu();
    });
    $('.dropdown-menu[id=menu_pokemon_list]').on('click', '.nakarm', function()
    {
        $('.modal-body[name="_modal"]').load(URL+'stopka/nakarm/'+id_poka_konst+'/?ajax', function()
        {
            $('.modal-title[name="_modal"]').text(imie+' - KARMIENIE');
            zamknij_menu();
            $('#__modal').modal("show");
        });

    });
    $('.dropdown-menu[id=menu_pokemon_list]').on('click', '.przenies_pocz', function()
    {
        $.getJSON('pokemony.php?ajax&modal&rezerwa='+id_poka_konst, function(json)
        {
            $('.modal-title[name="_modal"]').text(json.title);
            $('.modal-body[name="_modal"]').html(json.body);
            $('#tabelka').load(URL+'lewo/?ajax', function(){tooltip_f();});
            zamknij_menu();
            $('#__modal').modal("show");
        });
    });
    function zamknij_menu()
    {
        $('#menu_pokemona').hide();
        id_poka_konst = 0;
        id_i = 0;
        imie = '';
    }
    tooltip_f();
    
    $(document).click(function()
    {
        zamknij_menu();
    });
    
    
    
    
    
    if($('#zegar').length) odliczanie();
    if($('#beta').length)
    {	
        var tekst;
        tekst = '<div class="modal fade in" id="beta_modal" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>';
        tekst += '<span class="modal-title">BETA TESTY</span></div><div class="modal-body">';
        tekst += '<div class="well well-primary jeden_ttlo text-center"><span class="pogrubienie">Witaj na zamkniętych testach beta gry Pokemon.</span><br />';
        tekst += "Oto lista rzeczy, które jeszcze nie działają:<br />";
        tekst += "1.Niektóre ataki, które wymagają osobnego oskrypotowania.<br />";
        tekst += "2.Punkty do wykorzystania.<br />";
        tekst += "3.Niektóre wydarzenia w dziczy.<br />";
        tekst += "Dodatkowe informacje.<br />";
        tekst += "Jak zgłosić błąd:<br />";
        tekst += "W Menu wybierz opcję inne > zgłoś błąd.<br />";
        tekst += "Ewentualnie napisać PW do gracza o nicku: tester1<br />";
        tekst += "To okienko pojawi się jeden raz po zalogowaniu się.<br />";
        tekst += "Te informacje możesz przeczytać w zakładce zgłoś błąd<br /></div>";
        tekst += '</div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div></div></div></div>';

        $('#beta').html(tekst);
        $('#beta_modal').modal("show");
    }
    if($('#podpowiedz_').length)
    {
        var div = $( "<div id='podpowiedz'></div>" );
        $('#prawo').prepend( div );
        $('#podpowiedz').load(URL+'podpowiedzi.php');
        //$('#podpowiedz').load();
    }
	if($('#witaj').length)
	{
		var wys_czas = '';
		var godz = 0;
		var min = 0;
		var sek = 0;
		var dni = 0;
		var nazwa = $('#witaj').attr('name');
		var ost = $('#witaj').attr('href');
		//obliczenie czasu
		if(ost < 60) wys_czas =  ost + ' sekund.';
	      else if(ost < 3600)
	      {
	        min = Math.floor(ost / 60);
	        sek = ost - 60 * min;
	        wys_czas = min + ' minut '+ sek + ' sekund.';
	      }
	      else
	      {
	        dni = 0;
	        if(ost > 86400)
	        {
	          dni = Math.floor(ost / 86400);
	          ost -= dni * 86400;
	        }
	        godz = Math.floor(ost / 3600);
	        ost -= godz * 3600;
	        min = Math.floor(ost / 60);
	        sek = ost - 60*min;
	        if(dni > 0) wys_czas = dni + ' dni ';
	      	wys_czas += godz + ' godzin ' + min + ' minut ' + sek + ' sekund.';
	      }
		//obliczenie czasu  koniec
                var tekst;
		tekst = '<div class="modal fade in" id="witaj_modal" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>';
                tekst += '<span class="modal-title">Witaj '+ nazwa +'</span></div><div class="modal-body">';
        
		tekst +=' <div class="well well-primary jeden_ttlo text-center"><span class="pogrubienie">Witaj ponownie, '+ nazwa +"</span><br />";
		tekst += 'Cieszymy się, że wracasz do nas po przerwie, która trwała ' + wys_czas + '<br />';
                tekst += '</div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Zamknij</button></div></div></div></div>';


		$('#witaj').html(tekst);
                $('#witaj_modal').modal("show");
	}
	$('#witaj_zamknij').click(function()
	{
            $('#witaj').fadeOut();
	});
	$('#lewo').on('click', '#wiadomosci', function()
	{
            window.location=URL+'wiadomosci';
	});
	$('#lewo').on('click', '#raporty', function()
	{
            window.location=URL+'raporty';
	});
	$('#prawo').on('click', '.desktop', function() 
        {
            var mode = $(this).attr('name');
            $('#prawo').load(URL+'gra/desktop/'+mode+'/?ajax', function(){location.reload();});
        });
 /*var tyt = '';
 $('body').on('hover', "*[Title]", function()//fadein
 {
    tyt = $(this).attr("Title");
     $('#tytul').html(tyt).fadeIn(100);
     $(this).attr({
        title: ""
     });
 },
 function()//fadeout
 {
  $('#tytul').hide();
  $(this).attr({
     title: tyt
  });

 }).mousemove(function() {
  var dlugosc = $('#tytul').width() / 2;
  var position = $(this).offset();
  var this_w = $(this).width() / 2;
  //alert("lewo:" + position.left + " góra: " + position.top);
  var left_w = position.left + this_w - dlugosc;
 	var top_w = position.top - 50;



 		$('#tytul').css({  top: top_w, left: left_w });
  });*/

   var lad = '<div class="ladowanie"><img src=URL+"public/img/loader.gif" /> TRWA ŁADOWANIE</div>';
   function zaladuj(co)
   {
       var myVar = setTimeout(function(){$('#leczenie_pokaz').fadeOut();}, 4000);
   	$('#leczenie_pokaz').show().html('').load(co, function(){
            if($('.potwieredzeniewypicia').length){
                clearTimeout(myVar); 
            }
            $('#tabelka').load(URL+'lewo', function(){if($('#tooltip').length) tooltip_f();});
        });
   	
   }
    $('#leczenie').click(function()
    {
        zaladuj(URL+'stopka/wylecz');
    });
    $('#puszka_sody').click(function()
    {;
    	zaladuj(URL+'stopka/soda');
    });
    $('#puszka_wody').click(function()
    {
    	zaladuj(URL+'stopka/woda');
    });
    $('#puszka_lemoniady').click(function()
    {
    	zaladuj(URL+'stopka/lemoniada');
    });
    $('#Cheri_Berry_stopka').click(function()
    {
    	zaladuj(URL+'stopka/cheri');
    });
    $('#Wiki_Berry_stopka').click(function()
    {
    	zaladuj(URL+'stopka/wiki');
    });
    $('#nakarm_stopka').click(function()
    {
    	zaladuj(URL+'stopka/nakarm');
    });
});
