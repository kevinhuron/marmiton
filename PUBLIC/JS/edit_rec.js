/**
 * Created by kevinhuron on 26/01/2016.
 */
$(document).ready(function(){
    $('#category').multiselect({});
    $("#category").change(function(e){
        if($(this).val() == "autres")
            $("#OtherChoice").show();
        else
            $("#OtherChoice").hide();
    });

    $("#btn_add_field_ingre").click(function(e){
        e.preventDefault();
        $("#table_ingre").append('<tr><td><fieldset class="form-group"><label>Quantité</label><input type="text" class="form-control inputQte" name="qte[]" placeholder="Quantité (ex : 10L , 20g, 10 cuillères à soupe ...)"><small class="text-muted">Saisissez les quantiés (ex : 10L , 20g ...)</small></fieldset><div class="verifQte"></div></td><td> <fieldset class="form-group"> <label>Ingédients</label> <input type="text" class="form-control inputIngre" name="ingredient[]" placeholder="Ingrédients (ex : lait, beurre...)"> <small class="text-muted">Saisissez les ingrédients (ex : lait, beurre...)</small> </fieldset> <div class="verifIngre"></div></td> </tr>');
    });

    $("#btn-remove-line-ingre").click(function(e) {
        e.preventDefault();
        $("tr").remove("#table_ingre tbody>tr:last");
    });

    $("#btn_add_field_step").click(function(e){
        e.preventDefault();
        $("#table_step").append('<tr> <td> <fieldset class="form-group"> <label>Nom de l\'étape</label> <input type="text" class="form-control inputStep" name="name_step[]" placeholder="Etapes (ex : Mélangez le lait avec les oeufs ...)"> <small class="text-muted">Saisissez les étapes (ex : Mélangez le lait avec les oeufs ...)</small> </fieldset> <div class="verifStep"></div> </td> </tr>');
    });

    $("#btn-remove-line-step").click(function(e) {
        e.preventDefault();
        $("tr").remove("#table_step tbody>tr:last");
    });
    var cook_time;
    var idr = $("#id_r").html();
    var rq = $.ajax({
        url: 'index.php?run=recette_edited&idr='+idr,
        method: "GET"

    });
    rq.success(function(recettes) {
        recettes = jQuery.parseJSON (recettes);

        var recette_content = recettes['list_rec'];
        var ingredient = recettes['list_ingre'];
        var step = recettes['list_step'];
        var score = recettes['list_score'];
        var categ = recettes['list_categ'];

        var img;

        $.each(recette_content, function (key, value) {
            img = value['name_img'];
            cook_time = value['cook_time'];
            $('#the_titre').html(value['title']);
            if (img == null)
            {
                $("#all_img_r").append('<div class="text-danger">Aucune image</div>');
            } else {
                $("#all_img_r").append('<div style="display: flex"><a class="example-image-link thumbnail" href="PUBLIC/IMG/'+img+'" data-lightbox="example-set" data-title="IMAGE">' +
                    '<img class="example-image img-thumbnail" src="PUBLIC/IMG/'+img+'" alt="img" style="width: 100%; height: 100%"/>' +
                    '</a><button name="'+value['id_img']+'" class="btn btn-danger-outline btn_del_img"><i class="fa fa-trash"></i></button></div>');
            }

            $('#titre_r').empty();
            $('#tmp_prep_r').empty();
            $('#tmp_cook_r').empty();
            $('#nb_port').empty();

            $('#titre_r').val(value['title']);
            $('select#type_dish_r').val(value['type_dish']);
            $('#diff_r').val(value['difficulty']);
            $('#cost_r').val(value['cost']);
            $('#tmp_prep_r').val(value['time_prep']);
            $('#tmp_cook_r').val(cook_time);
            $('#nb_port').val(value['nb_port']);
            $('#drink').val(value['drink']);
            $('#textareaNote').val(value['note']);

            if($(".toggle_vege").hasClass('toggle-off') && value['vegetarian'] == 1){
                $(".toggle_vege").addClass('toggle-on');
                $(".toggle_vege").removeClass('toggle-off');
            }

            else if($(".toggle_vege").hasClass('toggle-off') && value['vegetarian'] == 0){
                $(".toggle_vege").addClass('toggle-off');
                $(".toggle_vege").removeClass('toggle-on');
            }

            if($(".toggle_type_cook").hasClass('toggle-off') && cook_time == "Sans cuisson"){
                $(".toggle_type_cook").addClass('toggle-on');
                $(".toggle_type_cook").removeClass('toggle-off');
                $("#tmp_cook_r").slideUp();
                $("#tmp_cook_r").val("Sans cuisson");
            }

            else if($(".toggle_type_cook").hasClass('toggle-off') && cook_time != "Sans cuisson"){
                $(".toggle_type_cook").addClass('toggle-off');
                $(".toggle_type_cook").removeClass('toggle-on');
                $("#tmp_cook_r").slideDown();
                $("#tmp_cook_r").val(cook_time);
            }
            /********************************************************
             * BTN DELETE IMG
             *******************************************************/
            $(".btn_del_img").click(function (e) {
                e.preventDefault();
                var id_img = $(this).attr('name');
                var name_img = img;
                var rq = $.ajax({
                    url: 'index.php?run=del_img_from_rec&idr='+idr+'&id_img='+id_img+'&filename='+name_img,
                    method: "GET"
                });
                rq.success(function(result) {
                    if (result != 1) {
                        $(".font_logout").text(result);
                        $("#modal_login").modal("show");
                    }
                    else {
                        $(".font_logout").text("Suppression OK ! ");
                        $("#content_login").hide();
                        $("#spinnerl").show();
                        $("#modal_login").modal("show");
                        window.setTimeout(function() {
                            location.href='index.php?run=edit_rec_page&idr='+idr;
                        }, 1000);
                    }
                });
            });
            /********************************************************
             * END BTN DELETE IMG
             *******************************************************/
        });
        $.each(categ, function (key, value) {
            var categ = value['name_c'];
            $('#categ_r').append('<div style="display: flex;"><input type="text" class="form-control" value="'+categ+'" readonly/> <button name="'+value['id_c']+'" class="btn btn-danger-outline btn_del_categ"><i class="fa fa-trash"></i></button></div><br>');
            /********************************************************
             * BTN DELETE CATEG
             *******************************************************/
            $(".btn_del_categ").click(function (e) {
                e.preventDefault();
                var id_c = $(this).attr('name');
                var rq = $.ajax({
                    url: 'index.php?run=del_categ_from_rec&idr='+idr+'&id_c='+id_c,
                    method: "GET"
                });
                rq.success(function(result) {
                    if (result != 1) {
                        $(".font_logout").text(result);
                        $("#modal_login").modal("show");
                    }
                    else {
                        $(".font_logout").text("Suppression OK ! ");
                        $("#content_login").hide();
                        $("#spinnerl").show();
                        $("#modal_login").modal("show");
                        window.setTimeout(function() {
                            location.href='index.php?run=edit_rec_page&idr='+idr;
                        }, 1000);
                    }
                });
            });
            /********************************************************
             * END BTN DELETE CATEG
             *******************************************************/
        });
        $.each(ingredient, function (key, value) {
            var ingre = value['name_in'];
            var qte = value['qt'];
            if (ingre == null && qte == null){
                $('#ingre').append('Aucun ingédients');
            } else {
                $('#ingre').append('<div style="display: flex;"><input type="text" class="form-control" value="'+ingre+' '+ qte+'" readonly/> <button name="'+value['id_in']+'" class="btn btn-danger-outline btn_del_ingre"><i class="fa fa-trash"></i></button></div><br>');
            }
            /********************************************************
             * BTN DELETE INGRE
             *******************************************************/
            $(".btn_del_ingre").click(function (e) {
                e.preventDefault();
                var id_in = $(this).attr('name');
                var rq = $.ajax({
                    url: 'index.php?run=del_ingre&idr='+idr+'&id_in='+id_in,
                    method: "GET"
                });
                rq.success(function(result) {
                    if (result != 1) {
                        $(".font_logout").text(result);
                        $("#modal_login").modal("show");
                    }
                    else {
                        $(".font_logout").text("Suppression OK ! ");
                        $("#content_login").hide();
                        $("#spinnerl").show();
                        $("#modal_login").modal("show");
                        window.setTimeout(function() {
                            location.href='index.php?run=edit_rec_page&idr='+idr;
                        }, 1000);
                    }
                });
            });
            /********************************************************
             * END BTN DELETE INGRE
             *******************************************************/
        });
        $.each(step, function (key, value) {
            var step = value['name_step'];
            if (step == null){
                $('#step').append('Aucune étapes');
            } else {
                $('#step').append('<div style="display: flex;"><input type="text" class="form-control" value="'+step+'" readonly/> <button name="'+value['id_step']+'" class="btn btn-danger-outline btn_del_step"><i class="fa fa-trash"></i></button></div><br>');
            }
            /********************************************************
             * BTN DELETE STEP
             *******************************************************/
            $(".btn_del_step").click(function (e) {
                e.preventDefault();
                var id_step = $(this).attr('name');
                var rq = $.ajax({
                    url: 'index.php?run=del_step&idr='+idr+'&id_step='+id_step,
                    method: "GET"
                });
                rq.success(function(result) {
                    if (result != 1) {
                        $(".font_logout").text(result);
                        $("#modal_login").modal("show");
                    }
                    else {
                        $(".font_logout").text("Suppression OK ! ");
                        $("#content_login").hide();
                        $("#spinnerl").show();
                        $("#modal_login").modal("show");
                        window.setTimeout(function() {
                            location.href='index.php?run=edit_rec_page&idr='+idr;
                        }, 1000);
                    }
                });
            });
            /********************************************************
             * END BTN DELETE STEP
             *******************************************************/
        });
    });
    /********************************************************
     * TOGGLE
     *******************************************************/
    $('.toggle').click(function(e) {
        var toggle = this;
        e.preventDefault();
        $(toggle).toggleClass('toggle-on')
            .toggleClass('toggle-off')
            .addClass('toggle-moving');
        setTimeout(function() {
            $(toggle).removeClass('toggle-moving');
        }, 200)
    });
    $(".toggle_vege").click(function(){
        if($(".toggle_vege").hasClass("toggle-on")){
            var value = 1;
            var rq = $.ajax({
                url: 'index.php?run=update_vege&idr='+idr+'&value='+value,
                method: "GET"
            });
            rq.success(function(result) {
                if (result != 1) {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else {
                    $(".font_logout").text("Modification OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                }
            });
        }
        else if($(".toggle_vege").hasClass("toggle-off")){
            var value = 0;
            var rq = $.ajax({
                url: 'index.php?run=update_vege&idr='+idr+'&value='+value,
                method: "GET"
            });
            rq.success(function(result) {
                if (result != 1) {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else {
                    $(".font_logout").text("Modification OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                }
            });
        }
    });
    $(".toggle_type_cook").click(function () {
        if($(".toggle_type_cook").hasClass("toggle-on")) {
            $("#tmp_cook_r").slideUp();
            $("#tmp_cook_r").val("Sans cuisson");
            var value = $("#tmp_cook_r").val();
            var rq = $.ajax({
                url: 'index.php?run=update_tmp_cook&idr='+idr+'&value='+value,
                method: "GET"
            });
            rq.success(function(result) {
                if (result != 1) {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else {
                    $(".font_logout").text("Modification OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                }
            });
        }
        else if($(".toggle_type_cook").hasClass("toggle-off")) {
            $("#tmp_cook_r").val(cook_time);
            $("#tmp_cook_r").slideDown();
        }
    });
    /********************************************************
     * END TOGGLE
     *******************************************************/

    /********************************************************
     * CHANGE ON INPUT RECETTE
     *******************************************************/
    $("#titre_r").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_title&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    $("#type_dish_r").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_type_dish&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    $("#diff_r").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_diff&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    $("#cost_r").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_cost&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    $("#tmp_prep_r").change(function () {
        if(!$.isNumeric($(this).val()))
        {
            $("#tmp_prep_r").css({border: '1px solid #F70021'});
            $('#verifIsNum').html("<p class='text-danger'>Vous devez saisir uniquement des chiffres !</p>");
            $("#tmp_prep_r").on('change', function () {
                if($.isNumeric($("#tmp_prep_r").val())) {
                    $("#tmp_prep_r").css({border: '1px solid #00E14B'});
                    $('#verifIsNum').html("");
                }
            });
        } else{
            var value = $(this).val();
            var rq = $.ajax({
                url: 'index.php?run=update_tmp_prep&idr='+idr+'&value='+value,
                method: "GET"
            });
            rq.success(function(result) {
                if (result != 1) {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else {
                    $(".font_logout").text("Modification OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                }
            });
        }
    });
    $("#tmp_cook_r").change(function () {
        if(!$.isNumeric($(this).val()))
        {
            $("#tmp_cook_r").css({border: '1px solid #F70021'});
            $('#verifIsNum').html("<p class='text-danger'>Vous devez saisir uniquement des chiffres !</p>");
            $("#tmp_cook_r").on('change', function () {
                if($.isNumeric($("#tmp_cook_r").val())) {
                    $("#tmp_cook_r").css({border: '1px solid #00E14B'});
                    $('#verifIsNum').html("");
                }
            });
        } else{
            var value = $(this).val();
            var rq = $.ajax({
                url: 'index.php?run=update_tmp_cook&idr='+idr+'&value='+value,
                method: "GET"
            });
            rq.success(function(result) {
                if (result != 1) {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else {
                    $(".font_logout").text("Modification OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                }
            });
        }
    });
    $("#nb_port").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_nb_port&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });$('#drink').val(value['drink']);
        $('#textareaNote').val(value['note']);
    });
    $("#drink").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_drink&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    $("#textareaNote").change(function () {
        var value = $(this).val();
        var rq = $.ajax({
            url: 'index.php?run=update_note&idr='+idr+'&value='+value,
            method: "GET"
        });
        rq.success(function(result) {
            if (result != 1) {
                $(".font_logout").text(result);
                $("#modal_login").modal("show");
            }
            else {
                $(".font_logout").text("Modification OK ! ");
                $("#content_login").hide();
                $("#spinnerl").show();
                $("#modal_login").modal("show");
                window.setTimeout(function() {
                    location.href='index.php?run=edit_rec_page&idr='+idr;
                }, 1000);
            }
        });
    });
    /********************************************************
     * END CHANGE ON INPUT RECETTE
     *******************************************************/

    /********************************************************
     * FORM ADD NEW INGRE IN UPDATE PAGE
     *******************************************************/
    $("#formNewIngre").submit(function(e){
        e.preventDefault();
        var qte_i = $('input[name^=qte]').map(function(){return $(this).val();}).get();
        var ingre = $('input[name^=ingredient]').map(function(){return $(this).val();}).get();
        var title_r = $("#title_r").html();

        if($(".inputQte").val() == "" || $(".inputIngre").val() == "")
        {
            if ($(".inputQte").val() == "") {
                $(".inputQte").css({border: '1px solid #F70021'});
                $('.verifQte').html("<p class='text-danger'>La quantité est nécessaire !</p>");
                $(".inputQte").on('change', function () {
                    $('.verifQte').html("");
                    $(".inputQte").css({border: '1px solid #00E14B'});
                });
            }
            if ($(".inputIngre").val() == "") {
                $(".inputIngre").css({border: '1px solid #F70021'});
                $('.verifIngre').html("<p class='text-danger'>L'ingrédient est nécessaire !</p>");
                $(".inputIngre").on('change', function () {
                    $('.verifIngre').html("");
                    $(".inputIngre").css({border: '1px solid #00E14B'});
                });
            }
        }
        else{
            var rq = $.ajax({
                url: 'index.php?run=insertIngre',
                method: "POST",
                data: {ingred : ingre, qte : qte_i, idr : idr}
            });
            rq.success(function (result) {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else
                {
                    $(".font_logout").text("Ajout OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                    $(window).off('beforeunload');
                }
            });
        }
    });
    /********************************************************
     * END FORM ADD NEW INGRE IN UPDATE PAGE
     *******************************************************/

    /********************************************************
     * FORM ADD NEW STEP IN UPDATE PAGE
     *******************************************************/
    $("#formNewStep").submit(function(e){
        e.preventDefault();
        var step = $('input[name^=name_step]').map(function(){return $(this).val();}).get();

        if($('.inputStep').val() == "") {
            $(".inputStep").css({border: '1px solid #F70021'});
            $('.verifStep').html("<p class='text-danger'>Le nom de l'étape est nécessaire !</p>");
            $(".inputStep").on('change', function () {
                $('.verifStep').html("");
                $(".inputStep").css({border: '1px solid #00E14B'});
            });
        }
        else{
            var rq = $.ajax({
                url: 'index.php?run=insertStep',
                method: "POST",
                data: {step : step, idr : idr}
            });
            rq.success(function (result) {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else
                {
                    $(".font_logout").text("Ajout OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                    $(window).off('beforeunload');
                }
            });
        }
    });
    /********************************************************
     * END FORM ADD NEW STEP IN UPDATE PAGE
     *******************************************************/

    /********************************************************
     * FORM ADD NEW CATEG IN UPDATE PAGE
     *******************************************************/
    $("#formAddCateg").submit(function(e){
        e.preventDefault();
        var categ = $("#category").val();
        var otherChoice = $("#inputOtherC").val();
        if($("#category").val() == "autres")
            categ = otherChoice;

        if($('#category').val() == null) {
            $("#category").css({border: '1px solid #F70021'});
            $('#verifCateg').html("<p class='text-danger'>La catégorie est nécessaire !</p>");
            $("#category").on('change', function () {
                $('#verifCateg').html("");
                $("#category").css({border: '1px solid #00E14B'});
            });
        }
        else{
            var rq = $.ajax({
                url: 'index.php?run=add_categ_from_update&categ='+categ,
                method: "POST"
            });
            rq.success(function (result) {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else
                {
                    $(".font_logout").text("Ajout OK ! ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=edit_rec_page&idr='+idr;
                    }, 1000);
                    $(window).off('beforeunload');
                }
            });
        }
    });
    /********************************************************
     * END FORM ADD NEW CATEG IN UPDATE PAGE
     *******************************************************/

    /********************************************************
     * BTN ADD IMG FROM EDIT PAGE
     *******************************************************/
    $("#btn_add_img_from_edit_page").click(function(e){
        e.preventDefault();
    });
    /********************************************************
     * END BTN ADD IMG FROM EDIT PAGE
     *******************************************************/
});