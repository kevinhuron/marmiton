/**
 * Created by kevinhuron on 22/01/2016.
 */
$(document).ready(function(){
    $("#btn_add_field_ingre").click(function(e){
        e.preventDefault();
        $("#table_ingre").append('<tr><td><fieldset class="form-group"><label>Quantité</label><input type="text" class="form-control inputIngre" placeholder="Quantité (ex : 10L , 20g ...)"><small class="text-muted">Saisissez les quantiés (ex : 10L , 20g ...)</small></fieldset></td><td> <fieldset class="form-group"> <label>Ingédients</label> <input type="text" class="form-control inputIngre" placeholder="Ingrédients (ex : lait, beurre...)"> <small class="text-muted">Saisissez les ingrédients (ex : lait, beurre...)</small> </fieldset> </td> </tr>');
    });

    $("#formNewIngre").submit(function(e){
        e.preventDefault();
        var qte_i = $('input[name^=qte]').map(function(){return $(this).val();}).get();
        var ingre = $('input[name^=ingredient]').map(function(){return $(this).val();}).get();

        if($("input[name^=qte]").map(function(){return $(this).val();}).get() == "" || $("input[name^=ingredient]").map(function(){return $(this).val();}).get() == "")
        {
            if ($("input[name^=qte]").map(function(){return $(this).val();}).get() == "") {
                $(".inputQte").css({border: '1px solid #F70021'});
                $('.verifQte').html("<p class='text-danger'>La quantité est nécessaire !</p>");
                $(".inputQte").on('change', function () {
                    $('.verifQte').html("");
                    $(".inputQte").css({border: '1px solid #00E14B'});
                });
            }
            if ($("input[name^=ingredient]").map(function(){return $(this).val();}).get() == "") {
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
                data: {ingred : ingre, qte : qte_i}
            });
            rq.success(function (result) {
                if (result != 1)
                {
                    $(".font_logout").text(result);
                    $("#modal_login").modal("show");
                }

                else
                {
                    $(".font_logout").text("Etape suivante --> Enumérez les étapes à suivre... ");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href='index.php?run=form_step';
                    }, 3000);
                }
            });
        }
    });
});