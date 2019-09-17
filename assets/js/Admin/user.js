import Fonction from "../Fonction";

const $ = require('jquery');

import Routing from '../Routing';

require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('./control-field');
require('../Fonction');

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
    //remove help block si existe
    $("#emailFormatInvalid").remove();
    //toujours activer bouton quand on show modal change pwd
    $('#buttonResetting').attr('disabled',false);
    $("#exampleModalLongTitle").addClass('text-danger').text(titleModal);
    $('#email_user').removeClass('border border-danger').val('');
    $('.modal-body').show();
    $('.modal-footer').show();
})

/**
 * control format email
 */
$("#email_user").keyup(function () {
    var email = $(this).val();

    if (!Fonction.isEmailValidFormat(email)) {
        $("#emailFormatInvalid").remove();
        $(this).addClass('border border-danger');
        $(this).parent().append("<small id='emailFormatInvalid' style='height: 32px;' class='form-text text-danger'>Format email invalid</small>");
        $('#buttonResetting').attr('disabled','disabled');
        return false;
    } else {
        $(this).removeClass('border border-danger');
        $("#emailFormatInvalid").remove();
        $('#buttonResetting').attr('disabled',false);
    }
})

/**
 * submit resetting password
 */
$('#buttonResetting').click(function (e) {
    e.preventDefault();
    var main = $('.modal-content');
    var email = $('#email_user').val();
    if (email == ''){
        $('#email_user').parent().append("<small id='emailFormatInvalid' style='height: 32px;' class='form-text text-danger'>Veuillez saisir un email invalid</small>");
        $(this).attr('disabled','disabled');
        return false;
    }
    main.ajaxloader('show');
    $.ajax({
        url: Routing.generate('resetting_password_user',{}, true),
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
            main.ajaxloader('hide');
        }
    })
})

// var dataTable = $('#listAdmin').DataTable({
//     destroy : true,
//     bLengthChange: false,
//
//     info: false,
//     language: {
//         processing:     "Traitement en cours...",
//         search:         "Rechercher&nbsp;:",
//         lengthMenu:     "Afficher _MENU_ &eacute;l&eacute;ments",
//         info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
//         infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
//         infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
//         infoPostFix:    "",
//         loadingRecords: "Chargement en cours...",
//         zeroRecords:     "Aucun &eacute;l&eacute;ment trouv&eacute;",
//         emptyTable:     "Aucune donnée disponible dans le tableau",
//         paginate: {
//             first:      "Premier",
//             previous:   "Pr&eacute;c&eacute;dent",
//             next:       "Suivant",
//             last:       "Dernier"
//         },
//         aria: {
//             sortAscending:  ": activer pour trier la colonne par ordre croissant",
//             sortDescending: ": activer pour trier la colonne par ordre décroissant"
//         }
//     },
// });

$("#search_box").on('keyup', function() {
    // dataTable.state().clear().search( this.value ).draw();
});
$(document).ready(function () {
    var dataTable = $('#listAdmin').DataTable({
        searching : true,
        destroy : true,
        bLengthChange: false,
        info: false,
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:     "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; trouv&eacute;",
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
        "autoWidth": false,
        responsive: true,
        columnDefs: [
          { "width": "20%", "targets": 0, orderable: true },
          { "width": "20%", "targets": 1, orderable: true},
          { "width": "5%", "targets": 6, orderable: false}
        ]
       

    });

    $('.alert_value').click(function () {
        $(this).css('display','none')
    })
});
