$.getScript('//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js');
$(document).ready(function() {
    var zaz = 0;
    var id_konst = 0;
    var war = 0;
    function e(a)
    {
        var w = $('#'+a+'_wartosc').text();
        w = w.replace('.', '');
        w = parseInt(w);
        if($('input[name="'+a+'"]').is(":checked"))
        {
            zaz++;
            war = war + w;
        }
        else
        {
            zaz--;
            war = war - w;
        }
        $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
        $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+numeral(war).format('0,0').replace(',', '.')+' &yen;');
    }
    function zamknij()
    {
        $('#menu_hodowla').hide();
        id_konst = 0;
    }
    $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
    $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
    $('#prawo').on('contextmenu', '.hodowla-btn', function(event)
    {
        id_konst = $(this).attr('name');
        var mousex = event.clientX + 10;
	var mousey = event.clientY + 10;
        //event.pageX + ", " + event.pageY;
        $('#menu_hodowla').css({ top: mousey, left: mousex }).fadeIn(200);
        $( "#menu_hodowla" ).each( function() 
        {
            var windowHeight = $(window).innerHeight();
            var pageScroll = $(window).scrollTop();
            var offset = $( this ).offset().top;
            var space = windowHeight - (offset - pageScroll) ;

            if( space < 220 ) {
                $( this ).addClass( "dropup" );
            } else  {
                $( this ).removeClass( "dropup" );
            }
        });
        return false;
    });
    $(document).click(function()
    {
        zamknij();
    });
    $('.dropdown-menu_dr').on('click', '.info', function()
    {
        $.getJSON('pokemon/id/'+id_konst+'?ajax&modal&t', function(json)
        {
            $('.modal-title[name="pokemon_modal"]').text(json.title);
            $('.modal-body[name="pokemon_modal"]').html(json.body);
        });
        zamknij();
        $('#pokemon_modal').modal("show");
    });
    $('#prawo').on('click', '#zaznacz_wszystkie', function()
    {
        $('.hodowla-btn').addClass('active');
        $('.hodowla').each(function() 
        {
            var a = $(this).prop("checked");
            if(!a)
            {
                $(this).prop("checked", "true");
                e($(this).attr("name"));
            }
        });
        
    });
    $('#prawo').on('change', '.hodowla', function()
    {
        var id = $(this).attr("name");
        e(id);      
    });
    $('#prawo').on('click', '#wszystkie', function()
    {
        war = 0;
        zaz = 0;
        $('.panel-body').load(URL+"kupiec/wszystkie/?komunikat");
        $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
        $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
        history.replaceState( null,null, URL+"kupiec/wszystkie" );
        
    });
    $('#prawo').on('click', '#tak', function()
    {
        $('#prawo').load(URL+"kupiec/wszystkie/1/?ajax", function()
        {
            war = 0;
            zaz = 0;
            $('#tabelka').load(URL+'lewo');
            $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
            $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
        });
        history.replaceState( null,null, URL+"kupiec/wszystkie/1" );
    });
    $('#prawo').on('click', '#nie', function()
    {
        $('#prawo').load(URL+"kupiec?ajax", function()
        {
            war = 0;
            zaz = 0;
            $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
            $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
        });
        
        history.replaceState( null,null, URL+"kupiec" );
        
    });
    $('#prawo').on('click', '#zaznaczone', function()
    {
        var dane;
        var i = 0;
        $('.hodowla').each(function()
        {
            var a = $(this).prop("checked");
            if(a)
            {
                if(i == 0) dane = $(this).attr("name");
                else dane = dane + '&' + $(this).attr("name");
                i++;
            }
        });
        $('#hodowla_panel').load(URL+"kupiec/zaznaczone/?ajax",dane, function()
        {
            war = 0;
            zaz = 0;
            $('#tabelka').load(URL+'lewo');
            $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
            $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
        });
        
        history.replaceState( null,null, URL+"kupiec/zaznaczone" ); 
    });
    $('.dropdown-menu_dr').on('click', '.sprzedaj_jeden', function()
    {
        $('#hodowla_panel').load(URL+"kupiec/zaznaczone/?ajax", id_konst , function()
        {
            zamknij();
            war = 0;
            zaz = 0;
            $('#zaznaczonych').html('Zaznaczono '+zaz+' Pokemonów.');
            $('#wartosc_zaznaczonych').html('Wartość zaznaczonych Pokemonów '+war+' &yen;');
            $('#tabelka').load(URL+'lewo');
        });
    });
});
