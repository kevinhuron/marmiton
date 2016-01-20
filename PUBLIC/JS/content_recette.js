/**
 * Created by kevinhuron on 19/01/2016.
 */

$(document).ready(function(){


///////////////////////////////////////////////////////////////////////////
    var cle = $("#id_r").html();
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
        var slider = $("#theslider");
        var i = 1;

        $.each(recette_content, function (key, value) {
            img = value['name_img'];
            slider.append('<div data-p="112.50" style="display: none;" class="slideImg">' +
                '<img data-u="image" src="PUBLIC/IMG/'+img+'" />' +
                '<div data-u="thumb">' +
                '<img src="PUBLIC/IMG/'+img+'" />' +
                '<div class="title_back"></div>' +
                '<div class="title">' +
                '</div>' +
                '</div>' +
                '</div>');

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
            $('#categ_r').append(value['name_c']);
        });
        $.each(ingredient, function (key, value) {
            $('#ingre').append('- '+value['name_in']+' ('+value['qt']+')<br>');

        });
        $.each(step, function (key, value) {
            $('#step').append('- '+value['name_step']+'<br>');
        });
    });

    var jssor_1_options = {
        $AutoPlay: true,
        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$
        },
        $ThumbnailNavigatorOptions: {
            $Class: $JssorThumbnailNavigator$,
            $Cols: 3,
            $Align: 200
        }
    };

    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    function ScaleSlider() {
        var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            refSize = Math.min(refSize, 600);
            jssor_1_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
});