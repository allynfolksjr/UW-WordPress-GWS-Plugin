<?php
/*
Plugin Name: UW <-> GWS Sync Plugin
Plugin URI: http://staff.washington.edu/nikky
Description: Authentication and restriction functionality
Version: 0.1.0
Author: Nikky Southerland; UW Information Technology
License: UW Owned
*/

// This gets you the URL of the plugin for super awesome things
define( 'UWAUTH_PATH', plugin_dir_url(__FILE__) );

// Include the options page
// include 'uw-auth_options_page.php';
// include 'uwhf_lib.php';

// $debug = true;
// if ($debug) {
error_reporting(E_ALL);
// }




function nothing(){}

function check_for_restrictions() {
	if (check_blog_for_protection()) {
		$current_user = wp_get_current_user();
		if ( (0 == $current_user->ID) && !is_admin() ) {
			// 402 - Not logged in
			add_uwauth_headers();
			echo "This page is protected, but you're not logged in";
			echo "You are not authorized to view this page. Please log in now.";
			?>
			<a href="<?php echo wp_login_url( get_permalink() ); ?>">Login</a> <?php
			echo "</body>";
			exit;
		} else {
			if (in_array($current_user->user_login,get_authorized_users_for_page())) {
				echo "Cool, you're authorized, cya";
			} else {
				// echo "$current_user->user_login not found in auth table! Cya!";
				echo "You are not authorized to view this page.";
				print "<br/>";
				print_r (get_authorized_users_for_page());
				print "<br/>";
				print in_array($current_user->user_login, get_authorized_users_for_page(), true);
				echo get_stylesheet();
				print "<br/>";
				echo UWAUTH_PATH;
				exit;
			}
		}
	}
}

function check_blog_for_protection() {
	return true;
}

function get_authorized_users_for_page() {
	return ["webtest", "nikky@washington.edu"];
}

function add_uwauth_headers() {
	echo "<html><head><title>Protected Site</title><link rel=\"stylesheet\" href=\"";
	echo UWAUTH_PATH;
	echo "css/bootstrap.min.css\"/>";
	echo "</head><body>";
}

function parse_raw_gws_string($string) {
	$group_array = [];
	print_r(explode(";",$string));
	foreach(explode(";",$string) as $group) {
		$group_array[] = explode(":",$group)[4];
	}
	return $group_array;
}

function get_gws_groups($user) {
	return get_user_meta($user->ID, '_gws_groups');
}

function user_gws_groups($user_login, $user) {
	if ($_SERVER['gws_groups']) {
		$groups = parse_raw_gws_string($_SERVER['gws_groups']);
	} else {
		$groups = "nil";
	}
	update_user_meta($user->ID, '_gws_groups', $groups);
}

function user_last_login($user_login, $user) {
	update_user_meta($user->ID, '_last_login', time() );
}

function user_uwauth_profile($user) {
	print "<h3>Your UW Groups WordPress Knows About</h3>";
	print_r(get_gws_groups($user));
}

// http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
add_action('parse_request', 'nothing');
add_action('wp_login', 'user_gws_groups', 10, 2);
add_action('wp_login', 'user_last_login', 10, 2);
add_action('show_user_profile', 'user_uwauth_profile');

?>
