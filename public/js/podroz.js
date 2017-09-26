$(document).ready(function()
{
    $('#prawo').on('click', '.podroz', function()
    {
        var region = $(this).attr("data-region");
        $('#podroz-body').laduj(URL+'podroz/region/'+region+'?ajax', 1);
    });
});