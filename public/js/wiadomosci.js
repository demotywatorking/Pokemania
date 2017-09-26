$(document).ready(function()
{
    var u = 0;
    var il = 0;
    var id;
    var lock = 0;
    var mod = 0;
    var ilosc = 0;
    var st = 1;
    function nowe()
    {
        if(st == 0)
        {
            st = 1;
            if(tylko_nowe())st = 0;
        }
        setTimeout(nowe, 5000);
    }
    function tylko_nowe()
    {
        if(mod == 1 && st == 1)
        {
            $.getJSON(URL+'wiadomosci/id/'+id+'/'+ilosc+'/1/?ajax', function(json)
            { 
                $('[data-toggle="tooltip"]').tooltip({html: true});
                if(parseInt(json.ilosc) > 0)
                {
                    //alert(json.ilosc);
                    //alert(parseInt(json.ilosc));
                    il += parseInt(json.ilosc);
                    ilosc += parseInt(json.ilosc);
                    json.body = seviEmo.makeEmo(json.body);
                    if($('.scroll').append(json.body))$('.scroll').scrollTo('100%');
                }
            });
            return true;
        }
        else return false;
    }
    function load_w()
    {
        $.getJSON(URL+'wiadomosci/id/'+id+'/'+il+'/?ajax', function(json)
        {
            if(json.body != '')
            {
                json.body = seviEmo.makeEmo(json.body);
                $('.scroll').prepend(json.body);
                lock = 0;
                $('[data-toggle="tooltip"]').tooltip({html: true});
                il = il + 30;
            }
        });
    }
        
    function odpisz(text)
    {
        if(text !== '')
        {
            st = 1;
            $.when(tylko_nowe()).then(function()
            {
                text = encodeURIComponent(text);
                $.getJSON(URL+'wiadomosci/id/'+id+'/0/0/1/?ajax&text='+text, function(json)
                {
                    if(json.status == 'pusta')
                    {
                        $('.scroll').append('<div class="width100 pusta">Wiadomość nie może być pusta!</div>');
                        $('.scroll').scrollTo('100%');
                    }
                    else if(json.status == 'OK')
                    {
                        json.body = seviEmo.makeEmo(json.body);
                        $('.scroll').append(json.body);
                        $('.scroll').scrollTo('100%');
                        il++;
                        ilosc++;
                    }
                    st = 0;
                    $('[data-toggle="tooltip"]').tooltip({html: true});
                    
                    //$('.modal-title').text(json.title);
                    //$('.modal-body').html(json.body);
                    //$('[data-toggle="tooltip"]').tooltip({html: true});
                });
            });
        }
        else {$('.scroll').append('<div class="width100 pusta">Wiadomość nie może być pusta!</div>');$('.scroll').scrollTo('100%');}
    }
    $('#prawo').on('click','.wiadomosc', function()
    {  
        if(u == 0)
        {
            id = this.id;
            $('.modal-body').text("ŁADOWANIE");
            $('.modal-title').text("");
            mod = 1;
            $("#wiadomosci_modal").modal("show");
            $.getJSON(URL+'wiadomosci/id/'+id+'/?ajax', function(json)
            {
                $('.modal-title').text(json.nadawca);
                $('.modal-body').html(json.body);
                $('[data-toggle="tooltip"]').tooltip({html: true});
                lock = 0;
                $('.wiadomosc_tresc').each(function()
                {
                    $(this).html((seviEmo.makeEmo($(this).text())));
                });
            });
            setTimeout(nowe, 5000);
            $(document).on('shown.bs.modal', function()
            {
                $('.scroll').scrollTo('100%');
                ilosc = parseInt($('.ost').attr('id'));
                $('.odpisz').focus();
            });
            il = il + 30;
        }
    });
    $(document).on('hide.bs.modal', function()
    {
        mod = 0;
        id = '';
        il = 0;
        ilosc = 0;
    });
    $('#wiadomosci_modal').on("wheel", ".scroll" ,function()
    {
        if($(this).scrollTop() < 80 )
        {
            if(lock == 0)
            {
                //alert('0');
                load_w();
                lock = 1;
            }
        }
    });
    $('#wiadomosci_modal').on('keydown', '.odpisz', function(e)
    {
        if(e.which == 13)//enter
        {
            if(!e.shiftKey)
            {
                e.preventDefault();
                var text = $(this).val();
                $(this).val('');
                odpisz(text);
            }
        }
    });
    $("#prawo").on('click', '.nowa', function()
    {
        $('.panel-body').load(URL+'wiadomosci/nowa/?ajax');
        history.replaceState('null','null', URL+'wiadomosci/nowa');
    });
    $('#prawo').on('click', '.wyslij', function()
    {
        var params = {};
        params['odbiorca'] = $('#odbiorca').val();
        params['tresc'] = $('#tresc').val();
        $.post(URL+'wiadomosci/wyslij/?ajax', params, function(data){
            $(".panel-body").html(data);
            $('#tabelka').load(URL+'lewo');
            $.scrollTo("0%", 300);
        });
    });
});
