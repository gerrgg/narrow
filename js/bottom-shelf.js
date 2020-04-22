jQuery(document).ready(function( $ ){
    var shelf = {

        wrapper: $('#bottom-shelf'),
        content: $('#bottom-shelf .content'),
        close_button: $('#bottom-shelf span.close'),
        toggle: $('.open-shelf'),
        body: $('#page'),

        init: function(){
           shelf.toggle.click( this.open )
           this.close_button.click( this.close )
        },

        open: function( e ){
            if( shelf.wrapper.is(":hidden") ){
                console.log( 'open' );

                shelf.body.addClass('panel-open');
                shelf.wrapper.addClass('show');
            }
            // shelf.wrapper.addClass('show');
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