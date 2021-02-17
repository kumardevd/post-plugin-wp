jQuery( document ).ready( function ( $ ) {
    $( '#post-submission-form' ).on( 'submit', function(e) {
        
        e.preventDefault();
        var title = $( '#post-submission-title' ).val();
        var excerpt = $( '#post-submission-excerpt' ).val();
        var content = $( '#post-submission-content' ).val();
        var cat = $( '#cat' ).val();
        var status = 'draft';
 
        var data = {
            title: title,
            excerpt: excerpt,
            content: content,
            status : status
        };
 
        $.ajax({
            method: "POST",
            url: apfajax.ajaxurl,
            data: data,

            success : function( response ) {
                console.log( response );
            },
            fail : function( response ) {
                console.log( response );
            }
 
        });
 
    });
 
} );