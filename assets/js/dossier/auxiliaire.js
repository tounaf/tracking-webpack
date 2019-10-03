const $ = require('jquery');
import Routing from '../Routing';
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('../jquery.ajaxloader');


var main = $('#auxiliaires-list');

$(document).ready(function () {
    var table = $('#auxiliaires-listActuel').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": Routing.generate('liste_auxiliaireActuel'),
            "type" : "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 10,
        "orderable": false,
        "columns": [
            {"data": "nomPrenom", "orderable":true},
            {"data": "prestation", "orderable":true},
            {"data": "convenu", "orderable":true},
            {"data": "payer", "orderable":true},
            {"data": "reste_payer", "orderable":true},
            {"data": "devise", "orderable":true},
            {
                "data": "filename",
                "render": function (data, type, row) {
                    if(data != null){
                        var data = '<a href="'+Routing.generate('download_pjauxiliaire',{'id':row.id }) +'"> fichier: '+row.filename+'</a>';
                    }
                    else{
                        var data = 'pas de fichier joint';
                    }
                    return data;
                },
                "orderable":true
            },
            {"data": "statuts", "orderable":true},
            {
                "targets": -1,
                "data": "edit",
                "orderable": false,
                "defaultContent": "",
                "className": 'text-center',
                "render": editRow
            },
        ],
        "autoWidth": false,
        responsive: true,
        bLengthChange: false,
        info: false,
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
    });
    var table = $('#auxiliaires-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": Routing.generate('liste_auxiliaire'),
            "type" : "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 10,
        "orderable": true,
        "columns": [
            {"data": "nomPrenom"},
            {"data": "prestation"},
            {"data": "convenu"},
            {"data": "payer"},
            {"data": "reste_payer"},
            {"data": "devise"},
            {
                "data": "filename",
                "render": function (data, type, row) {
                    if(data != null){
                        var data = '<a href="'+Routing.generate('download_pjintervenant',{'id':row.id }) +'"> fichier: '+row.filename+'</a>';
                    }
                    else{
                        var data = 'pas de fichier joint';
                    }
                    return data;
                }
            },
            {"data": "statuts"},
            {
                "targets": -1,
                "data": "edit",
                "orderable": false,
                "defaultContent": "",
                "className": 'text-center',
                "render": editRow
            },
        ],
        "autoWidth": false,
        responsive: true,
        bLengthChange: false,
        info: false,
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
    });
    function editRow(data, type, row) {
        data = ' <button  data-target="#modalAuxi" data-title="MODIFICATION" data-route="auxiliaires_edit" class="btn-editAuxi" data-id="'+row.id+'" type="button"><i class="icon-edit"></i></button>\n' +
            '  <button  data-target="#modalAuxi" data-title="SUPPRESSION" data-route="auxiliaires_delete" class="btn-removeAuxi" data-id="'+row.id+'" type="button"><i class="icon-trash"></i></button>';
        return data;
    }
});
$('body').on('click', '.btn-removeAuxi', function (e) {
    e.preventDefault();
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
                $('#modalAuxi').modal('show');
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
                $('#modalAuxi').modal('show');
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
$('body').on('click', '#modalCreateAuxi', function (e) {
    e.preventDefault();
    //   main.ajaxloader('show');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var dossierId = $(this).data('dossier');
    var url = Routing.generate(route, {id: dossierId},true);
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        elt.addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalAuxi').modal('show');
        //main.ajaxloader('hide');
    })
});

$('#modalAuxi').on('blur keyup mouseout', '#auxiliaires_restePayer', function(event){
    event.preventDefault();
    var convenu = $("#modalAuxi #auxiliaires_restePayer").val();
    if (convenu != 0){
        $(".auxiStatut").val("A Payer");
    } else if (convenu == 0){
        $(".auxiStatut").val("Soldé");
    }
    else{
        $(".auxiStatut").val("Autres");
    }
});

/**
 *  after change on select devise, displaying devise on another devie
 */
$('body').on('change', '#auxiliaires_deviseAuxiConv', function(e){

    var str = "";
    $( "#auxiliaires_devise option:selected" ).each(function() {
        str += $( this ).text() + " ";
    });
    $( "#auxiliaires_deviseAuxiPayer" ).val( str );
    $( "#auxiliaires_deviseAuxiPayer" ).val( str );
    console.log(str);
}).trigger("change");

//after loading modal, chargement du devise selon le type par defaut
$("#modalAuxi").on('shown.bs.modal', function(){
    var str = "";
    $( "#auxiliaires_devise option:selected" ).each(function() {
        str += $( this ).text() + " ";
    });
    $( "#devise2Auxi" ).val( str );
    $( "#devise1Auxi" ).val( str );
});

/**
 * get form edit
 */
$('body').on('click', '.btn-editAuxi', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id})
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        if (response.status == 403) {
            $('.modal-body').hide();
            $('#modalAuxi').modal('show');
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
            $('#modalAuxi').modal('show');
            //       main.ajaxloader('hide');
        }
    })
})


