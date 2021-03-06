/**
 * Created by kevinhuron on 17/01/2016.
 */

$(document).ready(function(){
    var rq = $.ajax({
        url: 'index.php?run=getAllReceipts',
        method: "GET"

    });
    rq.success(function(all_receipts)
    {
        all_receipts = jQuery.parseJSON (all_receipts);

        var list = $("#card-recettes");
        var all_r = all_receipts['all_receipts'];
        var score = all_receipts['score'];
        var img;

        $.each(all_r, function(key, value)
        {
            img = value['name_img'];
            list.append('<div class="card">' +
                    '<img class="card-img-top" src="PUBLIC/IMG/'+img+'" alt="Card image cap" style="width: 100%;height: 100%;">' +
                    '<div class="card-block text-xs-center">' +
                    '<h4 class="card-title">'+value['title']+'</h4>' +
                    '<span class="label label-danger"><strong>'+value['type_dish']+'</strong></span><br>' +
                    '<span class="label label-warning"><strong><i class="fa fa-signal" data-toggle="tooltip" data-placement="top" title="Difficulté"></i> : '+value['difficulty']+'</strong></span><br>' +
                    '<span class="label label-info"><strong><i class="fa fa-eur" data-toggle="tooltip" data-placement="top" title="Coût"></i> : '+value['cost']+'</strong></span><br>' +
                    '<span class="label label-success"><strong><i class="fa fa-user" data-toggle="tooltip" data-placement="top" title="Portions"></i> : '+value['nb_port']+'</strong></span><br><br>' +
                    '<a href="index.php?run=show_content&cle='+value['id_r']+'" class="btn btn-primary-outline">VOIR</a>' +
                    '</div>'+
                    '</div>');

        });
    });
});