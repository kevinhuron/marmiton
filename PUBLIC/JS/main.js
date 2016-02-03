/**
 * Created by kevinhuron on 12/01/2016.
 */
$(document).ready(function () {

    var rq = $.ajax({
        url: 'index.php?run=get_distinct_ingre_categ',
        method: "GET"

    });
    rq.success(function(receipts)
    {
        receipts = jQuery.parseJSON (receipts);

        var ingre = receipts['distinct_ingre'];
        var categ = receipts['categ'];
        var i = 1;
        var j = 1;

        $.each(ingre, function(key, value)
        {
            $("#ingre_checkbox").append('<label class="c-input c-checkbox checkbox-inline">'+
            '<input type="checkbox" id="ingreCheckbox'+i+'" value="'+value['name_in']+'" name="ingre">'+
            '<span class="c-indicator"></span>'+value['name_in']+'</label>');
            i++;
        });
        $.each(categ, function(key, value)
        {
            $("#categ_checkbox").append('<label class="c-input c-checkbox checkbox-inline">'+
                '<input type="checkbox" id="categCheckbox'+j+'" value="'+value['name_c']+'" name="categ">'+
                '<span class="c-indicator"></span>'+value['name_c']+'</label>');
            j++;
        });
    });

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
                $("#content_login").hide();
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

    /******** SEARCH *******/
    $("#btn-valid-search").click(function(e){
        e.preventDefault();
        var dish = [];
        var ingre = [];
        var categ = [];
        var cost = [];
        var diff = [];
        var search = $("#inputSearch").val();
        $("input[name='dish']").each(function () {
            if($(this).is(':checked')){
                dish.push($(this).val());
            }
        });
        $("input[name='ingre']").each(function () {
            if($(this).is(':checked')){
                ingre.push($(this).val());
            }
        });
        $("input[name='categ']").each(function () {
            if($(this).is(':checked')){
                categ.push($(this).val());
            }
        });
        $("input[name='cost']").each(function () {
            if($(this).is(':checked')){
                cost.push($(this).val());
            }
        });
        $("input[name='diff']").each(function () {
            if($(this).is(':checked')){
                diff.push($(this).val());
            }
        });
        console.log(search);
        console.log(dish);
        console.log(ingre);
        console.log(categ);
        console.log(cost);
        console.log(diff);
        location.href = "index.php?run=search&searchText=search&dish=dish&ingre=ingre&categ=categ&cost=cost&diff=diff";
    });
    /******** END SEARCH *******/
});
