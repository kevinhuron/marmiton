/**
 * Created by kevinhuron on 17/01/2016.
 */

$(document).ready(function(){
    /**
     * Voici la méthode dynamique avec du JSON
     */
    var rq = $.ajax({
        url: 'index.php?run=getAllReceipts',
        method: "GET"

    });
    rq.success(function(all_receipts)
    {
        all_receipts = jQuery.parseJSON (all_receipts);

        var list = $("#card-recettes");
        var all_r = all_receipts['all_receipts'];
        var img;

        $.each(all_r, function(key, value)
{
            img = value['name_img'];
            list.append('<div class="card">' +
                    '<img class="card-img-top" src="PUBLIC/IMG/'+img+'" alt="Card image cap" style="width: 100%;height: 100%;">' +
                    '<div class="card-block text-xs-center">' +
                    '<h4 class="card-title">'+value['title']+'</h4>' +
                    '<p class="card-text"><strong>'+value['type_dish']+'</strong><br>' +
                    '<p class="card-text"><strong>Difficulté : '+value['difficulty']+'</strong></p>' +
                    '<p class="card-text"><strong>Coût : '+value['cost']+'</strong></p>' +
                    '<p class="card-text"><strong>Portions : '+value['nb_port']+'</strong></p>' +
                    '<a href="index.php?run=show_content&cle='+value['id_r']+'" class="btn btn-primary">VOIR !</a>' +
                    '</div>'+
                    '</div>');
        });
    });
});