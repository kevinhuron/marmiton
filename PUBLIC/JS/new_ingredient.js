/**
 * Created by kevinhuron on 22/01/2016.
 */
$(document).ready(function(){
    $(window).bind('beforeunload', function(){
        return 'Voulez vous vraiment quitter cette ? Votre recette n\'est pas finit et ne sera donc pas enregistrée. Vous devrez recommencer votre recette depuis le début.';
    });

    $("#btn_add_field_ingre").click(function(e){
        e.preventDefault();
        $("#table_ingre").append('<tr><td><fieldset class="form-group"><label>Quantité</label><input type="text" class="form-control inputQte" name="qte[]" placeholder="Quantité (ex : 10L , 20g, 10 cuillères à soupe ...)"><small class="text-muted">Saisissez les quantiés (ex : 10L , 20g ...)</small></fieldset><div class="verifQte"></div></td><td> <fieldset class="form-group"> <label>Ingédients</label> <input type="text" class="form-control inputIngre" name="ingredient[]" placeholder="Ingrédients (ex : lait, beurre...)"> <small class="text-muted">Saisissez les ingrédients (ex : lait, beurre...)</small> </fieldset> <div class="verifIngre"></div></td> </tr>');
    });

    $("#btn-remove-line").click(function(e) {
        e.preventDefault();
        $("tr").remove("#table_ingre tbody>tr:last");
    });

    $("#formNewIngre").submit(function(e){
        e.preventDefault();
        var qte_i = $('input[name^=qte]').map(function(){return $(this).val();}).get();
        var ingre = $('input[name^=ingredient]').map(function(){return $(this).val();}).get();
        var id_rec = $("#idr").html();
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
                data: {ingred : ingre, qte : qte_i, idr : id_rec}
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
                        location.href='index.php?run=form_step&idr='+id_rec+'&title='+title_r;
                    }, 3000);
                    $(window).off('beforeunload');
                }
            });
        }
    });
});