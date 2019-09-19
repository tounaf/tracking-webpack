// import Routing from "../Routing";

import Routing from "../Routing";

require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');
require('datatables.net-bs4');

$('#listSubdossier').DataTable({
    searching : false,
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
});

$(document).ready(function () {

    var $collectionHolder;

// setup an "add a tag" link
    var $addTagButton = $('<button type="button" class="add_tag_link">Add a tag</button>');
    var $newLinkLi = $('<li></li>').append($addTagButton);

    jQuery(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('ul.tags');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagButton.on('click', function(e) {
            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkLi);
        });
    });


    // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
    var $container = $('div#dossier_subDossiers');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find(':input').length;

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $('#addSubDossier').click(function(e) {
        addSubDossier($container);
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addSubDossier($container);
    } else {
        // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    // La fonction qui ajoute un formulaire CategoryType
    function addSubDossier($container) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Catégorie n°' + (index+1))
            .replace(/__name__/g,        index)
        ;
        // console.log($('.col-form-label'));
        //
        // console.log($(template).children(0).remove());
        // $(template).children(0).remove();
        // $('.col-form-label').remove();

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

    // La fonction qui ajoute un lien de suppression d'une catégorie
    function addDeleteLink($prototype) {
        // Création du lien
        var $deleteLink = $('<button href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i>Supprimer</button>');

        // Ajout du lien
        $prototype.append($deleteLink);

        // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

    $('body').on('click','#formSubDossier', function (e) {
        e.preventDefault();
        $(this).ajaxloader('show');
        var id = $("#idSubDossier").val();
        console.log(id);return false
        var data = $(this).serialize();
        var url = Routing.generate('search_dossier',{id: id}, true)
        $.post(url, data,function (data) {

                $('#listSubdossier').DataTable().ajax.reload()
                $("#formSearchDossier").ajaxloader('hide');
            }
        )
    })
    $('input[id^="dossier_subDossiers"]').parent().remove();
    $('input[id^="dossier_subDossiers"]').parent().children(0).hide();
    $('input[id^="dossier_subDossiers"]').hide();

})