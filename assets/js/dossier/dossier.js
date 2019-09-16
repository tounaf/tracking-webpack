const $ = require('jquery');
import Routing from '../Routing';
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');

$(document).ready(function () {
    console.log('tafiditra');
    $('#listDossier').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "paging": true,
        "info": true,
        "ajax": {
            "url": Routing.generate('ajax_list_dossier'),
            "type": "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 5,
        "orderable": true,
        "columns": [
            {"data": "reference"},
            {"data": "nom"},
            {"data": "categorie"},
            {"data": "entite"},
            {"data": "statut"},
            { "targets": -1,
                "data": "edit",
                "orderable": false,
                "defaultContent": "",
                "className": 'text-center',
                "render": editRow
            },
            /* { "targets": -1,
                 "data": "delete",
                 "orderable": false,
                 "defaultContent": "",
                 "className": 'text-center',
                 "render": deleteRender
             }*/
        ],
        bLengthChange: false,
        info: false,
        language: {
            processing:     "Chargement en cours...",
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
    });

    function editRow(data, type, row) {
        data = '                                <button  data-target="#modalPassword" data-title="{{ \'label.edit.create\'|trans }}" data-route="edit_societe" class="btn btn-link text-danger btn-edit" data-id="{{ societe.id }}" type="button"><i class="fa fa-edit"></i></button>\n' +
            '                                <button  data-target="#modalPassword" data-title="{{ \'label.delete\'|trans }}" data-route="delete_societe" class="btn btn-link text-danger btn-remove" data-id="{{ societe.id }}" type="button"><i class="fa fa-trash-o"></i></button>';

        return data;
    }

    /*  function deleteRender(data, type, row) {
          var data  = '<div class="btn-perso" data-toggle="tooltip" data-original-title="Supprimer" data-title=""><i class="fa fa-remove text-red"></i></div>';

          return data;
      }*/

    /**
     * recherche
     */
    $("#formSearchDossier").on('submit', function (e) {
        e.preventDefault();
        console.log($(this));
        $(this).ajaxloader('show');
        var data = $(this).serialize();
        var url = Routing.generate('search_dossier')
        $.post(url, data,function (data) {
                console.log(data);
            $('#listDossier').DataTable().ajax.reload()
            $("#formSearchDossier").ajaxloader('hide');
            }
        )
    })

});