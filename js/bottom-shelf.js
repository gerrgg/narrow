jQuery(document).ready(function( $ ){
    var shelf = {

        wrapper: $('#bottom-shelf'),
        content: $('#bottom-shelf .content'),
        close_button: $('#bottom-shelf .header'),
        toggle: $('.open-shelf'),
        body: $('#page'),

        init: function(){
           shelf.toggle.click( this.open )
           this.close_button.click( this.close )
        },

        open: function( e ){
            let action = $(this).attr('data-action');

            shelf.get_content(action);

            if( shelf.wrapper.is(":hidden") ){
                shelf.body.addClass('panel-open');
                shelf.wrapper.addClass('show');
            }
            // shelf.wrapper.addClass('show');
        },

        get_content: function( action, args = [] ){

            let data = { action: action, data: args }

            console.log( data );
            
            $.post(wp_ajax.url, data, function( response ){
                console.log( response );

                shelf.content.html( response );
            });
        },

        close: function( e ){
            if( shelf.wrapper.is(":visible") ){
                console.log( 'close' );

                shelf.body.removeClass('panel-open');
                shelf.wrapper.removeClass('show');
            }
        }


    }

    shelf.init();
});