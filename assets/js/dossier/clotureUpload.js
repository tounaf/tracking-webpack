
// var fileCount = '{{ form.pjClotures|length }}';
// var removeButton = "<button type='button' class='btn btn-danger btn-xs' onclick='removeFile($(this));'><i class='fa fa-times' aria-hidden='true'></i></button>";


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
        $('#cloture_pjClotures_' + fileCount + '_file').trigger('click');
    });

    $("#jsBtnRemove" + fileCount).click(function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    })
    // Once the file is added
    $('#playground_cookiejarbundle_folder_pjClotures_' + fileCount + '_file').on('change', function() {
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
});