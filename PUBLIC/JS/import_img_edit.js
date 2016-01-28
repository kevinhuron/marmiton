/**
 * Created by kevinhuron on 28/01/2016.
 */

$(document).ready(function () {
    var count_img = 0;
    var idr = $('#idr').html();
    var rq = $.ajax({
        url: 'index.php?run=recette_edited&idr='+idr,
        method: "GET"
    });
    rq.success(function(recettes) {
        recettes = jQuery.parseJSON(recettes);

        var recette_content = recettes['list_rec'];

        var img;

        $.each(recette_content, function (key, value) {
            img = value['name_img'];

            if (img == null) {
                $("#all_img_r").append('<div class="text-danger">Aucune image</div>');
            } else {
                $("#all_img_r").append('<div style="display: flex"><a class="example-image-link thumbnail" href="PUBLIC/IMG/' + img + '" data-lightbox="example-set" data-title="IMAGE">' +
                    '<img class="example-image img-thumbnail" src="PUBLIC/IMG/' + img + '" alt="img" style="width: 100%; height: 100%"/>' +
                    '</a></div>');
                count_img++;
            }
        });
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

    //var counter = 0;

    $(".form_img").submit(function(e){
        e.preventDefault();
        var title = $('#input_1').val();
        var $form = $(this);
        $(window).off('beforeunload');
        if (count_img < 4) {
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
                    count_img += 1;
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
                    if (count_img == 1)
                        $(".btn-val").show();
                    else if (count_img == 3)
                    {
                        $('#preview1').toggleClass("hide_im");
                        $('#input_1').toggleClass("hide_im");
                        $('.btn-im').toggleClass("hide_im");
                        count_img +=1;
                    }
                }
            });
        }
    });
    $(".btn-val").click(function(){
        var idr = $('#idr').html();
        location.href = 'index.php?run=edit_rec_page&idr='+idr;
    });
    /*************************************************
     END PREVIEW AND IMPORT IMG
     *********************************************** */
});
