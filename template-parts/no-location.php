<?php 

$url = get_permalink( get_option('woocommerce_myaccount_page_id') );

?>
<h3 class="title">Choose your location</h3>
<p>Delivery options and speeds many vary depending on your location.</p>
<a role="button" href="<?php echo $url ?>">Sign in to see your addresses</a>
<hr>
<form method="POST" id="no-location-enter-zip" class="form-inline" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <i class="fas fa-compass fa-2x"></i>
    <input type="text" name="zip" placeholder="Enter a US Zip Code" autocomplete="zip">
    <input type="hidden" name="action" value="enter-zip-code" />
    <input type="submit" class="btn-primary" value="Enter Zip" />
</form>