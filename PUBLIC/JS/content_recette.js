/**
 * Created by kevinhuron on 19/01/2016.
 */

$(document).ready(function(){

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
        var silder = $(".has-slider");
        var silder_item = $("#c_item");
        var i = 1;

        $.each(all_title, function (key, value) {
            img = value['name_img'];
            silder.before('<li data-target="#carousel-example-generic" data-slide-to="'+i+'"></li>')
            silder_item.append('<div class="carousel-item"><img src="PUBLIC/IMG/'+img+'" alt="slide n°'+i+'"><div class="carousel-caption"><h3>'+value['title']+'</h3><p>'+value['type_dish']+'</p></div></div>')

            $('#id_r').append('<img src="PUBLIC/IMG/' + img + '" alt="img" style="width: 100%;height: 100%;" />' +
                '<h4 class="card-title">' + value['title'] + '</h4>' +
                '<p class="card-text"><strong>' + value['type_dish'] + '</strong><br>' +
                'Difficulté : ' + value['difficulty'] + '<br>' +
                'Coût : ' + value['cost'] + '<br>' +
                'Portions : ' + value['nb_port'] + ' </p>');
        });
    });
});