const $ = require('jquery');

import Routing from './Routing';

import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.min';
// const dt = require('datatables');
require('datatables/media/css/jquery.dataTables.min.css');
// require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('./control-field');

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
        console.log($(this).val());
        dataTable.search($(this).val()).draw();
    });

    $('.alert_value').click(function () {
        $(this).css('display','none')
    })

})
