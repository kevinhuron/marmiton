/**
 * Created by kevinhuron on 29/01/2016.
 */
$(document).ready(function(){
    var inscription;
    $("input[name='id']").keyup(function () {
        var id = $("input[name='id']").val();
        var rq = $.ajax({
            url: 'index.php?run=check_id_exist&id='+id,
            method: "POST"
        });
        rq.success(function(result)
        {
            if (result != 1) {
                $("input[name='id']").css({
                    borderColor: '#00E14B'
                });
                $('#verifLogin').html("<span class='text-success'>Ce nom d'utilisateur est disponible.</span>");
                inscription = true;
            }
            else {
                $("input[name='id']").css({
                    borderColor: 'red'
                });
                $('#verifLogin').html("<span class='text-danger'>Ce nom d'utilisateur est déjà pris.</span>");
                inscription = false;
            }
        });
    });

    $("input[name='Confirm_pass']").keyup(function () {
        if ($(this).val() !== $("input[name='pass']").val()) {
            $("input[name='Confirm_pass']").css({
                borderColor: 'red'
            });
            $("input[name='pass']").css({
                borderColor: 'red'
            });
            $('#verifMdp').html("<span class='text-danger'>Les mots de passes ne correspondent pas.</span>");
            inscription = false;
        }
        else {
            $("input[name='pass']").css({
                borderColor: '#00E14B'
            });
            $("input[name='Confirm_pass']").css({
                borderColor: '#00E14B'
            });
            $('#verifMdp').html("<span class='text-success'>Les mots de passes correspondent.</span>");
            inscription = true;
        }
    });

    $(".form_inscr").submit(function(e){
        e.preventDefault();
        var id = $("input[name='id']").val();
        var passwd = $("input[name='Confirm_pass']").val();
        var last_name = $("input[name='last_name']").val();
        var first_name = $("input[name='first_name']").val();
        var addr = $("input[name='addr']").val();
        var cp = $("input[name='cp']").val();
        var ville = $("input[name='ville']").val();
        var birth = $("input[name='date']").val();

        if(inscription == false || $("input[name='last_name']").val() == "" || $("input[name='first_name']").val() == "" ||
            $("input[name='addr']").val() == "" || $("input[name='cp']").val() == "" || $("input[name='ville']").val() == "" ||
            $("input[name='date']").val() == "" || $("input[name='id']").val() == "" || $("input[name='pass']").val() == ""){
            if(inscription == false)
                $("#modal_login").modal("show");
            if($("input[name='last_name']").val() == ""){
                $("input[name='last_name']").css({border: '1px solid #F70021'});
                $('#verifLastName').html("<span class='text-danger'>Votre nom est nécessaire !</span>");
                $("input[name='last_name']").on('change', function () {
                    $('#verifLastName').html("");
                    $("input[name='last_name']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='first_name']").val() == ""){
                $("input[name='first_name']").css({border: '1px solid #F70021'});
                $('#verifFirstName').html("<span class='text-danger'>Votre prénom est nécessaire !</span>");
                $("input[name='first_name']").on('change', function () {
                    $('#verifFirstName').html("");
                    $("input[name='first_name']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='addr']").val() == ""){
                $("input[name='addr']").css({border: '1px solid #F70021'});
                $('#verifAddr').html("<span class='text-danger'>Votre adresse est nécessaire !</span>");
                $("input[name='addr']").on('change', function () {
                    $('#verifAddr').html("");
                    $("input[name='addr']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='cp']").val() == ""){
                $("input[name='cp']").css({border: '1px solid #F70021'});
                $('#verifCp').html("<span class='text-danger'>Votre code postal est nécessaire !</span>");
                $("input[name='cp']").on('change', function () {
                    $('#verifCp').html("");
                    $("input[name='cp']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='ville']").val() == ""){
                $("input[name='ville']").css({border: '1px solid #F70021'});
                $('#verifVille').html("<span class='text-danger'>Votre ville est nécessaire !</span>");
                $("input[name='ville']").on('change', function () {
                    $('#verifVille').html("");
                    $("input[name='ville']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='date']").val() == ""){
                $("input[name='date']").css({border: '1px solid #F70021'});
                $('#verifDate').html("<span class='text-danger'>Votre date de naissance est nécessaire !</span>");
                $("input[name='date']").on('change', function () {
                    $('#verifDate').html("");
                    $("input[name='date']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='id']").val() == ""){
                $("input[name='id']").css({border: '1px solid #F70021'});
                $('#verifLogin').html("<span class='text-danger'>Votre login est nécessaire !</span>");
                $("input[name='id']").on('change', function () {
                    $('#verifLogin').html("");
                    $("input[name='id']").css({border: '1px solid #00E14B'});
                });
            }
            if($("input[name='pass']").val() == ""){
                $("input[name='pass']").css({border: '1px solid #F70021'});
                $('#verifMdp').html("<span class='text-danger'>Votre mot de passe est nécessaire !</span>");
                $("input[name='pass']").on('change', function () {
                    $('#verifMdp').html("");
                    $("input[name='pass']").css({border: '1px solid #00E14B'});
                });
            }
        } else {
            var rq = $.ajax({
                url: 'index.php?run=inscription_user',
                data : {"id" : id , "passwd" : passwd, "last_name": last_name, "first_name": first_name, "addr": addr, "cp": cp,"ville": ville, "birth": birth}
            });
            rq.success(function(result) {
                if (result != 1)
                    $("#modal_login").modal("show");
                else{
                    $(".font_logout").text("Bienvenue");
                    $("#content_login").hide();
                    $("#spinnerl").show();
                    $("#modal_login").modal("show");
                    window.setTimeout(function() {
                        location.href = "index.php?run=login"
                    }, 1000);
                }
            });
        }
    });
});