/**
 * Created by kevinhuron on 19/01/2016.
 */

$(document).ready(function(){
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


    var cle = $("#id_r").html();
    var rq = $.ajax({
        url: 'index.php?run=get_content&cle='+cle,
        method: "GET"
    });
    rq.success(function(content_recette)
    {
        $("#result_verif").slideDown();
        content_recette = jQuery.parseJSON (content_recette);

        var all_title = content_recette['content_recette'];
        var img;
        var slider = $("#theslider");
        var i = 1;

        $.each(all_title, function (key, value) {
            img = value['name_img'];
            slider.append('<div data-p="112.50" style="display: none;">' +
                '<img data-u="image" src="PUBLIC/IMG/img" />' +
                '<div data-u="thumb">' +
                '<img src="PUBLIC/IMG/recette1.jpg" />' +
                '<div class="title_back"></div>' +
                '<div class="title">' +
                '</div>' +
                '</div>' +
                '</div>');

            $('#titre_r').append(value['title']);
            $('#categ_r').append(value['name_c']);
            $('#type_dish_r').append(value['type_dish']);
            $('#diff_r').append(value['difficulty']);
            $('#cost_r').append(value['cost']);
            $('#tmp_prep_r').append(value['time_prep']);
            $('#tmp_cook_r').append(value['cook_time']);
            $('#test').append(value['']);
        });
    });


});