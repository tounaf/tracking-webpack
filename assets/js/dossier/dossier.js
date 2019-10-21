const $ = require('jquery');
import Routing from '../Routing';
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('./subdossier');
require('./clotureUpload');
require('./dossierUpload');
require('./tranfernotification');

$(document).ready(function () {
    $('#listDossier').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "paging": true,
        "ajax": {
            "url": Routing.generate('ajax_list_dossier'),
            "type": "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 50,
        "orderable": true,
        "columns": [
            {
                "data": "reference",
                "render": function (data, type, row) {
                    var data = '<a href="' + Routing.generate('render_edit_dossier', {'id': row.id, 'currentTab':'declaration'}) + '" class="text-danger">' + row.reference + '</a>';

                    return data;
                }
            },
            {"data": "nom"},
            {"data": "categorie"},
            {"data": "entite"},
            {"data": "statut"},
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
        "autoWidth": false,
        responsive: true,
        columnDefs: [
          { "width": "18%", "targets": 0, orderable: true },
          { "width": "27.5%", "targets": 1, orderable: true},
          { "width": "26.5%", "targets": 2, orderable: true},
          { "width": "26.5%", "targets": 3, orderable: true},
          { "width": "26.5%", "targets": 4, orderable: true},
        ]
    });

    /*  function deleteRender(data, type, row) {
          var data  = '<div class="btn-perso" data-toggle="tooltip" data-original-title="Supprimer" data-title=""><i class="fa fa-remove text-red"></i></div>';

          return data;
      }*/

    /**
     * recherche
     */
    $("#formSearchDossier").on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxloader('show');
        var data = $(this).serialize();
        var url = Routing.generate('search_dossier')
        $.post(url, data,function (data) {
            $('#listDossier').DataTable().ajax.reload()
            $("#formSearchDossier").ajaxloader('hide');
            }
        )
    })

    /**
     * SUBMITTION EDIT FORM SUB DOSSIER
     */
    $(document).on('click','#createSubDossier', function (e) {
        e.preventDefault();
        var main = $('#modalPassword');
        main.ajaxloader('show');
        var id = $('#idSubDossier').val();
        var numeroDossier = $('#sub_dossier_numeroSubDossier').val();
        var libelle = $('#sub_dossier_libelle').val();
        var url = Routing.generate('edit_sub_dossier',{id: id}, true)
        $.post(url,{numero: numeroDossier, libelle: libelle, id: id},function (response) {
            var elt = $('#exampleModalLongTitle');
            $('.modal-body').hide();
            elt.removeClass('text-danger').addClass(' text-success alert alert-'+response.type).text(response.message);
            $('#modalPassword').modal('show');
            setTimeout(function(){// wait for 5 secs(2)
                elt.text("La page va se raffraichir");
                location.reload();
            }, 5000);
            main.ajaxloader('hide');
            return false;
        })
    })

    if ($("#dossier_statutPartiAdverse").val() == 'Personne morale') {
        $("#dossier_formePartieAdverse").css('display','block')
        $("label[for='dossier_formePartieAdverse']").css('display','block')
    }
    $("#dossier_statutPartiAdverse").change(function () {

        if ($(this).val() == 'Personne morale') {
            $("#dossier_formePartieAdverse").css('display','block')
            $("label[for='dossier_formePartieAdverse']").css('display','block')
        } else {
            $("#dossier_formePartieAdverse").css('display','none')
            $("label[for='dossier_formePartieAdverse']").css('display','none')
        }

    })

   


});