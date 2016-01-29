/**
 * Created by kevinhuron on 29/01/2016.
 */
$(document).ready(function(){
    $('.carousel').carousel({
        interval: 200000
    })
    var idr = $("#id_r").html();
    var rq = $.ajax({
        url: 'index.php?run=get_kitchen_mode&idr='+idr,
        method: "GET"
    });
    rq.success(function(step)
    {
        step = jQuery.parseJSON (step);
        var silder = $(".has-slider");
        var silder_item = $("#c_item");
        var steps = step['step'];
        var recette = step['recette'];
        var i = 1;

        $.each(steps, function (key, value) {
            silder.before('<li data-target="#carousel-example-generic" data-slide-to="'+i+'"></li>');
            silder_item.append('<div class="carousel-item">' +
                '<img src="PUBLIC/IMG/fond_kitchen_mode.jpg" alt="slide n°'+i+'">' +
                '<div class="carousel-caption">' +
                '<h1 style="color: #000;font-size: 5rem;margin-bottom: 100px;">Etape N° '+i+'</h1>' +
                '<h2 style="color: #000">'+value['name_step']+'</h2>' +
                '</div>' +
                '</div>');
            i++;
        });
        $.each(recette, function (key, value) {
            $("#title").html(value['title']);
        });
    });
});