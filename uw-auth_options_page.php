<?php

add_action('admin_menu', 'uwauth_menu');

function uwauth_menu() {
  add_menu_page('UW Groups Sync', 'UW Groups Sync', 'administrator',
    __FILE__,'uw-auth_settings_page',plugins_url('/images/icon.png', __FILE__));

  add_action( 'admin_init', 'register_uwauth_settings' );
}


function register_uwauth_settings() {
  register_setting( 'uwauth_settings', 'themeNetid' );
  echo "I was registered!";
  echo get_current_blog_id();
}

?>
