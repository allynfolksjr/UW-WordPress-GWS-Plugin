<?php

add_action('admin_menu', 'uw_auth_menu');

function uw_auth_menu(){
  add_options_page( 'UW Groups', 'UW Groups', 'manage_network', 'UW-Groups', 'uw_auth_options');
}


function debug_groups() {
  $current_user = wp_get_current_user();
  $debug = "";
  $debug .= "You are " . $current_user->ID . " | " . $current_user->user_login;
  $debug .= "<h3>Test Groups: Are you in them?</h3><div><ul>";
  $testing_groups = ["u_blogsdev_test", "uw_employees", "u_nikky_employees_and_students"];
  foreach($testing_groups as $group) {
    $debug .= "<li>" . $group . ": ";
    if (in_group($current_user, $group)) {
      $debug .= "<strong>True</strong>";
    } else {
      $debug .= "<em>False</em>";
    }
  }
  $debug .= "</ul>";
  $debug .= "<h3>Your (Selected) Capabilities on this current blog prior to mucking</h3>";
  $testing_roles = ["manage_network", "activate_plugins", "moderate_comments", "edit_published_posts", "edit_posts", "read" ];
  foreach($testing_roles as $role) {
    $debug .= "<li>" . $role . ": ";
    if (current_user_can($role)) {
      $debug .= "<strong>True</strong>";
    } else {
      $debug .= "<em>False</em>";
    }
  }
  $debug .= "<h3>Checking the role options for this blog</h3>";
  if ( ! isset( $wp_roles ) )
    $wp_roles = new WP_Roles();
  foreach(array_keys(($wp_roles->get_names())) as $role) {
     $debug .= "<li>" . $role . "</li>";
  }
  $debug .= "<h3>Your Roles on this current blog after mucking about</h3>";
  $testing_roles = ["manage_network", "activate_plugins", "moderate_comments", "edit_published_posts", "edit_posts", "read" ];
  foreach($testing_roles as $role) {
    $debug .= "<li>" . $role . ": ";
    if (current_user_can($role)) {
      $debug .= "<strong>True</strong>";
    } else {
      $debug .= "<em>False</em>";
    }
  }
  $debug .= "</div>";
  print $debug;
}

function uw_auth_dashboard_widget(){
  wp_add_dashboard_widget('uw_auth_dashboard_widget', 'UW Auth Testing Widget', 'uw_auth_dashboard_widget_function');
}

function uw_auth_dashboard_widget_function(){
  debug_groups();
}
add_action('wp_dashboard_setup', 'uw_auth_dashboard_widget');

function uw_auth_options(){
if ( !current_user_can( 'manage_network' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  debug_groups();
  echo '</div>';
}


?>
