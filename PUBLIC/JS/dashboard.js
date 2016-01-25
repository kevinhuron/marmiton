$(document).ready(function() {
    var rq = $.ajax({
        url: 'index.php?run=getRecipientsUser',
        method: "GET"

    });
    rq.success(function(all_receipts)
    {
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
                '<p class="card-text"><strong>'+value['title']+'</strong><br><span class="label label-danger"> <strong>'+value['type_dish']+'</strong></span><br>' +
                '<span class="label label-warning">Difficulté : '+value['difficulty']+'</span><br>' +
                '<span class="label label-info">Coût : '+value['cost']+'</span><br>' +
                '<span class="label label-success">Portions : '+value['nb_port']+'</span><br><br>' +
                '<a name="'+value['id_r']+'" class="btn btn-danger btn_delete_rec" style="margin-right: 15px; float: right">Supprimer</a>' +
                '<a name="'+value['id_r']+'" class="btn btn-warning">Modifier</a>' +
                '</p></li>');
            count++;
        });
        $(".count_number").text(count);
    });

    $(".btn_delete_rec").click(function(){
        console.log("zefzfze");
        alert("dcddezez");
    });
});
