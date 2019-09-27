
// var fileCount = '{{ form.pjClotures|length }}';
var removeButton = "<button type='button' class='btn btn-danger btn-xs' onclick='removeFile($(this));'><i class='fa fa-times' aria-hidden='true'></i></button>";
function removeFile(ob)
{
    ob.parent().parent().remove();
}

$('.add_tag').click(function () {
    console.log("haaa");
    var filCount = $(this).data('length');
    console.log(filCount);
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
    hideStuff += removeButton;
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
        $('#playground_cookiejarbundle_folder_pjClotures_' + fileCount + '_file').trigger('click');
    });

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
    // createAddFile(fileCount);
    // fileCount++;
});