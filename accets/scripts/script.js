// Shorthand for $( document ).ready()
$(function() {
    console.log( "ready!" );

    $("form#CreateExtensionForm").validate();

    // Create Extention
    $('#CreateExtension').click(function (event) {
        event.preventDefault();
        $('#CreateExtensionForm-wrapper').toggle();
        return false;
    });

    $('button.cancel-btn').click(function () {
        $('#CreateExtensionForm-wrapper').toggle();
        $( '.alert' ).hide();
        return false;
    });

    $( "form#CreateExtensionForm").submit(function( event ) {
        event.preventDefault();
        var $form = $( this ),
            url = $form.attr( "action" );

        var posting = $.post( url, { action: "createExtension", data: $form.serialize() } );
        // Put the results in a div
        posting.done(function( data ) {
            data = $.parseJSON(data);
            var message = data.message;
            if(data.success) {
                $( '.alert' ).removeClass('alert-danger').addClass('alert-success').show();
                $( '.alert strong' ).text( message );
                location.reload();
            } else {
                $( '.alert' ).removeClass('alert-success').addClass('alert-danger').show();
                $( '.alert strong' ).text( message );
            }
        });
    });

    // Handle default options
    $( "table.CreateExtensionDefaultsTable tr td:last-child button").click(function () {
        var $form = $(this).parents("tr").find('input:text, input:checkbox, select').serialize(),
            extensionName = $(this).val();
        console.log($form);
        var posting = $.post( 'controller.php', { action: "createExtension", data: 'extensionName='+extensionName+'&'+ $form } );
        posting.done(function( data ) {
            data = $.parseJSON(data);

            var message = data.message;

            if(data.success) {
                $( '.alert' ).removeClass('alert-danger').addClass('alert-success').show();
                $( '.alert strong' ).text( message );
                location.reload();
            } else {
                $( '.alert' ).removeClass('alert-success').addClass('alert-danger').show();
                $( '.alert strong' ).text( message );
            }
        });
    });

    $( "#directoryObjects td:last-child form").submit(function (event) {
        event.preventDefault();
        var $form = $(this);
        var posting = $.post( 'controller.php', { action: "deleteExtension", data: $form.serialize() } );
        posting.done(function( data ) {
            data = $.parseJSON(data);

            var message = data.message;

            if(data.success) {
                $( '.alert' ).removeClass('alert-danger').addClass('alert-success').show();
                $( '.alert strong' ).text( message );
                location.reload();
            } else {
                $( '.alert' ).removeClass('alert-success').addClass('alert-danger').show();
                $( '.alert strong' ).text( message );
            }
        });
    });

    $( "form#CreateUserForm").submit(function( event ) {
        event.preventDefault();
        var $form = $( this ),
            url = $form.attr( "action" );

        var posting = $.post( url, { action: "editUser", data: $form.serialize() } );

        posting.done(function( data ) {
            data = $.parseJSON(data);

            var message = data.message;

            console.log(data);
            if(data.success) {
                $( '.alert' ).removeClass('alert-danger').addClass('alert-success').show();
                $( '.alert strong' ).text( message );
                location.reload();
            } else {
                $( '.alert' ).removeClass('alert-success').addClass('alert-danger').show();
                $( '.alert strong' ).text( message );
            }
        });
    });
});