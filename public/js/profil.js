$(document).ready(function() {
  $('.stow').click(function()
  {
  	window.location=$('.stow').attr('href');
  });
  /*$(document).on('click', '.pok_modal', function()
  {
    var id = $(this).attr("data-id");
    $('.modal-body').html("ŁADOWANIE");
    $('.modal-body').load('pokemon.php?ajax&modal&m&id='+id);
    $('#pokemon_modal').modal("show");
  });*/
  $(document).on('click', '.pok_modal', function()
  {
    var id = $(this).attr("data-id");
    $('.modal-body[name="profil_pok"]').text("ŁADOWANIE");

    $('.modal-body[name="profil_pok"]').load(URL+'pokemon/'+id+'/?ajax&modal');
    $('#pokemon_modal').modal("show");
  });
  $('#prawo').on('click', '.um', function()
  {
      var id = this.id;
      $('.panel-body').load(URL+'profil/um/'+id+'/?ajax', function()
      {
          $('#tabelka').load(URL+'lewo');
      });
  });
  $('#prawo').on('click', '.dodaj', function()
  {
      var id = this.id;
      $('#zaproszenie').load(URL+'znajomi/dodaj/'+id+'/?ajax');
  });
});