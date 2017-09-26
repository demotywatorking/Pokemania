$(document).ready(function()
{
    var zaz_rezerwa = 0;
    var zaz_poczekalnia = 0;
    var id_konst = 0;
    $('.dropdown-menu_dr').dropdown("toggle");
    function zamknij()
    {
        $('#menu_poczekalnia').hide();
        $('#menu_rezerwa').hide();
        id_konst = 0;
    }
    function n()
    {
        zaz_rezerwa = 0;
        zaz_poczekalnia = 0;
    }
    function e_rezerwa(a)
    {
        if($('input[name="'+a+'"]').is(":checked"))
            zaz_rezerwa++;
        else
            zaz_rezerwa--;
        
        if(zaz_rezerwa > 0) $('#zaznaczonych_rezerwa').html('<span>Zaznaczono '+zaz_rezerwa+' Pokemonów.</span><span class="pull-right"><div class="dropdown"><button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Przenieś do:<span class="caret"></span></button><ul class="dropdown-menu dropdown-menu_dr"><li class="kursor druzyna_rez"><a>DRUŻYNY</a></li><li class="kursor poczekalnia_rez"><a>POCZEKALNI</a></li></ul></div></span>').show();
        else $('#zaznaczonych_rezerwa').hide();
    }
    function e_poczekalnia(a)
    {
        if($('input[name="'+a+'"]').is(":checked"))
            zaz_poczekalnia++;
        else
            zaz_poczekalnia--;
        
        if(zaz_poczekalnia > 0) $('#zaznaczonych_poczekalnia').html('<span class="text-center">Zaznaczono '+zaz_poczekalnia+' Pokemonów.</span><span class="pull-right"><div class="dropdown"><button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Przenieś do:<span class="caret"></span></button><ul class="dropdown-menu dropdown-menu_dr"><li class="kursor druzyna_pocz"><a>DRUŻYNY</a></li><li class="kursor rezerwa_pocz"><a>REZERWY</a></li></ul></div></span>').show();
        else $('#zaznaczonych_poczekalnia').hide();
    }
    $('#prawo').on('click', '.up', function()
    {
        var id = $(this).attr('pok-id');
        $('#content').load(URL+'pokemony/up/'+id+'/?ajax', function()
        {
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('click', '.down', function()
    {
        var id = $(this).attr('pok-id');
        $('#content').load(URL+'pokemony/down/'+id+'/?ajax', function()
        {
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('click', '.rezerwa', function()
    {
        var id = $(this).attr('pok-id');
        $('#content').load(URL+'pokemony/rezerwa/'+id+'/?ajax', function()
        {
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('change', '.rezerwa_zaz', function()
    {
        var id = $(this).attr("name");
        e_rezerwa(id);
    });
    $('#prawo').on('change', '.poczekalnia_zaz', function()
    {
        var id = $(this).attr("name");
        e_poczekalnia(id);
    });
    $('#prawo').on('contextmenu', '.poczekalnia-btn', function(event)
    {
        id_konst = $(this).attr('name');
        var mousex = event.clientX + 10;
	var mousey = event.clientY + 10;
        //event.pageX + ", " + event.pageY;
        $('#menu_poczekalnia').css({ top: mousey, left: mousex }).fadeIn(200);
        $( "#menu_poczekalnia" ).each( function() 
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
    $('#prawo').on('contextmenu', '.rezerwa-btn', function(event)
    {
        id_konst = $(this).attr('name');
        var mousex = event.clientX + 10;
	var mousey = event.clientY + 10;
        //event.pageX + ", " + event.pageY;
        $('#menu_rezerwa').css({ top: mousey, left: mousex }).fadeIn(200);
        $( "#menu_rezerwa" ).each( function() 
        {
            var windowHeight = $(window).innerHeight();
            var pageScroll = $(window).scrollTop();
            var offset = $( this ).offset().top;
            var space = windowHeight - ( offset - pageScroll );

            if( space < 180 ) {
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
    $('.dropdown-menu_dr').on('click', '.przenies_rez', function()
    {
        var gdzie = this.id.slice(0, -1);
        //laduj('.tab-content','pokemony.php?ajax&active=2&'+gdzie+'&'+id_konst, 1);
        var params = {};
        params[id_konst] = 1;
        $.post(URL+'pokemony/'+gdzie+'/?ajax&active=2', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
        //$('.tab-content').load('pokemony.php?ajax&active=2&'+gdzie+'&'+id_konst ,function(){$('#tabelka').load('lewo.php'); $.scrollTo('0%', 400);});
        zamknij();
        n();
    });
    $('.dropdown-menu_dr').on('click', '.przenies_pocz', function()
    {
        var gdzie = this.id.slice(0, -1);
        var params = {};
        params[id_konst] = 1;
        $.post(URL+'pokemony/'+gdzie+'/?ajax&active=3', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
        zamknij();
        n();
    });
    $('#prawo').on('click', '.targ-btn',function()
    {
        $.getJSON('pokemon.php?ajax&modal&t&id='+ $(this).attr("name"), function(json)
        {
            $('.modal-title[name="pokemon_modal"]').text(json.title);
            $('.modal-body[name="pokemon_modal"]').html(json.body);
        });
        $('#pokemon_modal').modal("show");
    });
    $('.dropdown-menu_dr').on('click', '.info', function()
    {
        $('.modal-body[name="pokemon_modal"]').load(URL+'pokemon/'+id_konst+'/?ajax&modal');
        zamknij();
        $('#pokemon_modal').modal("show");
        /*
        $.getJSON('pokemon/'+id_konst+'/?ajax&modal', function(json)
        {
            $('.modal-title[name="pokemon_modal"]').text(json.title);
            $('.modal-body[name="pokemon_modal"]').html(json.body);
        });
        zamknij();
        $('#pokemon_modal').modal("show");*/
    });
    $('.dropdown-menu_dr').on('click', '.hodowla', function()
    {
        $('.modal-title[name="pokemon_modal"]').text('SPRZEDAŻ POKA'); 
        $('.modal-body[name="pokemon_modal"]').text('');
        $('.modal-body[name="pokemon_modal"]').load('kupiec/zaznaczone/?ajax&komunikat&'+id_konst);
        $('#content').load(URL+'pokemony/?ajax&active=3', function(){
            $('#tabelka').load(URL+'lewo');
        });
        zamknij();
        n();
        $('#pokemon_modal').modal("show");
    });
    $('.dropdown-menu_dr').on('click', '.wystaw', function()
    {
        window.location = URL+'targ/wystaw/pokemon/?h='+id_konst;
    });
    $('#prawo').on('click', '.rezerwa_pocz', function()
    {
        n();
        var params = {};
        $('.poczekalnia_zaz').each(function()
        {

            var a = $(this).prop("checked");
            if(a)
            {
                params[$(this).attr("name")] = 1;
            }
        });
        $.post(URL+'pokemony/rezerwa/?ajax&active=3', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('click', '.poczekalnia_rez', function()
    {
        n();
        var params = {};
        $('.rezerwa_zaz').each(function()
        {

            var a = $(this).prop("checked");
            if(a)
            {
                params[$(this).attr("name")] = 1;
            }
        });
        $.post(URL+'pokemony/poczekalnia/?ajax&active=2', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('click', '.druzyna_pocz', function()
    {
        n();
        var params = {};
        $('.poczekalnia_zaz').each(function()
        {

            var a = $(this).prop("checked");
            if(a)
            {
                params[$(this).attr("name")] = 1;
            }
        });
        $.post(URL+'pokemony/druzyna/?ajax&active=3', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
    $('#prawo').on('click', '.druzyna_rez', function()
    {
        n();
        var params = {};
        $('.rezerwa_zaz').each(function()
        {

            var a = $(this).prop("checked");
            if(a)
            {
                params[$(this).attr("name")] = 1;
            }
        });
        $.post(URL+'pokemony/druzyna/?ajax&active=2', params, function(data){
            $("#content").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
});