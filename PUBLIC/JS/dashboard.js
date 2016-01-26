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
            if(img == null) {
                img = "Aucune images disponibles";
                list.append('<li class="list-group-item" style="padding-bottom: 30px">' +
                    '<div style="width: 130px;" class="pull-lg-right">' +
                    '<h4><div class="label label-danger">Aucune images</div></h4></div>' +
                    '<p class="card-text"><strong>'+value['title']+'</strong><br><span class="label label-danger"> <strong>'+value['type_dish']+'</strong></span><br>' +
                    '<span class="label label-warning">Difficulté : '+value['difficulty']+'</span><br>' +
                    '<span class="label label-info">Coût : '+value['cost']+'</span><br>' +
                    '<span class="label label-success">Portions : '+value['nb_port']+'</span><br><br>' +
                    '<a name="'+value['id_r']+'" class="btn btn-danger-outline btn_delete_rec" style="margin-right: 15px; float: right">Supprimer</a>' +
                    '<a name="'+value['id_r']+'" class="btn btn-warning-outline">Modifier</a>' +
                    '</p></li>');
            }
            else {
                list.append('<li class="list-group-item" style="padding-bottom: 30px">' +
                    '<div style="width: 130px;" class="pull-lg-right">' +
                    '<div class="theImg"><img src="PUBLIC/IMG/'+img+'" alt="img" style="width: 100%;height: 100%;"></div></div>' +
                    '<p class="card-text"><strong>'+value['title']+'</strong><br><span class="label label-danger"> <strong>'+value['type_dish']+'</strong></span><br>' +
                    '<span class="label label-warning">Difficulté : '+value['difficulty']+'</span><br>' +
                    '<span class="label label-info">Coût : '+value['cost']+'</span><br>' +
                    '<span class="label label-success">Portions : '+value['nb_port']+'</span><br><br>' +
                    '<a name="'+value['id_r']+'" class="btn btn-danger-outline btn_delete_rec" style="margin-right: 15px; float: right">Supprimer</a>' +
                    '<a name="'+value['id_r']+'" class="btn btn-warning-outline">Modifier</a>' +
                    '</p></li>');
            }
            count++;
        });
        $(".count_number").text(count);

        $(".btn_delete_rec").click(function(){
            var id_r = $(this).attr('name');
            var rq = $.ajax({
                url: 'index.php?run=delRecipientsUser&idr='+id_r,
                method: "GET"

            });
            rq.success(function(result) {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }

                else
                {
                    $(".font_logout").text("Suppression OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=dashboardShow';
                    }, 1500);
                }
            });
        });
    });
});
