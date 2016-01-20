/**
 * Created by kevinhuron on 18/01/2016.
 */

$(document).ready(function(){
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
            if(new_title == "")
                $('.list-group').append('<h1><div class="text-danger">Veuillez saisir un titre</div></h1>');
           else if(all_title === undefined)
            {
                $('.list-group').append('<h1><div class="text-danger">Aucune recette est similaire à vos mots-clés</div></h1>');
            }
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
           if (all_title_recette['name'] != null) {
               $(".user").append(" " + all_title_recette['name']);
               $(".co-do").append('<a class="dropdown-item" href="index.php?run=dashboardShow">Votre dashboard</a>');
               $(".isco").text('Se déconnecter');
               $(".isco").attr("href", 'index.php?run=logout');
           }
       });
   });

    $("#btn_val_title").click(function(e){
        e.preventDefault();
        var title = $("#InputTitle").val();
        document.location.href='index.php?run=create_recette&value='+title;

    });
});