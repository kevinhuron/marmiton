$(document).ready(function(){
    $(window).bind('beforeunload', function(){
        return 'Voulez vous vraiment quitter cette ? Votre recette n\'est pas finit et ne sera donc pas enregistrée. Vous devrez recommencer votre recette depuis le début.';
    });
/****************************************************************
 PREVIEW AND IMPORT IMG
 **************************************************************** */
    $("#input_1").change(function (){
        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            $('#preview1').attr('src', e.target.result);
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });

    var counter = 0;

    $(".form_img").submit(function(e){
        e.preventDefault();
        var idr = $('#idr').html();
        var title = $('#input_1').val();
        var $form = $(this);
        $(window).off('beforeunload');
        if (counter < 4) {
            var formdata = (window.FormData) ? new FormData($form[0]) : null;
            var data = (formdata !== null) ? formdata : $form.serialize();
            var rq = $.ajax({
                method: "POST",
                contentType: false,
                processData: false,
                dataType: 'json',
                data: data,
                url: 'index.php?run=importImg'
            });

            rq.success(function (result) {
                if (result != 1) {
                    $("#confirmImg1").slideUp();
                    $("#errorImg1").slideDown();
                    $("#errorImg1").delay(5000).fadeOut('slow', function () {
                        $("#errorImg1").remove();
                    });
                }
                else {
                    counter += 1;
                    var nt = title.split("\\");
                    var count = nt.length;
                    $("#errorImg1").slideUp();
                    $("#confirmImg1").slideDown();
                    $("#confirmImg1").delay(5000).fadeOut('slow', function () {
                        $("#confirmImg1").remove();
                    });
                    $('#preview1').attr('src', '');
                    $('#input_1').val('');
                    $(".result").append('<div class="col-md-4 col-xs-12 alert-success paddding_all text-lg-center text-md-center text-sm-center">L\'image "' + nt[count - 1] + '" a été importée</div>');
                    if (counter == 1)
                        $(".btn-val").show();
                    else if (counter == 3)
                    {
                        $('#preview1').toggleClass("hide_im");
                        $('#input_1').toggleClass("hide_im");
                        $('.btn-im').toggleClass("hide_im");
                        counter +=1;
                    }
                }
            });
        }
    });

    $(".btn-val").click(function(e){
        $('#modal_login').modal("show");
        window.setTimeout(function() {
       console.log("Validate recipe");
        var id = $('#idr').val();
        var rq = $.ajax({
            method: "POST",
            data: {"id": id },
            url: 'index.php?run=check_matches'
        });

        rq.success(function (result) {
            console.log(result);
            var percent = parseInt(result);
            if (percent < 80) {
                console.log("No matches < 80");
                $("#content_login").html('<span class="text_logout">Votre recette a été validée. On vous redirige sur celle-ci. </span>');
                console.log("Matches > 80");
                window.setTimeout(function() {
                   location.href= $(".btn-val").attr("data");
                }, 5000);
            }
            else
            {
                $("#content_login").html('<span class="text_logout">Votre recette a une similititude de '+percent+'%  à une autre. Votre recette n\'est pas sauvegardé. </span>');
                console.log("Matches > 80");
                window.setTimeout(function() {
                  location.href='index.php?run=dashboardShow';
                }, 5000);
            }
        });
        }, 5000);
    });
/*************************************************
 END PREVIEW AND IMPORT IMG
 *********************************************** */
});