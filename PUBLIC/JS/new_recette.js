/**
 * Created by kevinhuron on 18/01/2016.
 */

$(document).ready(function(){

    $('#category').multiselect({
    });


    if($("#title_r").html() == "")
        location.href = "index.php?run=new_recette";
   $(".form_title_recette").submit(function(e){
       e.preventDefault();
       var new_title = $("#InputTitle").val();
       var titles = new_title.split(" ");
       console.log(titles);
       // A AMELIORER ! GERER LE TABLEAU DE MOT SAISIE DANS LE CHAMPS INPUT !
       var rq = $.ajax({
           url: 'index.php?run=getTitleRecette&value='+new_title,
           method: "GET"
       });
       rq.success(function(all_title_recette)
       {
           $("#result_verif").slideDown();
           all_title_recette = jQuery.parseJSON (all_title_recette);

            var all_title = all_title_recette['all_title_recette'];
            var img;
           $('.list-group').empty();
            if(new_title == "")
                $('.list-group').append('<h1><div class="text-danger">Veuillez saisir un titre</div></h1>');
           else if(all_title === undefined)
                $('.list-group').append('<h1><div class="text-danger">Aucune recette est similaire à vos mots-clés</div></h1>');
           else {
                var i = 0;
                $('.list-group').empty();
                $.each(all_title, function (key, value) {
                    img = value['name_img'];

                    $('.list-group').append('<li class="list-group-item" style="padding-bottom: 30px">' +
                        '<div style="width: 200px;" class="pull-lg-right">' +
                        '<img src="PUBLIC/IMG/' + img + '" alt="img" style="width: 100%;height: 100%;" />' +
                        '</div>' +
                        '<h4 class="card-title">' + value['title'] + '</h4>' +
                        '<p class="card-text"><strong>' + value['type_dish'] + '</strong><br>' +
                        'Difficulté : ' + value['difficulty'] + '<br>' +
                        'Coût : ' + value['cost'] + '<br>' +
                        'Portions : ' + value['nb_port'] + '</p>' +
                        '</li>');
                    i++;
                });
                $(".text-info").html(i);
            }
       });
   });

    $("#btn_val_title").click(function(e){
        e.preventDefault();
        var title = $("#InputTitle").val();
        document.location.href='index.php?run=create_recette&value='+title;
    });

    $("#category").change(function(e){
        if($(this).val() == "autres")
            $("#OtherChoice").show();
        else
            $("#OtherChoice").hide();
    });
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

    $(".toggle_type_cook").click(function () {
        if($(".toggle_type_cook").hasClass("toggle-on"))
        {
            $("#divTempCook").slideUp();
            $("#inputTmpCook").val("0");
        }
        else if($(".toggle_type_cook").hasClass("toggle-off"))
        {
            $("#inputTmpCook").val("");
            $("#divTempCook").slideDown();
        }
    });

    /************************************************************************
     * FORM ADD NEW RECETTE
     ************************************************************************/
    $("#formNewRecette").submit(function(e){
        e.preventDefault();
        var title = $("#title_r").html();
        var dish_type = $("#type_dish").val();
        var vegetarian;
        var categ = $("#category").val();
        var otherChoice = $("#inputOtherC").val();
        var difficult = $("#difficulty").val();
        var theCost = $("#cost").val();
        var tmp_prep = $("#inputTmpPrep").val();
        var tmp_cook = $("#inputTmpCook").val();
        var type_cook;
        var drink = $("#inputDrink").val();
        var note = $("#textareaNote").val();
        var user = $("#idu").html();

        if($(".toggle_vege").hasClass("toggle-on"))
            vegetarian = 1;
        else if($(".toggle_vege").hasClass("toggle-off"))
            vegetarian = 0;
        var nb_portion = $("#inputNbPort").val();

        if($(".toggle_type_cook").hasClass("toggle-on"))
            type_cook = 1;
        else if($(".toggle_type_cook").hasClass("toggle-off"))
            type_cook = 0;


        if($("#type_dish").val() == null || $("#category").val() == null || $("#difficulty").val() == null ||
            $("#cost").val() == null || $("#inputTmpPrep").val() == "" || $("#inputTmpCook").val() == "" ||
            $("#inputNbPort").val() == "") {
            if ($("#type_dish").val() == null) {
                $("#type_dish").css({border: '1px solid #F70021'});
                $('#verifTypePlat').html("<p class='text-danger'>Le type de plat est nécessaire !</p>");
                $("#type_dish").on('change', function () {
                    $('#verifTypePlat').html("");
                    $("#type_dish").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#category").val() == null) {
                $("#category").css({border: '1px solid #F70021'});
                $('#verifCateg').html("<p class='text-danger'>La catégorie est nécessaire !</p>");
                $("#category").on('change', function () {
                    $('#verifCateg').html("");
                    $("#category").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#difficulty").val() == null) {
                $("#difficulty").css({border: '1px solid #F70021'});
                $('#verifDiff').html("<p class='text-danger'>La difficulté est nécessaire !</p>");
                $("#difficulty").on('change', function () {
                    $('#verifDiff').html("");
                    $("#difficulty").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#cost").val() == null) {
                $("#cost").css({border: '1px solid #F70021'});
                $('#verifCost').html("<p class='text-danger'>Le prix est nécessaire !</p>");
                $("#cost").on('change', function () {
                    $('#verifCost').html("");
                    $("#cost").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#inputTmpPrep").val() == "") {
                $("#inputTmpPrep").css({border: '1px solid #F70021'});
                $('#verifTmpPrep').html("<p class='text-danger'>Le temps de préparation est nécessaire !</p>");
                $("#inputTmpPrep").on('change', function () {
                    $('#verifTmpPrep').html("");
                    $("#inputTmpPrep").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#inputTmpCook").val() == "") {
                $("#inputTmpCook").css({border: '1px solid #F70021'});
                $('#verifTmpCook').html("<p class='text-danger'>Le temps de cuisson est nécessaire !</p>");
                $("#inputTmpCook").on('change', function () {
                    $('#verifTmpCook').html("");
                    $("#inputTmpCook").css({border: '1px solid #00E14B'});
                });
            }
            if ($("#inputNbPort").val() == "") {
                $("#inputNbPort").css({border: '1px solid #F70021'});
                $('#verifNbPort').html("<p class='text-danger'>Le nombre de portion est nécessaire !</p>");
                $("#inputNbPort").on('change', function () {
                    $('#verifNbPort').html("");
                    $("#inputNbPort").css({border: '1px solid #00E14B'});
                });
            }
        }
        else if($("#category").val() == "autres" && $("#inputOtherC").val() == "")
        {
            $("#inputOtherC").css({border: '1px solid #F70021'});
            $('#verifOtherC').html("<p class='text-danger'>La catégorie est nécessaire !</p>");
            $("#inputOtherC").on('change', function () {
                $('#verifOtherC').html("");
                $("#inputOtherC").css({border: '1px solid #00E14B'});
            });
        }
        else if ((!$.isNumeric($("#inputTmpCook").val()) && !$.isNumeric($("#inputTmpPrep").val())) ||
            (!$.isNumeric($("#inputTmpCook").val()) || !$.isNumeric($("#inputTmpPrep").val()))) {
            $("#inputTmpCook").css({border: '1px solid #F70021'});
            $("#inputTmpPrep").css({border: '1px solid #F70021'});
            $('#verifIsNum').html("<p class='text-danger'>Vous devez saisir uniquement des chiffres !</p>");
            $("#inputTmpCook").on('change', function () {
                if($.isNumeric($("#inputTmpCook").val())) {
                    $("#inputTmpCook").css({border: '1px solid #00E14B'});
                    $('#verifIsNum').html("");
                }
            });
            $("#inputTmpPrep").on('change', function () {
                if($.isNumeric($("#inputTmpPrep").val())) {
                    $("#inputTmpPrep").css({border: '1px solid #00E14B'});
                    $('#verifIsNum').html("");
                }
            });
        }
        else {
            if($("#category").val() == "autres")
                categ = otherChoice;
            if(type_cook == 1)
                tmp_cook = "Sans cuisson";
            console.log(categ);
            var rq = $.ajax({
                url: 'index.php?run=insertNewRecette&title='+title+'&type_dish='+dish_type+'&vege='+vegetarian+'&categ='+categ+'&diff='+difficult+'&cost='+theCost+'&tmp_cook='+tmp_cook+'&tmp_prep='+tmp_prep+'&nb_port='+nb_portion+'&drink='+drink+'&note='+note+'&user='+user,
                method: "GET"
            });
            rq.success(function(result)
            {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }
                else
                {
                    $(".font_logout").text("Etape suivante --> Citez les ingrédients nécessaires... ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=formIngre';
                    }, 3000);
                }
            });
        }
    });
    /************************************************************************
     * END FORM ADD NEW RECETTE
     ************************************************************************/
});