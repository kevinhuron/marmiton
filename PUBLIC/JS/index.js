/**
 * Created by kevinhuron on 17/01/2016.
 */

$(document).ready(function(){
    /**
     * Voici la méthode dynamique avec du JSON
     */
    var rq = $.ajax({
        url: 'index.php?run=getIndexReceipts',
        method: "GET"

    });
    rq.success(function(receipts)
    {
        receipts = jQuery.parseJSON (receipts);
        var silder = $(".has-slider");
        var silder_item = $("#c_item");
        var r = receipts['receipts'];
        var i = 1;
        var j = 0;
        var img;

        $.each(r, function(key, value)
        {
            img = value['name_img'];
            silder.before('<li data-target="#carousel-example-generic" data-slide-to="'+i+'"></li>')
            silder_item.append('<div class="carousel-item"><img src="PUBLIC/IMG/'+img+'" alt="slide n°'+i+'"><div class="carousel-caption"><h3>'+value['title']+'</h3><p>'+value['type_dish']+'</p></div></div>')
            if (j == 0)
                $(".largeRelated").append('<div data-wow-delay="0.5s" class="wow fadeIn animated" style="background-image:url(PUBLIC/IMG/'+img+')"><a href="#" class="bgOp"> <h2 style="bottom: 170px;width: 100%;background-color: rgba(0,0,0,0.5);">'+value['title']+'<br>'+value['type_dish']+'<br> Difficulté : '+value['difficulty']+'<br> Coût : '+value['cost']+'<br> Portions : '+value['nb_port']+'</h2></a></div>')
            if (j >= 1 && j <= 4)
                $(".card-right").append('<div class="text-center item_carac1" style="background-image:url(PUBLIC/IMG/'+img+')"> <a href="#"> <div class="carac">'+value['title']+' <p>----</p>'+value['type_dish']+' <div>Difficulté : '+value['difficulty']+'</div> <div>Coût : '+value['cost']+'</div> <div>Portions : '+value['nb_port']+'</div></div></a></div>');
            else if (j >= 5 && j <= 8)
                $(".card-bottom").append('<div class="text-center item_carac" style="background-image:url(PUBLIC/IMG/'+img+')"> <a href="#"> <div class="carac">'+value['title']+' <p>----</p>'+value['type_dish']+' <div>Difficulté : '+value['difficulty']+'</div> <div>Coût : '+value['cost']+'</div> <div>Portions : '+value['nb_port']+'</div></div></a></div>');
            i++;
            j++;
        });
    })
});