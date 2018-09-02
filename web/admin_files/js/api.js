'use strict';

$(document).ready(function () {

    $('.ajax-delete').on('click', function (e) {

        let entity = $(this).data('entity');
        let objectId = $(this).data('objectid');
        let message = $(this).data('confirmmessage');
        let deleteUrl = $(this).data('deleteurl');
        let deleteConfirm = confirm(message);

        if (deleteConfirm === true) {
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: {
                    entity: entity,
                    id: objectId
                },
                success: function (response) {
                    $('#object-id-' + objectId).remove();
                },
                error: function (response) {
                    console.log(response);
                }
            });

        }
    })
});

function ajaxDeleteAction() {
    
}