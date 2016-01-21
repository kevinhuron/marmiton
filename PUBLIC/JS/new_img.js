$(document).ready(function(){
    //PREVIEW IMG
    $("#input_1").change(function (){
        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            $('#preview1').attr('src', e.target.result);
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
    $("#input_2").change(function (){
        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            $('#preview2').attr('src', e.target.result);
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
    $("#input_3").change(function (){
        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            $('#preview3').attr('src', e.target.result);
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
});