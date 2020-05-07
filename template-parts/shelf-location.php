<?php 

$my_account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
$user = wp_get_current_user();
// add not logged in
?>
<div class="shelf-location">
    <h3 class="title">Choose your location</h3>
    <p>Delivery options and speeds many vary depending on your location.</p>
    <?php 

    // get address
    $address = narrow_get_address_from_user();

    if( empty( $address ) ) :

        printf( '<a role="button" href="%s">Sign in to see your addresses</a>', $my_account_link );

    else : 

        printf( '<a class="address" href="%sedit-address/" role="button">
                    <strong>%s</strong>
                    <address>%s, %s</address>
                    <i class="fas fa-edit"></i>
                </a>', $my_account_link, $address['user'], $address['city'], $address['postcode'] );
    ?>

    <?php endif; ?>
</div> <!-- .shelf-location -->

<hr>
<form method="GET" id="shelf-location-enter-zip" class="form-inline" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <i class="fas fa-compass fa-2x"></i>
    <input type="text" name="zip" placeholder="Enter a US Zip Code" autocomplete="zip">
    <input type="hidden" name="action" value="enter-zip-code" />
    <input type="submit" class="btn-primary" value="Enter Zip" />
    <?php // https://codex.wordpress.org/WordPress_Nonces ?>
</form>