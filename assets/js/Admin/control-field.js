import Fonction from "../Fonction";
import Routing from "../user/Routing";

const $ = require('jquery');
require('../jquery.ajaxloader');


var main = $('.main');

/**
 * control format email
 */
$("body").on('blur','#fos_user_email',function () {
    var email = $(this).val();
    var oldEmail  = this.defaultValue;
    var url = Routing.generate('verify_email',{'email': email}, true);
    var isValid = false;
    if (!Fonction.isEmailValidFormat(email)) {

        $('#emailHelp').remove();
        $("#emailFormatInvalid").remove();
        $(this).addClass('error-input invalid');
        $("#fos_user_email").parent().append("<small id='emailFormatInvalid' class='form-text error-text'>Format email invalid</small>");

    } else {

        $('#emailHelp').remove();
        $(this).removeClass('error-input invalid');
        $("#emailFormatInvalid").remove();
        isValid = true;
    }
    if (!isValid) {
        return false;
    }
    $.get(url,{email: email, oldEmail: oldEmail},
        function (response) {
            if (response.status == 200) {
                $('#emailHelp').remove();
                $('#saveCreateUser').attr('disabled',false);
            }
            if (response.status == 403) {
                $('#emailHelp').remove();
                $("#fos_user_email").parent().append("<small id='emailHelp' class='form-text error-text'>Cet adresse email est déja utilisé</small>");
                $('#saveCreateUser').attr('disabled','disabled');
                $("#fos_user_email").addClass('invalid');
            }
        })
})

/**
 * control foramt phone
 */
$('body').on('blur','#fos_user_phoneNumber',function () {
    var phone = $(this).val();
    if (!Fonction.isPhone(phone)) {

        $("#phoneFormatInvalid").remove();
        $(this).addClass('error-input invalid');
        $(this).parent().append("<small id='phoneFormatInvalid' class='form-text error-text'>Numero télephone incorrecte</small>");
    } else {

        $(this).removeClass('error-input invalid');
        $("#phoneFormatInvalid").remove();
    }
})

/**
 * Desactiver bouton submit si il existe input.invalid
 */
$('body').on(' keyup','input',function(){

    var boutonSave = $('#saveCreateUser');
    if ($('input.invalid').length) {
        boutonSave.attr("disabled","disabled");
    } else {
        boutonSave.attr("disabled",false);
    }
})

/**
 * submit form user on create | edit
 */
$("#formCreateUser").submit(function (e) {
    e.preventDefault();
    $('#listUser').ajaxloader('show');
    var data = $(this).serialize();
    var url = Routing.generate('create_user', {}, true);
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
$('.btn-edit').click(function (e) {
    e.preventDefault();

    main.ajaxloader('show');
    var id = $(this).data('id');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id}, true)
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        if (response.status == 403) {
            $('.modal-body').hide();
            $('#modalPassword').modal('show');
            elt.removeClass('text-danger').addClass('alert alert-'+response.type).text(response.message);
            setTimeout(function(){// wait for 5 secs(2)
                elt.text("La page va se raffraichir");
            }, 1000);
            setTimeout(function(){// wait for 5 secs(2)
                location.reload(); // then reload the page.(3)
            }, 3000);
            main.ajaxloader('hide');
            return false;
        } else {
            removeClassStartingWith(elt, 'alert');
            $('.modal-body').show();
            $("#exampleModalLongTitle").addClass('text-danger').text(title);
            $('.modal-body').html(response);
            $('#modalPassword').modal('show');
            main.ajaxloader('hide');
        }
    })
})

/**
 * get form on create
 */
$('#showModalCreate').click(function (e) {
    e.preventDefault();

    main.ajaxloader('show');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route, {}, true);
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        elt.addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalPassword').modal('show');
        main.ajaxloader('hide');
    })
})

/**
 * Get form for delete action
 */
$('.btn-remove').click(function (e) {
    e.preventDefault();
    main.ajaxloader('show');
    var route = $(this).data('route');
    var id = $(this).data('id');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id}, true);
    $.ajax({
        url:url,
        success:function (response) {
            var elt = $('#exampleModalLongTitle');
            if (response.status == 403) {
                $('.modal-body').hide();
                $('#modalPassword').modal('show');
                elt.removeClass('text-danger').addClass('alert alert-'+response.type).text(response.message);
                setTimeout(function(){// wait for 5 secs(2)
                    elt.text("La page va se raffraichir");
                }, 1000);
                setTimeout(function(){// wait for 5 secs(2)
                    location.reload(); // then reload the page.(3)
                }, 3000);
                main.ajaxloader('hide');
                return false;
            } else {
                removeClassStartingWith(elt, 'alert');
                $('.modal-body').show();
                $("#exampleModalLongTitle").addClass('text-danger').text(title);
                $('.modal-body').html(response);
                $('#modalPassword').modal('show');
                main.ajaxloader('hide');
            }
        }
    })
})

/**
 * supprimer des class
 * @param node
 * @param begin
 */
function removeClassStartingWith(node, begin) {
    node.removeClass (function (index, className) {
        return (className.match ( new RegExp("\\b"+begin+"\\S+", "g") ) || []).join(' ');
    });
}