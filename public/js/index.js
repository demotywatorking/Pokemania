$(document).ready(function()
{
    var URL = 'http://pokemania.ml/';
  function nowe()
  {
      $('#nowe').load('http://pokemania.ml/index/ostatnie');
      setTimeout(nowe, 10000);
  }
  nowe();
  function a()
  {
    if(isAdblockEnabled === false)
        $('#informacja').hide();
  }
  a();

  var click_zal = 0;
  var click_zar = 0;
  var click_przyp = 0;
  var x = 0;
  var y = 0;
  function pokaz(id)
  {
    var w_width = $( '#content' ).width();
    if(click_przyp == 1)zamknij("przypomnij_div");
    if(wylogowano > 0)
    {
      wylogowano-=1;
      if(wylogowano == 0)
        zamknij("wylogowano");
    }
    if(zarejestrowano == 1 && id != "zarejestrowano")
    {
      zarejestrowano = 0;
      zamknij("zarejestrowano");
    }
    x = (w_width / 2) - 250;
    y = 300;
    if(id=="zaloguj")
    {
      click_zal = 1;
    }
    else if(id=="przypomnij_div")
    {
      click_przyp = 1;
      zamknij("zaloguj");
    }
    else if(id=="zarejestruj")
    {
      click_zar = 1;
      x = (w_width / 2) - 300;
      y = 40;
    }

    $('#'+id).css({  top: y, left: x });
    $('#'+id).fadeIn();
    $('#'+id).draggable();
  }
  function okienko (co, value)
  {
    $('#okienko_'+co).addClass('ladowanie');
    if(co == 'login')
    {
      $.get(URL+'SprawdzLogin/'+co+'/'+value, function (data)
      {
          $('#okienko_'+co).removeClass('ladowanie');
          if(data == 'OK')
          {
            $('#okienko_'+co).addClass('wszystko_ok');
            $('#okienko_'+co).attr('Title', 'Ten login jest wolny.');
          }
          else
          {
             $('#okienko_'+co).addClass('cos_zle');
             $('#okienko_'+co).attr('Title', 'Ten login jest już zajęty.');
           }
      });
    }
    else if(co == 'email')
    {
      $.get(URL+'SprawdzLogin/'+co+'/'+value, function (data)
      {
          $('#okienko_'+co).removeClass('ladowanie');
          if(data == 'OK')
          {
            $('#okienko_'+co).addClass('wszystko_ok');
            $('#okienko_'+co).attr('Title', 'Ten email jest wolny.');
          }
          else if(data == 'error, zly format')
          {
            $('#okienko_'+co).addClass('cos_zle');
            $('#okienko_'+co).attr('Title', 'Zły format emaila.');
          }
          else
          {
             $('#okienko_'+co).addClass('cos_zle');
             $('#okienko_'+co).attr('Title', 'Ten email jest już zajęty.');
           }
      });
    }
    //(document).load();
  }
  function zamknij(id)
  {
    if(id=="zaloguj") click_zal = 0;
    else if(id=="zarejestruj") click_zar = 0;
    else if(id=="przypomnij_div") click_przyp = 0;
    $('#'+id).fadeOut();
  }
  var pok = $('#pok_rejestracja_form').val();
  $('#pok_rejestracja').html('<img src="'+URL+'img/poki/'+pok+'.png" id="img_rej"/>');

  $('#pok_rejestracja_form').change(function()
  {
    pok = $(this).val();
    $('#pok_rejestracja').html('<img src="'+URL+'img/poki/'+pok+'.png" id="img_rej" />');
  });

  var aa = $('#wylogowano');
  var wylogowano = 0;
  if(aa.length)
  {
    pokaz("wylogowano");
    wylogowano = 1;
  }
  var bb = $('#zarejestrowano');
  var zarejestrowano = 0;
  if(bb.length)
  {
    pokaz("zarejestrowano");
    zarejestrowano = 1;
  }
  var xx = $('.zal_blad');
  if(xx.length)
  {
    pokaz("zaloguj");
  }
  var yy = $('.rej_blad_wys');
  if(yy.length)
  {
    pokaz("zarejestruj");
  }
  var zz = $('.przyp_blad');
  if(zz.length)
  {
    pokaz("przypomnij_div");
  }
  $('#zaloguj_sie_przycisk').click(function()
  {
    if(click_zar == 1)
    {
      zamknij("zarejestruj");
    }
    if(click_zal == 0)
    {
      var position = $(this).position();
      pokaz("zaloguj");
    }
    else
    {
      zamknij("zaloguj");
    }
  });
  $('#zarejestruj_sie_przycisk').click(function()
  {
    if(click_zal == 1)
    {
      zamknij("zaloguj");
    }
    if(click_zar == 0)
    {
      var position = $(this).position();
      pokaz("zarejestruj");
    }
    else
    {
      zamknij("zarejestruj");
    }
  });
  $('#zamknij_zaloguj').click(function()
  {
    zamknij("zaloguj");
  });
  $('#zamknij_zarejestruj').click(function()
  {
    zamknij("zarejestruj");
  });
  $('#zamknij_przypomnij').click(function()
  {
    zamknij("przypomnij_div");
  });
  $('#zamknij_wyloguj').click(function()
  {
    wylogowano -=1;
    zamknij("wylogowano");
  });
  $('#przypomnij').click(function()
  {
    pokaz("przypomnij_div");
  });
  $('#zaloguj_ponownie').click(function()
  {
    wylogowano = 0;
    zamknij("wylogowano");
    pokaz("zaloguj");
  });
  $('#zamknij_zarejestrowano').click(function()
  {
    zamknij("zarejestrowano");
  });
  $('.form_input').blur(function()
  {
    var id = this.id;
    var val = $(this).val()
    $('#okienko_'+id).removeClass('wszystko_ok cos_zle ladowanie')
    if(id == 'login')
    {
      if(val.length > 4 && val.length < 21)
        okienko(id, val);
      else
      {
        $('#okienko_'+id).addClass('cos_zle');
        $('#okienko_'+id).attr('Title', 'Login musi zawierać od 5 do 20 znaków.');
      }
    }
    else if(id == 'email')
    {
      if(val.length > 6)
        okienko(id, val);
      else
      {
        $('#okienko_'+id).addClass('cos_zle');
        $('#okienko_'+id).attr('Title', 'Zły email');
      }
    }
  });

});
