$(document).ready(function(){
    $('#prawo').on('click', 'a', function (e){
        e.preventDefault();
        var href = $(this).attr('href');
       $('.panel-body').load(href+'/?ajax');
    });
});