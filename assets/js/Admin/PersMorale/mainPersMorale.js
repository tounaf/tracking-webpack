import 'datatables.net-bs4/css/dataTables.bootstrap4.min.css';
import 'datatables.net-bs4';
import Routing from "../../Routing";

var dataTable = $('#persMorale_table').DataTable({
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
        zeroRecords:     "Aucun &eacute;l&eacute;ment trouv&eacute;",
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
    dataTable.search( this.value ).draw();
});
$(document).ready(function () {
    $('#persMorale_table').DataTable({
        destroy : true,
        searching : false,
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

    });
});
console.log("je suis la");

/**
 * get form creer
 */
$('#showModal').click(function (e) {
    e.preventDefault();
    console.log("log");
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route);
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        $("#exampleModalLongTitle").addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalPersMorale').modal('show');
    })
});

/**
 * get form edit
 */
$('.btn-edit-persMorale').click(function (e) {
    e.preventDefault();
    console.log('tafiditra');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var id = $(this).data('id');
    var url = Routing.generate(route,{'id':id})
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        if (response.status == 403) {
            $('.modal-body').hide();
            $('#modalPersMorale').modal('show');
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
            $("#exampleModalLongTitle").addClass('text-danger').text(title);
            $('.modal-body').html(response);
            $('#modalPersMorale').modal('show');
        }
    })
})


function removeClassStartingWith(node, begin) {
    node.removeClass (function (index, className) {
        return (className.match ( new RegExp("\\b"+begin+"\\S+", "g") ) || []).join(' ');
    });
}

//id pour la suppression
 var persMorale_id;
$(document).on('click', '.delete', function(){
    persMorale_id = $(this).attr('id');
    $('#confirmModal').modal('show');
    $("#exampleModalLongTitle").addClass('text-danger').text(title);
    console.log(persMorale_id);
});

//ok button confirmation suppression
$('#ok_button').on('click', function(){
    console.log(persMorale_id);
    var route = $(this).data('route');
   $.ajax({

        url: Routing.generate(route),
        data: {
            id: persMorale_id
        },
        success: function(data){
            $('#confirmModal').modal('hide');
            location.reload();
        }
    });
    console.log(persMorale_id);
});


/**
 * Get form for delete action
 */
$('.btn-remove').click(function (e) {
    e.preventDefault();
    console.log("attention")
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
                $('#modalPersMorale').modal('show');
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
                $("#exampleModalLongTitle").addClass('text-danger').text(title);
                $('.modal-body').html(response);
                $('#modalPersMorale').modal('show');
            }
        }
    })
})