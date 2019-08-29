import Fonction from "../Fonction";
import Routing from "./Routing";

const $ = require('jquery');

/**
 * control format email
 */
$("#fos_user_email").keyup(function () {
    var email = $(this).val();
    var url = Routing.generate('verify_email',{'email': email}, true);

    if (!Fonction.isEmailValidFormat(email)) {
        $("#emailFormatInvalid").remove();
        $(this).addClass('border border-danger');
        $("#fos_user_email").parent().append("<small id='emailFormatInvalid' class='form-text text-danger'>Format email invalid</small>");
        $('#saveCreateUser').attr('disabled','disabled');
        return false;
    } else {
        $(this).removeClass('border border-danger');
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
                $("#fos_user_email").parent().append("<small id='emailHelp' class='form-text text-muted'>Cet addresse email est déja utilisé</small>");
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
        $(this).addClass('border border-danger');
        $(this).parent().append("<small id='phoneFormatInvalid' class='form-text text-danger'>Numero télephone incorrecte</small>");
        $('#saveCreateUser').attr('disabled','disabled');
    } else {

        $(this).removeClass('border border-danger');
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
$('.btn-edit-user').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = Routing.generate('edit_user',{'id':id})
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
            return false;
        } else {
            removeClassStartingWith(elt, 'alert');
            $('.modal-body').show();
            $("#exampleModalLongTitle").addClass('text-danger').text('Ajout/suppression ');
            $('.modal-body').html(response);
            $('#modalPassword').modal('show');
        }
    })
})

/**
 * get form on create
 */
$('#showModalCreateUser').click(function (e) {
    e.preventDefault();
    var route = $(this).data('route');
    var url = Routing.generate(route);
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        $("#exampleModalLongTitle").addClass('text-danger').text($(this).data('title'));
        $('.modal-body').show();
        $('#modalPassword').modal('show');
    })
})

$('.btn-remove-user').click(function (e) {
    e.preventDefault();
    var route = $(this).data('route');
    var id = $(this).data('id');
    console.log(route);
    console.log(Routing.generate(route,{'id':id}));
    return false;
    var url = Routing.generate(route,{'id':id});
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
                return false;
            } else {
                removeClassStartingWith(elt, 'alert');
                $('.modal-body').show();
                $("#exampleModalLongTitle").addClass('text-danger').text('SUPPRESSION UTILISATEUR');
                $('.modal-body').html(response);
                $('#modalPassword').modal('show');
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