import Fonction from "../Fonction";
import Routing from "./Routing";

const $ = require('jquery');

/**
 * control format email
 */
$("#fos_user_email").keyup(function () {
    var email = $(this).val();
    var url = Routing.generate('verify_email',{'email': email});

    if (!Fonction.isEmailValidFormat(email)) {
        $("#emailFormatInvalid").remove();
        $(this).addClass('');
        $("#fos_user_email").parent().append("<small id='emailFormatInvalid' class='form-text error-text'>Format d'email invalide</small>");
        $('#saveCreateUser').attr('disabled','disabled');
        return false;
    } else {
        $(this).removeClass('border');
        $("#emailFormatInvalid").remove();
        $('#saveCreateUser').attr('disabled',false);
    }
    $.get(url,email,
        function (response) {
            if (response.status == 200) {
                console.log(response);
                $('#emailHelp').remove();
                $('#saveCreateUser').attr('disabled',false);
            }
            if (response.status == 403) {
                $('#emailHelp').remove();
                $("#fos_user_email").parent().append("<small id='emailHelp' class='form-text error-text text-muted'>Cet addresse email est déja utilisé</small>");
                $('#saveCreateUser').attr('disabled','disabled');
            }
        })
})

/**
 * control foramt phone
 */
$('#fos_user_phoneNumber').keyup(function () {
    var phone = $(this).val();
    if (!Fonction.isPhone(phone)) {

        $("#phoneFormatInvalid").remove();
        $(this).addClass('border');
        $(this).parent().append("<small id='phoneFormatInvalid' class='form-text error-text'>Numero de télephone incorrecte</small>");
        $('#saveCreateUser').attr('disabled','disabled');
    } else {

        $(this).removeClass('border');
        $("#phoneFormatInvalid").remove();
        $('#saveCreateUser').attr('disabled',false);
    }
})

/**
 * submit form user on create | edit
 */
$("#formCreateUser").submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    var url = Routing.generate('create_user', {});
    $.post(
        url,
        data,
        function (response) {
            var elt = $('#exampleModalLongTitle');
            if (response.status == 500) {
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
            }
            if (response.status == 200) {

                removeClassStartingWith(elt,'alert')
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
                $('.modal-body').toggle();
                $('.modal-footer').toggle();
            }
            $('.modal-body').attr('display','none');
            $('.modal-body').prop('display','none');
        }
    )

})

/**
 * get form user edit
 */
$('.btn-edit-user').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = Routing.generate('edit_user',{'id':id})
    $.get(url,function (response) {
        $('.modal-body').html(response);
        $('#modalPassword').modal('show');
    })
})

/**
 * get form on create
 */
$('#showModalCreateUser').click(function (e) {
    e.preventDefault();
    var url = Routing.generate('create_user',{});
    $.get(url,function (response) {
        $('.modal-body').html(response);
        $('#modalPassword').modal('show');
    })
})