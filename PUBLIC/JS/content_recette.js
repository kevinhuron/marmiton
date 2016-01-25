/**
 * Created by kevinhuron on 19/01/2016.
 */

$(document).ready(function(){

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
            /*$("#all_img_r").append('<div data-p="112.50" style="display: none;" class="slideImg">' +
                '<img data-u="image" src="PUBLIC/IMG/'+img+'" />' +
                '<div data-u="thumb">' +
                '<img src="PUBLIC/IMG/'+img+'" />' +
                '<div class="title_back"></div>' +
                '<div class="title">' +
                '</div>' +
                '</div>' +
                '</div>');*/
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
            $('#categ_r').append(value['name_c']);
        });
        $.each(ingredient, function (key, value) {
            $('#ingre').append('- '+value['name_in']+' ('+value['qt']+')<br>');

        });
        $.each(step, function (key, value) {
            $('#step').append('- '+value['name_step']+'<br>');
        });
    });
});