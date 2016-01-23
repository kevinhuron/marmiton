/**
 * Created by kevinhuron on 22/01/2016.
 */
$(document).ready(function(){
    $("#btn_add_field_step").click(function(e){
        e.preventDefault();
        $("#table_step").append('<tr> <td> <fieldset class="form-group"> <label>Nom de l\'étape</label> <input type="text" class="form-control inputStep" name="name_step[]" placeholder="Etapes (ex : Mélangez le lait avec les oeufs ...)"> <small class="text-muted">Saisissez les étapes (ex : Mélangez le lait avec les oeufs ...)</small> </fieldset> <div class="verifQte"></div> </td> </tr>');
    });

    $("#formNewStep").submit(function(e){
        e.preventDefault();
        var step = $('input[name^=name_step]').map(function(){return $(this).val();}).get();
        var idr = $("#idr").html();

        if($('input[name^=name_step]').map(function(){return $(this).val();}).get() == "") {
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
                    $(".font_logout").text("Etape suivante --> Choisissez de jolies photos (0 à 3 max)... ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=newRecetteImg&idr='+idr;
                    }, 3000);
                }
            });
        }
    });
});