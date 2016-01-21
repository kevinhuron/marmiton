/**
 * Created by kevinhuron on 12/01/2016.
 */
$(document).ready(function () {

    $("#btn-nav-search").click(function(){
        $("#container_search").toggle("slideDown");
        $("#container_more_filter").slideUp();
        $("#container_plat_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_cout_filter").slideUp();
        $("#container_difficulte_filter").slideUp();
    });
    $("#btn-more-filter").click(function(){
        $("#container_more_filter").toggle("slideDown");
        $("#container_plat_filter").slideUp();
        $("#container_difficulte_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_cout_filter").slideUp();
    });
    $("#btn_plat_filter").click(function(){
        $("#container_plat_filter").toggle("slideDown");
        $("#container_difficulte_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_cout_filter").slideUp();
    });
    $("#btn_ingred_filter").click(function(){
        $("#container_ingred_filter").toggle("slideDown");
        $("#container_plat_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_cout_filter").slideUp();
        $("#container_difficulte_filter").slideUp();
    });
    $("#btn_categ_filter").click(function(){
        $("#container_categ_filter").toggle("slideDown");
        $("#container_plat_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_cout_filter").slideUp();
        $("#container_difficulte_filter").slideUp();
    });
    $("#btn_cout_filter").click(function(){
        $("#container_cout_filter").toggle("slideDown");
        $("#container_plat_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_difficulte_filter").slideUp();
    });
    $("#btn_difficulte_filter").click(function(){
        $("#container_difficulte_filter").toggle("slideDown");
        $("#container_plat_filter").slideUp();
        $("#container_ingred_filter").slideUp();
        $("#container_categ_filter").slideUp();
        $("#container_cout_filter").slideUp();
    });

    /******** LOGIN *******/

    $(".form_login").submit(function(e){
        e.preventDefault();
        var id = $("input[name='id']").val();
        var passwd = $("input[name='pass']").val();
        var rq = $.ajax({
            url: 'index.php?run=makeLogin',
            data : {"id" : id , "passwd" : passwd}
        });
        rq.success(function(result)
        {
            if (result != 0)
            {
                $(".font_logout").text("Bienvenue "+result.toUpperCase());
                $("#content_login").hide()
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    if ($("#modal_login").attr("data-location") == undefined)
                        location.href='index.php?run=indexAction';
                    else
                        location.href='index.php?run='+$("#modal_login").attr("data-location");
                }, 1000);
            }
            else
                $("#modal_login").modal("show");
        });
    });
    /******** END LOGIN **********/
});
