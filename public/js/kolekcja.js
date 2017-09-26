$(document).ready(function() {

$('.kolekcja').click(function()
{
  var id = this.id;
  window.open(URL+'pokemon_info.php?n='+id, 'pokedex', 'menubar=1,resizable=1,scrollbars=1,width=680,height=750');
});

});
