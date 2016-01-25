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

    $(".form_img").submit(function(e){
        e.preventDefault();
        var idr = $('#idr').html();

        var rq = $.ajax({
            method: "GET"
        });
        rq.success(function(result)
        {
            if (result != 1)
            {
                $("#confirmImg1").slideUp();
                $("#errorImg1").slideDown();
                $("#errorImg1").delay(5000).fadeOut('slow', function () {
                    $("#errorImg1").remove();
                });
            }
            else
            {
                $("#errorImg1").slideUp();
                $("#confirmImg1").slideDown();
                $("#confirmImg1").delay(5000).fadeOut('slow', function () {
                    $("#confirmImg1").remove();
                });
                $(window).off('beforeunload');
            }
        });
    });
/*************************************************
 END PREVIEW AND IMPORT IMG
 *********************************************** */
});