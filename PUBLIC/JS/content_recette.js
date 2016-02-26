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
        /**
         *  COMMENTAIRES
         */
        $.ajax({
            url: 'index.php?run=get_comment&idr='+cle,
            method: "PSOT",
            success:function(recettes) {
                recettes = jQuery.parseJSON(recettes);

                var recette_content = recettes['comment'];

                $.each(recette_content, function (key, value) {
                    if (value['name'] != '' && value['comment'] != ''){
                        $("#all_comment").append('<hr style="width: 70%;"> ' +
                            '<div style="padding-left: 70px;padding-right: 70px;"> ' +
                            '<div class="marginTop20" style="padding: 10px">' +
                            '<i class="fa fa-user"></i> <strong>Nom : </strong>'+ value['name'] +'<br/>' +
                            '<i class="fa fa-star"></i> <strong>Note :</strong> '+ value['value'] +' <br/>' +
                            '<i class="fa fa-comments"></i> <strong>Commentaire :</strong> '+ value['comment'] +'<br/>' +
                            '</div>' +
                            '<hr style="width: 70%;">' +
                            '</div>');
                    }
                });
            }
        });
        /**
         *  END COMMENTAIRES
         */
        $(".starC").each(function () {
            $(this).bind('click', function () {
                var score = $(this).val();
                $('#modal_comment').modal("show");
                $("#btn_send_comment").click(function(e){
                    e.preventDefault();
                    console.log('tesgrezefd');
                    var nom = $("#name_or_pseudo").val();
                    var commentaire = $("#comment").val();
                    console.log(nom);
                    console.log(commentaire);
                    if (nom == '' || commentaire == '') {
                        if (nom == '')
                            $('#error_name').show;
                        else
                            $('#error_name').hide;
                        if (commentaire == '')
                            $('#error_commentaire').show;
                        else
                            $('#error_commentaire').hide;
                    } else {
                        $.ajax({
                            url: "index.php?run=newScore",
                            type: "POST",
                            data: {"idr": cle, "score": score, "name": nom, 'comment': commentaire},
                            success: function (result) {
                                if(result != 1){
                                    $("#errorNote").slideDown().delay(1000).fadeOut('slow', function () {
                                        $("#confirmImg1").remove();
                                    });
                                    $('#error_error').show();
                                } else {
                                    $('#modal_comment').modal("hide");
                                    $("#confirmNote").slideDown().delay(1000).fadeOut('slow', function () {
                                        $("#confirmImg1").remove();
                                        location.reload();
                                    });
                                }
                            }
                        });
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