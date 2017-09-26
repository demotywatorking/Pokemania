$(document).ready(function() {
  var godziny = $('#godziny').text();
  var minuty = $('#minuty').text();
  var sekundy = $('#sekundy').text();
  var ab = "";
  function aktualizacja()
  {
    sekundy++;
    if(sekundy >= 60)
    {
      minuty++;
      sekundy = sekundy - 60;
    }
    if(minuty >= 60)
    {
      godziny++;
      minuty = minuty - 60;
    }
    if(godziny < 10 )
    {
      ab = "0" + godziny;
      $('#godziny').text(ab);
    }
    else $('#godziny').text(godziny);
    if(minuty < 10 )
    {
      ab = "0" + minuty;
      $('#minuty').text(ab);
    }
    else $('#minuty').text(minuty);
    if(sekundy < 10 )
    {
      ab = "0" + sekundy;
      $('#sekundy').text(ab);
    }
    else $('#sekundy').text(sekundy);

    }
    aktualizacja();
    setInterval(aktualizacja, 1000);


});
