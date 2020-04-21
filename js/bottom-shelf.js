jQuery(document).ready(function( $ ){
    var bottom_shelf = {

        toggle: $('#user-location-prompt'),
        wrapper: $('#bottom-shelf'),
        body: $('#page'),

        init: function(){
            $('#user-location-prompt').click( this.open );
        },

        open: function( e ){
            
            bottom_shelf.body.addClass('panel-open')
            bottom_shelf.wrapper.addClass('show').attr('aria-open', true);

        },

        close: function( e ){
            if( bottom_shelf.wrapper.attr( 'aria-open' ) == 'true' ){
                console.log( 'close' )
            }
        }

    }

    bottom_shelf.init();
});