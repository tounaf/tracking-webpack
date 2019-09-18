const $ = require('jquery');
import Routing from '../Routing';
import 'jquery.cookie';
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('../jquery.ajaxloader');


var main = $('#auxiliaires-list');

$(document).ready(function () {
    console.log('auxi');
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
            {"data": "user"},
            {"data": "convenu"},
            {"data": "payer"},
            {"data": "reste_payer"},
            {"data": "devise"},
            {"data": "prestation"},
            {"data": "statuts"},
            {
                "targets": -1,
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
        data = ' <button  data-target="#modalPassword" data-title="AJOUT/MODIFICATION" data-route="auxiliaire_edit" class="btn btn-link text-danger btn-edit" data-id="'+row.id+'" type="button"><i class="fa fa-edit"></i></button>\n' +
            '  <button  data-target="#modalPassword" data-title="SUPPRESSION" data-route="auxiliaires_delete" class="btn btn-link text-danger btn-removeAuxi" data-id="'+row.id+'" type="button"><i class="fa fa-trash-o"></i></button>';
        return data;
    }

    // var data  = '<div class="btn-perso" data-toggle="tooltip" data-original-title="Supprimer" data-title="'+row.thematic+'" data-id="'+row.id+'" onclick="deleteRow(this)"><i class="fa fa-remove text-red"></i></div>';
});
$('body').on('click', '.btn-removeAuxi', function (e) {
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
                $('#modalPassword').modal('show');
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
                $('#modalPassword').modal('show');
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
/*
$(function() {
    $('a[data-toggle="tab"]').on('shown', function(e){
        e.preventDefault();
        //save the latest tab using a cookie:
        $.cookie('last_tab', $(e.target).attr('href'));
    });

    //activate latest tab, if it exists:
    var lastTab = $.cookie('last_tab');
    console.log(lastTab);
    if (lastTab) {
        $('ul.nav-tabs').children().removeClass('active');
        $('a[href='+ lastTab +']').parents('li:first').addClass('active');
        $('div.tab-content').children().removeClass('active');
        $(lastTab).addClass('active');
    }
});*/

/**
 * get form on create
 */
$('body').on('click', '#modalCreateAuxiliare', function (e) {
    e.preventDefault();
    console.log("create Intervention");
    //   main.ajaxloader('show');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route);
    // var convenu = $('#intervenant_convenu').val();
    //console.log(convenu);
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        elt.addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalPassword').modal('show');
        //main.ajaxloader('hide');
    })
});
$('body').on('keyup', '#auxiliaires_restePayer', function(e){
    e.preventDefault();
    console.log("reste");
    var convenu = $("input#auxilaires_restePayer").val();
    console.log(convenu);
    if (convenu > 0) {
        $("#auxiliaires_statutIntervenant").val("A Payer");
    } else {
        $("#auxiliares_statutIntervenant").val("Soldé");
    }
});
/*
$('body').on('change', '#intervenant_devise', function(e){
        e.preventDefault();
        console.log("devise");
       var devise = $("#intervenant_devise").text();
       console.log(devise);
           $("#devise2").val(devise);

           $("#devise1").val(devise);

    }); */
//after change on select devise, displaying devise on another devise
$('body').on('change', '#auxiliaires_devise', function(e){
    var str = "";
    $( "#auxilaires_devise option:selected" ).each(function() {
        str += $( this ).text() + " ";
    });
    $( "#dvise2" ).val( str );
    $( "#dvise1" ).val( str );
}).trigger("change");

//after loading modal, chargement du devise selon le type par defaut
$("#modalPassword").on('shown.bs.modal', function(){
    console.log("afterloading modal");
    var str = "";
    $( "#auxiliaires_devise option:selected" ).each(function() {
        str += $( this ).text() + " ";
    });
    $( "#dvise2" ).val( str );
    $( "#dvise1" ).val( str );
});

/*$("input").keyup(function(){
    $("input").css("background-color", "pink");
});*/

/**
 * get form edit
 */
$('body').on('click', '.btn-edit', function (e) {
    e.preventDefault();

    // main.ajaxloader('show');
    var id = $(this).data('id');
    console.log("edit"+id);
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id})
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
            //       main.ajaxloader('hide');
        }
    })
})