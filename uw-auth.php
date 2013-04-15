<?php
/*
Plugin Name: UW <-> GWS Sync Plugin
Plugin URI: http://staff.washington.edu/nikky
Description: Map WordPress User Roles to UW Groups
Version: 0.1.0
Author: Nikky Southerland; UW Information Technology
License: UW Owned
*/




// This gets you the URL of the plugin for super awesome things
define( 'UWAUTH_PATH', plugin_dir_url(__FILE__) );


include 'uw-auth_options_page.php';
include 'uw-auth_diagnostics_page.php';

/*
Takes a raw gws_group string and returns an
array of groups.
*/

function parse_raw_gws_string($string) {
	$group_array = [];
	foreach(explode(";",$string) as $group) {
		$group_array[] = explode(":",$group)[4];
	}
	return $group_array;
}


/*
Retrieves gws_groups stored in WP database for specified user.
Returns array if true; false if no groups found.
*/

function get_gws_groups($user) {
	$groups = get_user_meta($user->ID, '_gws_groups');
	if ($groups) {
		// Not sure why WP is serializing it in an array, but okay
		return $groups[0];
	} else {
		return false;
	}
}
/*
Returns true if user is in group; false if not.
*/
function in_group($user, $group) {
	$groups = get_gws_groups($user);
	return in_array($group, $groups);
}




/*
Grabs the gws_groups string from shib and returns array of groups if true;
false if not present.
*/

function user_gws_groups($user_login, $user) {
	if ($_SERVER['gws_groups']) {
		$groups = parse_raw_gws_string($_SERVER['gws_groups']);
	} else {
		$groups = false;
	}
	update_user_meta($user->ID, '_gws_groups', $groups);
}

/*
Returns 'true' if requested role
is a higher level; otherwise returns false
*/

function role_priority($current_role, $requested_role) {
	$roles = ['subscriber','contributor','author','editor','administrator'];
	if (array_search($current_role,$roles) > array_search($requested_role,$roles)) {
		return true;
	} else {
		return false;
	}
}


/*
Upon login, will load all of a user's blogs and map their current groups
to any defined roels on blogs.
*/

function map_groups_to_roles() {
	$current_user = wp_get_current_user();
	if ( is_multisite() ) {
		$user_blogs = get_blog_ids_of_user_blogs($current_user);
		foreach($user_blogs as $blog) {
			switch_to_blog($blog);
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
			foreach(array_keys(($wp_roles->get_names())) as $role) {
				$group = get_option('gws_group_role_' . $role);
				if ($group) {
					wp_update_user(array ('ID' => $current_user->ID,'role' => $role));
				} else {
						// Not mapped
				}
			}
			restore_current_blog();
		}
	} else {
		// Pull directly from main blog
	}
}

/*
Return an array of the blog IDs that a user is a member of
*/

function get_blog_ids_of_user_blogs($current_user) {
	$user_blogs = [];
	$user_blog_objects = get_blogs_of_user($current_user->ID);
	foreach ($user_blog_objects as $blog) {
		$user_blogs[] = $blog->userblog_id;
	}
	return $user_blogs;
}

/*
Displays information about groups user is in on profile page.
*/

function user_uwauth_profile($user) {
	print "<h3>Your UW Groups WordPress Knows About</h3>";
	$groups = get_gws_groups($user);
	if ($groups) {
		print print_array_as_html_list($groups);
	} else {
		print "<p>This user is not known in any current groups.</p>";
	}
}

/*
Turns array into UL list
*/

function print_array_as_html_list($array) {
	$list = "<ul>";
	foreach($array as $member) {
		$list .= "<li>" . sanitize_text_field($member) . "</li>";
	}
	$list .= "</ul>";
	return $list;
}

// http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
// add_action('parse_request', 'nothing');
add_action('wp_login', 'user_gws_groups', 2, 2);
add_action('wp_login', 'map_groups_to_roles', 4);
add_action('show_user_profile', 'user_uwauth_profile');
add_action('edit_user_profile', 'user_uwauth_profile');



function nothing(){}

// function check_for_restrictions() {
// 	if (check_blog_for_protection()) {
// 		$current_user = wp_get_current_user();
// 		if ( (0 == $current_user->ID) && !is_admin() ) {
// 			// 402 - Not logged in
// 			add_uwauth_headers();
// 			echo "This page is protected, but you're not logged in";
// 			echo "You are not authorized to view this page. Please log in now.";
//
//		<a href="<?php echo wp_login_url( get_permalink() ); ">Login</a> <?php
// 			echo "</body>";
// 			exit;
// 		} else {
// 			if (in_array($current_user->user_login,get_authorized_users_for_page())) {
// 				echo "Cool, you're authorized, cya";
// 			} else {
// 				// echo "$current_user->user_login not found in auth table! Cya!";
// 				echo "You are not authorized to view this page.";
// 				print "<br/>";
// 				print_r (get_authorized_users_for_page());
// 				print "<br/>";
// 				print in_array($current_user->user_login, get_authorized_users_for_page(), true);
// 				echo get_stylesheet();
// 				print "<br/>";
// 				echo UWAUTH_PATH;
// 				exit;
// 			}
// 		}
// 	}
// }

// function check_blog_for_protection() {
// 	return true;
// }

// function get_authorized_users_for_page() {
// 	return ["webtest", "nikky@washington.edu"];
// }

// function add_uwauth_headers() {
// 	echo "<html><head><title>Protected Site</title><link rel=\"stylesheet\" href=\"";
// 	echo UWAUTH_PATH;
// 	echo "css/bootstrap.min.css\"/>";
// 	echo "</head><body>";
// }

?>
