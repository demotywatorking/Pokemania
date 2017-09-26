$(document).ready(function()
{
  $('#prawo').on('click', '.losuj', function(event)
  {
    event.preventDefault();
    var co = $(this).attr('href');
    $('#wynik').load(URL + co  + '/?ajax', function()
    {
        $('#loteria_body').load(URL+'loteria/?ajax');
        $('#tabelka').load(URL+'lewo');
    });
    history.replaceState(null, null, URL+'loteria/losuj');
  });

});
