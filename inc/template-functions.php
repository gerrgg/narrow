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