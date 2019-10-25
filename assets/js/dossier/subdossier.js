// import Routing from "../Routing";

import Routing from "../Routing";

require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');
require('../Fonction');

var main = $('.main');
$('#listSubdossier').DataTable({
    searching : false,
    destroy : true,
    bLengthChange: false,
    info: false,
    pageLength: 10,
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
          { "width": "18%", "targets": 0, orderable: true },
          { "width": "77%", "targets": 1, orderable: true},
          { "width": "5%", "targets": 2, orderable: false},
          
        ]
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
$(document).ready(function () {

    var $collectionHolder;

// setup an "add a tag" link
    var $addTagButton = $('<button type="button" class="add_tag_link">Add a tag</button>');
    var $newLinkLi = $('<li></li>').append($addTagButton);

    jQuery(document).ready(function () {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('ul.tags');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagButton.on('click', function (e) {
            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkLi);
        });
    });


    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#dossier_subDossiers');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#addSubDossier').click(function (e) {
        addSubDossier($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addSubDossier($container);
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function () {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addSubDossier($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        if ($container.length) {
            var template = $container.attr('data-prototype')
                .replace(/__name__label__/g, 'Catégorie n°' + (index + 1))
                .replace(/__name__/g, index)
            ;
            // On crée un objet jquery qui contient ce template
            var $prototype = $(template);

            // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
            addDeleteLink($prototype);

            // On ajoute le prototype modifié à la fin de la balise <div>

            // $('.modal-body').html($container.append($prototype));
            $container.append($prototype);

            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;
        }

    }

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<button href="#" class="btn btn-danger remove-fild-sub-dossier"><i class="fa fa-trash-o"></i>Supprimer</button>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function (e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    $('body').on('click', '#formSubDossier', function (e) {
        console.log("formsub");
        e.preventDefault();
        $(this).ajaxloader('show');
        var id = $("#idSubDossier").val();
        var data = $(this).serialize();
        var url = Routing.generate('search_dossier', {id: id}, true)
        $.post(url, data, function (data) {

                $('#listSubdossier').DataTable().ajax.reload()
                $("#formSearchDossier").ajaxloader('hide');
            }
        )
    })
    $('input[id^="dossier_subDossiers"]').parent().remove();
    $('.remove-fild-sub-dossier').remove();
    $('input[id^="dossier_subDossiers"]').parent().children(0).hide();
    $('input[id^="dossier_subDossiers"]').hide();

})
/**
 * get form on create
 */
$('body').on('click', '#createSubDossier', function (e) {
    console.log("modalCreate");
    e.preventDefault();
    main.ajaxloader('show');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var dossierId = $(this).data('dossier');
    console.log("route" + route + "title" + title + "dosssId" + dossierId);
    var url = Routing.generate(route, {id: dossierId},true);
    // var convenu = $('#intervenant_convenu').val();
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        removeClassStartingWith(elt, 'alert');
        $('.modal-body').html(response);
        elt.addClass('text-danger').text(title);
        $('.modal-body').show();
        $('#modalCreateSub').modal('show');
        main.ajaxloader('hide');
    })
})


/**
 * get form edit
 */
$('body').on('click', '.edit_subD', function (e) {
    e.preventDefault();
console.log("edit");
    main.ajaxloader('show');
    var id = $(this).data('id');
    var route = $(this).data('route');
    var title = $(this).data('title');
    var url = Routing.generate(route,{'id':id}, true)
    $.get(url,function (response) {
        var elt = $('#exampleModalLongTitle');
        if (response.status == 403) {
            $('.modal-body').hide();
            $('#modalCreateSub').modal('show');
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
            $('#modalCreateSub').modal('show');
            main.ajaxloader('hide');
        }
    })
})