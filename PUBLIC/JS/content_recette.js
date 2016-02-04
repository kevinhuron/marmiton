/**
 * Created by kevinhuron on 19/01/2016.
 */

$(document).ready(function(){
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    var cle = $("#id_r").html();
    if(cle == ""){
        $("#error").html("AUCUNE RECETTE CHOISIT / OU RECETTE INCONNU<br><a href='index.php?run=show_recettes' class='btn btn-success-outline'>Revenir à la liste des recettes</a>");
        $("#content_corps").hide();
    }
    else if (!$.isNumeric(cle)){
        $("#error").html("AUCUNE RECETTE CHOISIT / OU RECETTE INCONNU<br><a href='index.php?run=show_recettes' class='btn btn-success-outline'>Revenir à la liste des recettes</a>");
        $("#content_corps").hide();
    }
    /*else if (){
        $("#error").html("AUCUNE RECETTE CHOISIT / OU RECETTE INCONNU<br><a href='index.php?run=show_recettes' class='btn btn-success-outline'>Revenir à la liste des recettes</a>");
        $("#content_corps").hide();
    }*/
    else {
        var rq = $.ajax({
            url: 'index.php?run=get_content&cle='+cle,
            method: "GET"
        });
        rq.success(function(recettes)
        {
            recettes = jQuery.parseJSON (recettes);

            var recette_content = recettes['list_rec'];
            var ingredient = recettes['list_ingre'];
            var step = recettes['list_step'];
            var score = recettes['list_score'];
            var categ = recettes['list_categ'];

            var img;
            var i = 1;

            $.each(recette_content, function (key, value) {
                img = value['name_img'];
                $("#all_img_r").append('<a class="example-image-link thumbnail" href="PUBLIC/IMG/'+img+'" data-lightbox="example-set" data-title="IMAGE">' +
                    '<img class="example-image img-thumbnail" src="PUBLIC/IMG/'+img+'" alt="img" style="width: 100%; height: 100%"/>' +
                    '</a>');

                $('#titre_r').empty();
                $('#type_dish_r').empty();
                $('#diff_r').empty();
                $('#cost_r').empty();
                $('#tmp_prep_r').empty();
                $('#tmp_cook_r').empty();
                $('#nb_port').empty();

                $('#titre_r').append(value['title']);
                $('#type_dish_r').append(value['type_dish']);
                $('#diff_r').append(value['difficulty']);
                $('#cost_r').append(value['cost']);
                $('#tmp_prep_r').append(value['time_prep']);
                $('#tmp_cook_r').append(value['cook_time']);
                $('#nb_port').append(value['nb_port']);
            });
            $.each(categ, function (key, value) {
                $('#categ_r').append('--> '+value['name_c']+'<br>');
            });
            $.each(ingredient, function (key, value) {
                $('#ingre').append('<i class="fa fa-caret-right"></i> '+value['name_in']+' ('+value['qt']+')<br>');

            });
            $.each(step, function (key, value) {
                $('#step').append('<i class="fa fa-caret-right"></i> '+value['name_step']+'<br>');
            });
            $.each(score, function (key, value) {
                var score = value['score'];
                if(score == 5)
                    $("#star5").attr({checked: "checked"});
                else if(score == 4)
                    $("#star4").attr({checked: "checked"});
                else if(score == 3)
                    $("#star3").attr({checked: "checked"});
                else if(score == 2)
                    $("#star2").attr({checked: "checked"});
                else if(score == 1)
                    $("#star1").attr({checked: "checked"});
            });
        });
        $(".starC").each(function () {
            $(this).bind('click', function () {
                var score = $(this).val();
                $.ajax({
                    url: "index.php?run=newScore",
                    type: "POST",
                    data: {"idr": cle, "score": score},
                    success: function (result) {
                        if(result != 1){
                            $("#errorNote").slideDown().delay(2000).fadeOut('slow', function () {
                                $("#confirmImg1").remove();
                            });
                        } else {
                            $("#confirmNote").slideDown().delay(2000).fadeOut('slow', function () {
                                $("#confirmImg1").remove();
                            });
                        }
                    }
                });
            });
        });
        $("#btn_in_kitchen").click(function(e){
            e.preventDefault();
            $("#modal_login").modal("show");
            window.setTimeout(function() {
                location.href='index.php?run=kitchenMode&idr='+cle;
            }, 4000);
        });
    }
});