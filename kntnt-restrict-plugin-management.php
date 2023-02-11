<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Restrict Plugin Management
 * Plugin URI:        https://github.com/Kntnt/kntnt-restrict-plugin-management
 * Description:       Restricts plugin management to the first registered user.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires PHP:      5.3
 */

function is_first_registered_user() {
    $users = get_users(array('orderby' => 'registered'));
    $first_user = $users[0];
    return get_current_user_id() === $first_user->ID;
}

function restrict_plugins_pages() {
    if (!is_first_registered_user()) {
        wp_die(__('You do not have permission to manage plugins.'));
    }
}

// Hide the plugins menu for non-first registered users
add_action('admin_menu', function () {
    if (!is_first_registered_user()) {
        remove_menu_page('plugins.php');
    }
});

// Prevent non-first registered users from accessing plugin management pages
add_action('load-plugins.php', 'restrict_plugins_pages');
add_action('load-update.php', 'restrict_plugins_pages');
add_action('load-update-core.php', 'restrict_plugins_pages');
