const $ = require('jquery');

import Routing from './Routing';

import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.min';
const dt = require('datatables');
import Fonction from '../Fonction';

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

/**
 * show modal resetting password
 */
$('#showModalPassword').click(function () {

    $('#modalPassword').modal('show');
    var titleModal = $("#exampleModalLongTitle").data('title');
    var elt = $("#exampleModalLongTitle");
    removeClassStartingWith(elt,'alert')
    $("#exampleModalLongTitle").addClass('text-danger').text(titleModal);
    $('#email_user').val('');
    $('.modal-body').show();
    $('.modal-footer').show();
})

/**
 * submit resetting password
 */
$('#buttonResetting').click(function (e) {
    e.preventDefault();
    var email = $('#email_user').val();
    $.ajax({
        url: Routing.generate('resetting_password_user'),
        type: 'POST',
        data: {
            email: email
        },
        success: function (data) {
            var elt = $('#exampleModalLongTitle');
            if (data.status == 401) {
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
            }
            if (data.status == 200) {

                removeClassStartingWith(elt,'alert')
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
                $('.modal-body').toggle();
                $('.modal-footer').toggle();
            }
            $('.modal-body').attr('display','none');
            $('.modal-body').prop('display','none');
        }
    })
})

$(document).ready(function () {

    var dataTable = $('#listUser').DataTable({
        destroy : true,
        bLengthChange: false,
        info: false,
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:     "Afficher MENU &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement START &agrave; END sur TOTAL &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de MAX &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
    });
    $("#search_box").on('keyup', function() {
        dataTable.search($(this).val()).draw();
    });


    $("#fos_user_email").keyup(function () {
        var email = $(this).val();
        var url = Routing.generate('verify_email',{'email': email});

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
})

$("#formCreateUser").submit(function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    var url = Routing.generate('create_user');
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


$('.btn-edit-user').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = Routing.generate('edit_user',{'id':id})
    $.get(url,function (response) {
        $('.modal-body').html(response);
        $('#modalPassword').modal('show');
    })
})

$('#showModalCreateUser').click(function (e) {
    e.preventDefault();
    var url = Routing.generate('create_user');
    $.get(url,function (response) {
        $('.modal-body').html(response);
        $('#modalPassword').modal('show');
    })
})

$('#saveCreateUser').click(function (e) {
    e.preventDefault();
    console.log($(this));
})