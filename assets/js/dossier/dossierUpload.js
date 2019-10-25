
// var fileCount = '{{ form.pjClotures|length }}';
// var removeButton = "<button type='button' class='btn btn-danger btn-xs' onclick='removeFile($(this));'><i class='fa fa-times' aria-hidden='true'></i></button>";


import Routing from "../user/Routing";

$('.add_tag').click(function (e) {
    e.preventDefault();
    var filCount = $(this).data('length');
    createAddFile(filCount);

})
function createAddFile(fileCount)
{
    // grab the prototype template
    var newWidget = $("#filesProto").attr('data-prototype');
    // replace the "__name__" used in the id and name of the prototype
    newWidget = newWidget.replace(/__name__/g, fileCount);

    newWidget = "<div style='display:block'>" + newWidget + "</div>";

    hideStuff = "";
    hideStuff += "<div class='col col-xs-1' id='jsRemove" + fileCount + "' style='display: none;'>";
    hideStuff += "<button type='button' class='btn btn-danger btn-xs' id='jsBtnRemove" + fileCount + "'><i class='fa fa-times' aria-hidden='true'></i></button>";
    hideStuff += "</div>";

    hideStuff += "<div class='col col-xs-11' id='jsPreview" + fileCount + "'>";
    hideStuff += "</div>";

    hideStuff += "<div class='col col-xs-12'>";
    hideStuff += "<button type='button' id='jsBtnUpload" + fileCount + "' class='btn btn-warning'>";
    hideStuff += "<i class='fa fa-plus'></i> documment";
    hideStuff += "</button>";
    hideStuff += "</div>";

    $("#filesBox").append("<div class='row'>" + hideStuff + newWidget + "</div>");

    // On click => Simulate file behaviour
    $("#jsBtnUpload" + fileCount).on('click', function(e){
        $('#dossier_pjDossier_' + fileCount + '_file').trigger('click');
    });

    $("#jsBtnRemove" + fileCount).click(function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    })
    // Once the file is added
    $('#dossier_pjDossier_' + fileCount + '_file').on('change', function() {
        // Show its name
        fileName = $(this).prop('files')[0].name;
        $("#jsPreview" + fileCount).append(fileName);
        // Hide the add file button
        $("#jsBtnUpload" + fileCount).hide();
        // Show the remove file button
        $("#jsRemove" + fileCount).show();
        // Create another instance of add file button and company
        createAddFile(parseInt(fileCount)+1);
    });
}

$(document).ready(function(){
    function removeFile(ob)
    {
        ob.parent().parent().remove();
    }
    // createAddFile(fileCount);
    // fileCount++;
    $('.add_tag').trigger('click');

    $("#pj_dossier_File").on("change", function () {
        var formPj = $("#form-pj-dossier");
        // var data = new FormData(formPj);
        // console.log(this);
        formPj.trigger('submit');
    })

    /**
     * trigger submit when file selected
     */
    var maxupload;
    $('#errorupload').hide();
    $("#form-pj-dossier").on("submit", function () {
        // e.preventDefault();
        $.ajax({
            url : Routing.generate('get_fileuploadMax'),
            async: false,
            success : function(response){
                maxupload = response;
            }
        });
        var fd = new FormData();
        var files = $('#pj_dossier_File')[0].files[0];
        var id = $("#dossier-id").val();
        var idPj = $("#pj_dossier_infoPj").val();
        if(files){
            var taille = $('#pj_dossier_File')[0].files[0].size;
            var tailleOctet = (taille/1024)/1024;
            if(tailleOctet > maxupload){
                $('#errorupload').show();
                return false;
            }else{
                $('#errorupload').hide();
            }

        }
        fd.append('file', files);
        fd.append('pj_dossier_infoPj', idPj);
        $.ajax({
            url: Routing.generate('uploaddossier_file', {id: id}, true),
            type: 'POST',
            enctype: 'multipart/form-data',
            data: fd,
            contentType: false,
            processData: false,
            async: true,
            success: function(response){
                $('#listPjdossier').DataTable().ajax.reload()
            },
        });
        return false;
    })

    /**
     * submit form pj-info
     */
    $("#dossier-form").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($("#dossier-form")[0]);

        var id = $("#dossier-id").val();
        var url = Routing.generate('upload_file', {id: id}, true);

        $.ajax({
            url: url,
            type: 'POST',
            enctype: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
            async: true,
            data: formData,
            success: function (response) {
                $('#listPjdossier').DataTable().ajax.reload(originalJsonData,true)
            }
        })
    })

    /**
     * declencher la submission du formulaire cloture
     */
    $("#button-dossier-for").click(function (e) {
        e.preventDefault();
        $("#button-dossier-submit").trigger("click");
    })

    var roleJury = $('#roolesJury').attr('data-rolesJury');
    // var idd = $(this).data('idcloture');
    var iddossier = $('#iddossier').val();
    /**
     * list pj dossier
     */
    $('#listPjdossier').DataTable({
        "processing": true,
        "serverSide": false,
        "searching": false,
        "paging": true,
        "ajax": {
            "url": Routing.generate('liste_pj_dossier',{'id':iddossier}),
            "type": "POST"
        },
        "sAjaxDataProp": "data",
        "pageLength": 5,
        "orderable": true,

        "columns": [
            {
                "data": "id",
            },
            {"data": "dateAjoutDossier"},
            {"data": "informationPJ"},
            {
                "data": "edit",
                "render": function (data, type, row) {
                    var data = '<a href="'+Routing.generate('download_dossier',{'id':row.id }) +'">'+ row.lien+'</a>';
                    return data;
                }
            },
            { "targets": -1,
                "data": "edit",
                "orderable": false,
                "defaultContent": "",
                "className": 'text-center',
                "render": function (data, type, row) {
                    if(!roleJury) {
                        var data = '<a href="' + Routing.generate('delete_pj', {'id': row.id}) + '" class="text-danger"><i class="icon-trash"></i></a>';
                        return data;
                    }
                }
            },
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
            { "width": "26.5%", "targets": 0, orderable: true },
            { "width": "26.5%", "targets": 1, orderable: true},
            { "width": "26.5%", "targets": 2, orderable: true},
            { "width": "26.5%", "targets": 3, orderable: false},
            { "width": "26.5%", "targets": 4, orderable: false},
        ]
    });
});