const $ = require('jquery');
import Routing from '../Routing';
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('../jquery.ajaxloader');


var main = $('#avocat-list');

$(document).ready(function () {
    var table = $('#avocat-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": Routing.generate('liste_avocat'),
            "type" : "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 10,
        "orderable": true,
        "columns": [
            {"data": "nomPrenom",},
            {"data": "prestation",},
            {"data": "convenu",},
            {"data": "payer",},
            {"data": "reste_payer",},
            {"data": "devise",},
            {"data": "statuts",},
            {
                "targets": -1,
                "data": "edit",
                "orderable": false,
                "defaultContent": "",
                "className": 'text-center',
                "render": editRow
            },
        ],
        bLengthChange: false,
        info: false,
        "order": [[ 0, "desc" ]],
        searching: false,
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
            info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; trouv&eacute;",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Pr&eacute;c&eacute;dent",
                next: "Suivant",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
        "autoWidth": false,
        responsive: true,
       /*columnDefs: [
            {"targets": 0, orderable: true },
            {"targets": 1, orderable: true},
            {"targets": 2, orderable: true},
            {"targets": 3, orderable: true},
            {"targets": 4, orderable: true},
            {"targets": 5, orderable: true},
            {"targets": 6, orderable: true},
            {"targets": 7, orderable: true},
        ]*/
    });
    function editRow(data, type, row) {
        data = ' <button  data-target="#modalIntervenant" data-title="AJOUT/MODIFICATION" data-route="intervenant_edit" class="btn-edit"  data-id="'+row.id+'" type="button"><i class="icon-edit"></i></button>\n' +
            '  <button  data-target="#modalIntervenant" data-title="SUPPRESSION" data-route="intervenant_delete" class="btn-remove" data-id="'+row.id+'" type="button"><i class="icon-trash"></i></button>';
        return data;
    }
});
$('body').on('click', '.btn-remove', function (e) {
    //    var id = table.row(this).id();
    e.preventDefault();
 //   main.ajaxloader('show');
    var route = $(this).data('route');
    var id = $(this).data('id');
    var title = $(this).data('title');
   var url = Routing.generate(route,{'id':id});
    $.ajax({
         url:url,
         success:function (response) {
             var elt = $('#exampleModalLongTitle');
             if (response.status == 403) {
                 $('.modal-body').hide();
                 $('#modalIntervenant').modal('show');
                 $('#intervenant_convenu').keydown(function(e){
                     e.preventDefault();
                     $('intervenant_convenu').css("background-color", "yellow");
                 });
                 elt.removeClass('text-danger').addClass('alert alert-'+response.type).text(response.message);
                 setTimeout(function(){// wait for 5 secs(2)
                     elt.text("La page va se raffraichir");
                 }, 1000);
                 setTimeout(function(){// wait for 5 secs(2)
                     location.reload(); // then reload the page.(3)
                 }, 3000);
           //      main.ajaxloader('hide');
                 return false;
             } else {
                 removeClassStartingWith(elt, 'alert');
                 $('.modal-body').show();
                 $("#exampleModalLongTitle").addClass('text-danger').text(title);
                 $('.modal-body').html(response);
                 $('#modalIntervenant').modal('show');
                 main.ajaxloader('hide');
             }
         }
     })
});

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
 * get form on create
 */
$('body').on('click', '#modalCreateIntervenant', function (e) {
    e.preventDefault();
 //   main.ajaxloader('show');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var dossierId = $(this).data('dossier');
    var url = Routing.generate(route, {id: dossierId},true);
   // var convenu = $('#intervenant_convenu').val();
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        elt.addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalIntervenant').modal('show');
        //main.ajaxloader('hide');
    })
});

$(' body #modalIntervenant').on('blur keyup mouseout', '#intervenant_restePayer', function(event){
    event.preventDefault();
    var convenu = $("#modalIntervenant #intervenant_restePayer").val();
    if (convenu != 0){
        $(".intervenantStatut").val("A Payer");
    } else if (convenu == 0){
        $(".intervenantStatut").val("Soldé");
    }
    else{
        $(".intervenantStatut").val("Autres");
    }
});

/**
 *  after change on select devise, displaying devise on another devise
 */

$('body').on('change', '#intervenant_devise', function(e){
        var str = "";
        $( "#intervenant_devise option:selected" ).each(function() {
            str += $( this ).text() + " ";
        });
        $( "#devise2" ).val( str );
        $( "#devise1" ).val( str );
    }).trigger("change");

//after loading modal, chargement du devise selon le type par defaut
/*$("#modalIntervenant").on('shown.bs.modal', function(){
    var str = "";
    $( "#intervenant_devise option:selected" ).each(function() {
        str += $( this ).text() + " ";
    });
    $( "#indevise2" ).val( str );
    $( "#devise1" ).val( str );
});*/


/**
 * get form edit
 */
$('body').on('click', '.btn-edit', function (e) {
    e.preventDefault();

   // main.ajaxloader('show');
    var id = $(this).data('id');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id})
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        if (response.status == 403) {
            $('.modal-body').hide();
            $('#modalIntervenant').modal('show');
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
            $('#modalIntervenant').modal('show');
     //       main.ajaxloader('hide');
        }
    })
})