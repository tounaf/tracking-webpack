const $ = require('jquery');

import Routing from './Routing';

import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.min';

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

$('#showModalPassword').click(function () {

    $('#modalPassword').modal('show');
    var titleModal = $("#exampleModalLongTitle").data('title');
    var elt = $("#exampleModalLongTitle");
    removeClassStartingWith(elt,'alert')
    $("#exampleModalLongTitle").addClass('text-danger').text(titleModal);
    $('#email_user').val('');
    $('.modal-body').show();
    $('.modal-footer').show();
})
$('#buttonResetting').click(function (e) {
    e.preventDefault();
    var email = $('#email_user').val();
    $.ajax({
        url: Routing.generate('resetting_password_user'),
        type: 'POST',
        data: {
            email: email
        },
        success: function (data) {
            var elt = $('#exampleModalLongTitle');
            if (data.status == 401) {
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
            }
            if (data.status == 200) {

                removeClassStartingWith(elt,'alert')
                elt.removeClass('text-danger').addClass('alert alert-'+data.type).text(data.message);
                $('.modal-body').toggle();
                $('.modal-footer').toggle();
            }
            $('.modal-body').attr('display','none');
            $('.modal-body').prop('display','none');
        }
    })
})