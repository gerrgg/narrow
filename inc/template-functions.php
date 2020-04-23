<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package narrow
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function narrow_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'narrow_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function narrow_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'narrow_pingback_header' );

/**
 * Displays the site logo as an <a> tag for now.
 */
function narrow_mobile_menu_button(){
	$url = '#';
	printf( '<div class="mobile-menu-button">	
				<a href="%s">
					<i class="fas fa-bars"></i>
				</a>
			</div>', $url);
}
add_action( 'narrow_header_top_bar_left', 'narrow_mobile_menu_button', 1 );

/**
 * Displays the site logo as an <a> tag for now.
 */
function narrow_header_logo(){
	echo '<div class="site-branding">';
	if ( is_front_page() && is_home() ) :
		?>
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php
	else :
		?>
		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php
	endif;
	echo '</div><!-- .site-branding -->';
}

add_action( 'narrow_header_top_bar_left', 'narrow_header_logo', 5 );

/**
 * Shows the site title in the header if there is one
 */
function narrow_header_description(){
	$narrow_description = get_bloginfo( 'description', 'display' );
	if ( $narrow_description || is_customize_preview() ) :
		?>
		<p class="site-description"><?php echo $narrow_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif;
}

add_action( 'narrow_header_top_bar_left', 'narrow_header_description', 10 );

/**
 * Shows a link to login/register if user not logged in, otherwise account information
 */
function narrow_header_account_link(){
	$url = get_permalink( get_option('woocommerce_myaccount_page_id') );

	$message = ( is_user_logged_in() ) ? narrow_get_account_link_message() : 'Sign In';

	printf( '<div class="account-link">	
				<a href="%s">
					%s
				</a>
			</div>', $url, $message);
}

add_action( 'narrow_header_top_bar_right', 'narrow_header_account_link', 5 );


function narrow_get_account_link_message(){
	$user = wp_get_current_user();
	$message = ( isset( $user->data->ID ) ) ? 'Hello, ' . ucfirst( $user->display_name ) . '!' : 'Sign in';

	return $message;
}

/**
 * Link to cart
 */
function narrow_header_cart_link(){
	$url = get_permalink( get_option('woocommerce_cart_page_id') );

	printf( '<div class="cart-link">	
				<a href="%s">
					<i class="fas fa-shopping-cart"></i>
				</a>
			</div>', $url);
}
add_action( 'narrow_header_top_bar_right', 'narrow_header_cart_link', 10 );

function narrow_mobile_menu_header(){
	$url = get_permalink( get_option('woocommerce_myaccount_page_id') );
	$message = narrow_get_account_link_message();

	printf( '<header>
				<a href="%s" role="button">
					<i class="fas fa-user-circle"></i>
					<span>%s</span>
				</a>
			</header>', $url, $message );
}

add_action( 'narrow_mobile_menu', 'narrow_mobile_menu_header', 1 );



function narrow_mobile_account_menu(){

	wp_nav_menu(
		array(
			'theme_location' => 'menu-2',
			'menu_id'        => 'primary-menu',
		)
	);
	
}
add_action( 'narrow_mobile_menu', 'narrow_mobile_account_menu', 5 );

function narrow_mobile_2nd_menu(){

	wp_nav_menu(
		array(
			'theme_location' => 'menu-3',
			'menu_id'        => 'primary-menu',
		)
	);
	
}
add_action( 'narrow_mobile_menu', 'narrow_mobile_2nd_menu', 5 );

add_action( 'woocommerce_before_account_navigation', 'narrow_myaccount_navigation_label', 5 );
function narrow_myaccount_navigation_label(){
	printf( "<h3>Account links</h3>" );
}

add_action( 'narrow_after_header_before_content', 'narrow_add_address_prompt', 10 );
function narrow_add_address_prompt(){
	/**
	 * Checks if user is logged in or has login cookie, and gets html accordingly
	 */
	$user = wp_get_current_user();
	
	if( ! $user->ID && ! isset( $_COOKIE['narrow_address'] ) ){
		$msg = "Select a location to see product availablity";
	} else {
		$cookie_address = json_decode( stripslashes( $_COOKIE['narrow_address'] ) );

		$address = array(
			'user' => ( ! $user->ID ) ? ' you at ' : $user->displayname,
			'city' => ( ! $user->ID ) ? $cookie_address->City : get_user_meta( $user->ID, 'shipping_city', true ),
			'postcode' => ( ! $user->ID ) ? $cookie_address->ZipCode : get_user_meta( $user->ID, 'shipping_postcode', true ),
		);

		$msg = sprintf( "Deliver to %s - %s %s", 
			$address['user'], $address['city'], $address['postcode']
		);
	}

	?>
	<div id="user-location-prompt" class="open-shelf" data-action="no-location">
		<i class="fas fa-compass"></i>
		<span><?php echo $msg ?></span>
	</div>
	<?php
}


add_action( 'wp_ajax_no-location', 'narrow_choose_your_location' );
add_action( 'wp_ajax_nopriv_no-location', 'narrow_choose_your_location' );

function narrow_choose_your_location(){
	// Add conditon for getting non-logged in template
	ob_start();
	get_template_part( 'template-parts/no', 'location' );
	$html = ob_get_clean();
	echo $html;
	wp_die();
}

add_action( 'admin_post_enter-zip-code', 'narrow_enter_zip_code' );
add_action( 'admin_post_nopriv_enter-zip-code', 'narrow_enter_zip_code' );

function narrow_enter_zip_code(){
	/**
	 * If the user is logged in, her information is updated via WP_User, if not logged in store in cookie.
	 */

	 // get user and zip
	$user = wp_get_current_user();
	$zip = $_REQUEST['zip'];
	
	if( $user->ID ){
		// logged in
		$address = narrow_get_zip_code_json( $zip );
		update_user_meta( $user->ID, 'shipping_postcode', $zip );
		update_user_meta( $user->ID, 'shipping_city', $address['City'] );
		update_user_meta( $user->ID, 'shipping_state', $address['State'] );
	} else {
		// not logged in
		$address = narrow_get_zip_code_json( $zip, false );
		echo narrow_inline_cookie_functions();
		?>
		<script> 
			console.log( "<?php echo addslashes($address); ?>" );
			setCookie( 'narrow_address', "<?php echo addslashes($address); ?>", 7 );
		</script>
		<?php
	} 

	// wp_redirect('/');
}

function narrow_get_zip_code_json( $zip, $convert = true ){
	$api_key = "JT6X1PYK9O53Y1WIMLU3";
	$request_url = sprintf( "http://api.zip-codes.com/ZipCodesAPI.svc/1.0/QuickGetZipCodeDetails/%s?key=%s", $zip, $api_key);
	$clean_json = narrow_clean_unseen_chars( file_get_contents($request_url) );

	if( ! $convert ) return $clean_json;
	
	$address = json_decode( $clean_json, true );

	return ( isset( $address['Error'] ) ) ? false : $address;
}

function narrow_clean_unseen_chars( $str ){
	//https://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok

	// This will remove unwanted characters. Check http://www.php.net/chr for details
	for ($i = 0; $i <= 31; ++$i) { 
		$str = str_replace(chr($i), "", $str); 
	}
	
	$str = str_replace(chr(127), "", $str);

	// This is the most common part
	// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
	// here we detect it and we remove it, basically it's the first 3 characters 

	if (0 === strpos(bin2hex($str), 'efbbbf')) {
		$str = substr($str, 3);
	}
	return $str;
}

