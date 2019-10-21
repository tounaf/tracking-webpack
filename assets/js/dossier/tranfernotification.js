import $ from "jquery";
import Routing from "../Routing";

require('../bootstrap.datepicker.1.6.min');
$(document).ready(function () {
    //Add option null selected
    $("#transfertnotification_usernotif").prepend("<option value='' selected='selected'>veuillez séléctionner</option>");
    $("#transfertnotification_usertransfer").prepend("<option value='' selected='selected'>veuillez séléctionner</option>");
    //Control data selected
    $('select').on('change', function(event ) {
        var prevValue = $(this).data('previous');
        $('select').not(this).find('option[value="'+prevValue+'"]').show();
        var value = $(this).val();
        $(this).data('previous',value); $('select').not(this).find('option[value="'+value+'"]').hide();
    });
    //Control datepicker
    $.fn.datepicker.dates['fr'] = {
        days: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
        daysShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
        daysMin: ["d", "l", "ma", "me", "j", "v", "s"],
        months: ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
        monthsShort: ["janv.", "févr.", "mars", "avril", "mai", "juin", "juil.", "août", "sept.", "oct.", "nov.", "déc."],
        today: "Aujourd'hui",
        monthsTitle: "Mois",
        clear: "Effacer",
        weekStart: 1,
        format: "dd/mm/yyyy"
    };
    var minDateDebut, maxDateFin;
    $('#errordate').hide();
    $("#transfertnotification_datedebut").datepicker({
        language: 'fr',
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        changeMonth: true,
        //Desactive date passées
        startDate: new Date()

    }).on('changeDate', function (ev) {
        minDateDebut = $(this).datepicker('getDate');
    });

    $("#transfertnotification_datefin").datepicker({
        language: 'fr',
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        changeMonth: true,
        //Desactive date passées
        startDate: new Date()
    }).on('changeDate', function (ev) {
        maxDateFin =  $(this).datepicker('getDate');
        var diff = maxDateFin - minDateDebut;
        if(diff <= 0){
            $('#errordate').show();
        }else{
            $('#errordate').hide();
        }
    });


    /* $('#listTransfer').DataTable({
         "processing": true,
         "serverSide": true,
         "searching": false,
         "paging": true,
         "ajax": {
             "url": Routing.generate('transfernotification'),
             "type": "POST"
         },
         "sAjaxDataProp": "data",
         "pageLength": 5,
         "orderable": true,
         "columns": [
             {
                 "data": "reference",
                 "render": function (data, type, row) {
                     var data = '';

                     return data;
                 }
             },
             {"data": "nom"},
             {"data": "categorie"},
             {"data": "entite"},
             {"data": "statut"},
             /!*{ "targets": -1,
                 "data": "edit",
                 "orderable": false,
                 "defaultContent": "",
                 "className": 'text-center',
                 "render": editRow
             },*!/
             /!* { "targets": -1,
                  "data": "delete",
                  "orderable": false,
                  "defaultContent": "",
                  "className": 'text-center',
                  "render": deleteRender
              }*!/
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
     });*/


    /*$('#transfernotification').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var url = Routing.generate('transfernotification');
        var str = form.serializeArray();
        $.ajax({
            type: "POST",
            url: url,
            data: str,
            success: function (data) {

            }
        })

        return false;

    })*/

})

