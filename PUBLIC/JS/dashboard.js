$(document).ready(function() {
    var rq = $.ajax({
        url: 'index.php?run=getRecipientsUser',
        method: "GET"

    });
    rq.success(function(all_receipts)
    {
        console.log(all_receipts);
        all_receipts = jQuery.parseJSON (all_receipts);

        var list = $("#recipient_list");
        var all_r = all_receipts['all_receipts'];
        var img;
        var count = 0;

        $.each(all_r, function(key, value)
        {
            img = value['name_img'];
            list.append('<li class="list-group-item" style="padding-bottom: 30px">' +
                '<div style="width: 130px;" class="pull-lg-right"><img src="PUBLIC/IMG/'+img+'" alt="img" style="width: 100%;height: 100%;"></div>' +
                '<p class="card-text"><strong>'+value['title']+'</strong><br><p class="card-text"> <strong>'+value['type_dish']+'</strong><br>' +
                'Difficulté : '+value['difficulty']+'<br>' +
                'Coût : '+value['cost']+'<br>' +
                'Portions : '+value['nb_port']+'<br>' +
                '<a href="index.php?run=show_content&cle='+value['id_r']+'" class="btn btn-danger" style="margin-right: 15px">Supprimer</a>' +
                '<a href="index.php?run=show_content&cle='+value['id_r']+'" class="btn btn-warning">Modifier</a>' +
                '</p></li>');
            count++;
        });
        $(".count_number").text(count);
    });
});
