<?php

add_action('network_admin_menu', 'uw_auth_menu');
add_action('admin_init', 'uw_auth_register_settings');

function uw_auth_menu(){
  add_menu_page( 'UW Groups', 'UW Groups', 'manage_network', 'UW-Groups',
    'uw_auth_home','./' . UWAUTH_PATH . 'img/favicon.ico');
  add_submenu_page('UW-Groups','Settings', 'Settings','manage_network','Settings','uw_auth_settings_page');
  add_submenu_page('UW-Groups','Diagnostics', 'Diagnostics','manage_network',
    'Diagnostics','uw_auth_diagnostics');

}

function uw_auth_home() {
  ?>
  <h1>UW Groups Web Service <=> WordPress Role Sync</h1>
  <p>Highly experimental WordPress Plugin. Not for production use.</p>
  <p>
    <a target="blank" href="https://github.com/allynfolksjr/UW-WordPress-GWS-Plugin/blob/master/README.md">
      Full Documentation
    </a>
  </p>
  <?php
}

function uw_auth_register_settings() {
  if ( ! isset( $wp_roles ) ) {
    $wp_roles = new WP_Roles();
  }
  foreach(array_keys(($wp_roles->get_names())) as $role) {
    register_setting('uw_auth_settings_group','gws_group_role_' . $role);
  }
}


function uw_auth_settings_page() {
  ?>
  <h2>Settings</h2>
  <?php
  uw_auth_settings_form();
}

function uw_auth_settings_form() {
  ?>
  <form method="post" action="options.php">
    <?php
    settings_fields('uw_auth_settings_group');
  }

  ?>
