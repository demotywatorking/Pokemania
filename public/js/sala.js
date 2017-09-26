$(document).ready(function()
{
    var lad = '<div class="ladowanie"><img src="/img/loader.gif" /> TRWA ŁADOWANIE</div>';
    function trenuj(id, ile, id_poka)
    {
        $('#prawo').load(URL+'sala/trening/'+id_poka+'/'+id+'/'+ile+'/?ajax', function()
        {
            $("#tabelka").load(URL+'lewo');
        });
    }
    $('#prawo').on('click','.trenuj',function()
    {
        var id = this.id;
        var trening = id.slice(0, id.indexOf('_'));
        var id_poka = id.slice((id.indexOf('_') +1), id.length);
        var ile = $('#ile_'+id).val();
        trenuj(trening, ile, id_poka);
    });
    $('#prawo').on('click','.trenuj_1',function()
    {
        var id = this.id;
        var trening = id.slice(0, id.indexOf('_'));
        var id_poka = id.slice((id.indexOf('_') +1), id.length);
        trenuj(trening, 1, id_poka);
    });
    $('#prawo').on('keydown','.ile',function( event)
    {
        if ( event.which == 13 )
        {
            var id = this.id.substr( 4, 1 );
            var id_poka = this.id.substr(6);
            var ile = $(this).val();
            trenuj(id, ile, id_poka);
        }
    });
    $('#prawo').on('click', '.atak', function()
    {
        if(!($(this).hasClass('disabled')))
        {
            var id_ataku = this.id;
            var id_poka = $(this).attr('data-id-poka');
            var nr = $('input[name=zmien_atak_'+id_poka+']:checked').val();
            if(nr)
            {
                $('#prawo').load(URL+'sala/atak/'+id_poka+'/'+id_ataku+'/'+nr+'/?ajax', function()
                {
                    $("#tabelka").load(URL+'lewo');
                });
            }
            else alert('Najpierw zaznacz, zamiast którego ataku nauczyć nowego!');
        }
        else alert('Nie możesz nauczyć pokemona tego ataku!');
    });
  
  /*$('#sala_treningowa').on('click','.naucz',function( event)
  {
      var id = this.id;
      var id_p = $('#id_poka').text();
      var zm = $('#zmien_atak_'+id).val();
      $('#sala_treningowa').html(lad).load('sala_rysuj.php?id_ataku='+id+'&zmien_atak='+zm+'&id='+id_p);
  });*/
});
