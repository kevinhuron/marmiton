$(document).ready(function(){

    var rq = $.ajax({
        url: 'index.php?run=getUserCo',
        method: "GET"

    });
    rq.success(function(result)
    {
        us = jQuery.parseJSON (result);
        if (us['name'] != null) {
            $(".user").append(" " + us['name']);
            $(".co-do").append('<a class="dropdown-item" href="index.php?run=dashboardShow">Votre dashboard</a>');
            $(".isco").text('Se déconnecter');
            $(".sinscr").hide();
            $(".isco").attr("href", 'index.php?run=logout');
        }
    });
});