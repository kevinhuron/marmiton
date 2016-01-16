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

    /**
     * Voici la méthode dynamique avec du JSON
     */
    //// je veux toutes les recettes (via ajax)
    var rq = $.ajax({
        url: 'index.php?run=getAllReceipts',
        method: "GET"

    });
    rq.success(function(receipts)
    {
        /// j'ai reçu mon objet json , je me transforme en tableau ( via (array JQuery.parseJSON(json object) )
        receipts = jQuery.parseJSON (receipts);

        var silder = $(".has-slider");
        var silder_item = $("#c_item");
        var r = receipts['receipts'];
        var i = 1;
        var j = 0;
        var img;

        /// je parcours le tableau de recette via (each)
        $.each(r, function(key, value)
        {
            img = value['name_img'];
            silder.before('<li data-target="#carousel-example-generic" data-slide-to="'+i+'"></li>')
            silder_item.append('<div class="carousel-item"><img src="PUBLIC/IMG/'+img+'" alt="slide n°'+i+'"><div class="carousel-caption"><h3>'+value['title']+'</h3><p>'+value['type_dish']+'</p></div></div>')
            if (j == 0)
                $(".largeRelated").append('<div style="background-image:url(PUBLIC/IMG/'+img+')"><a href="#" class="bgOp"> <h2 style="bottom: 170px;width: 100%;background-color: rgba(0,0,0,0.5);">'+value['title']+'<br>'+value['type_dish']+'<br> Difficulté : '+value['difficulty']+'<br> Coût : '+value['cost']+'<br> Portion : '+value['nb_port']+'</h2></a></div>')
            if (j >= 1 && j <= 4)
                $(".card-right").append('<div class="text-center item_carac1" style="background-image:url(PUBLIC/IMG/'+img+')"> <a href="#"> <div class="carac">'+value['title']+' <p>----</p>'+value['type_dish']+' <div>Difficulté : '+value['difficulty']+'</div> <div>Coût : '+value['cost']+'</div> <div>Portion : '+value['nb_port']+'</div></div></a></div>');
            else if (j >= 5 && j <= 8)
                $(".card-bottom").append('<div class="text-center item_carac" style="background-image:url(PUBLIC/IMG/'+img+')"> <a href="#"> <div class="carac">'+value['title']+' <p>----</p>'+value['type_dish']+' <div>Difficulté : '+value['difficulty']+'</div> <div>Coût : '+value['cost']+'</div> <div>Portion : '+value['nb_port']+'</div></div></a></div>');
            i++;
            j++;
        });
    })
});
