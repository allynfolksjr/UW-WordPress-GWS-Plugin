<?php

add_action('network_admin_menu', 'uw_auth_menu');

function uw_auth_menu(){
  add_menu_page( 'UW Groups', 'UW Groups', 'manage_network', 'UW-Groups',
    'uw_auth_home','./' . UWAUTH_PATH . 'img/favicon.ico');
  add_submenu_page('UW-Groups','Settings', 'Settings','manage_network','Settings','uw_auth_settings');
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

function uw_auth_settings() {
  ?>

  <h2>Settings</h2>
  <?php
}


?>
