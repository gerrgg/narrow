<?php 

$url = get_permalink( get_option('woocommerce_myaccount_page_id') );

?>
<h3 class="title">Choose your location</h3>
<p>Delivery options and speeds many vary depending on your location</p>
<a role="button" href="">Sign in</a>
<hr>
<div class="form-inline">
    <i class="fas fa-compass fa-2x"></i>
    <input type="text" placeholder="Enter a US Zip Code">
    <input type="submit" class="btn-primary" value="Enter" />
</div>