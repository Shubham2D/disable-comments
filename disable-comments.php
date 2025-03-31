<?php
/**
 * Plugin Name: Disable Comments Completely
 * Description: Fully disables comments and trackbacks on the entire WordPress site.
 * Version: 1.0
 * Author: Shubham Sawarkar
 * License: MIT
 */

if (!defined('ABSPATH')) exit; // Prevent direct access

// Redirect users trying to access the Comments page
add_action('admin_init', function () {
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from the dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in all post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in admin menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments icon from the admin bar
add_action('admin_bar_menu', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}, 0);