<?php
function ats_plugin_work_custom_post_type() {
	$labels = array(
		'name' 				=> __('Works', 'ats_plugin'),
		'singular_name' 	=> __('Work', 'ats_plugin'),
		'add_new' 			=> __('Add New', 'ats_plugin'),
		'add_new_item' 		=> __('Add New Work', 'ats_plugin'),
		'edit_item' 		=> __('Edit Work', 'ats_plugin'),
		'new_item' 			=> __('New Work', 'ats_plugin'),
		'view_item' 		=> __('View Work', 'ats_plugin'),
		'search_items' 		=> __('Search Works', 'ats_plugin'),
		'not_found' 		=> __('No works found', 'ats_plugin'),
	);

	$capabilities = array(
		'edit_post' 			=> 'edit_work',
		'edit_posts' 			=> 'edit_works',
		'edit_others_posts' 	=> 'edit_others_works',
		'publish_posts' 		=> 'publish_works',
		'read_post' 			=> 'read_work',
		'read_private_posts' 	=> 'read_private_works',
		'delete_post' 			=> 'delete_work',
		'delete_posts' 			=> 'delete_works'
	);

	register_post_type('work', 
		array(
			'labels' 				=> $labels,
			'public' 				=> true,
			'show_in_rest' 			=> true,
			'supports' 				=> array('title', 'editor', 'thumbnail', 'custom-fields'),
			'rewrite' 				=> array('slug' => 'works'),
			// 'has_archive' 			=> true,
			'menu_icon' 			=> 'dashicons-art',
			'capability_type' 		=> 'post',
			'hierarchical' 			=> false,
			// 'capabilities' 			=> $capabilities,
		)
	);
}

function ats_plugin_reel_custom_post_type() {
	$labels = array(
		'name' 				=> __('Reels', 'ats_plugin'),
		'singular_name' 	=> __('Reel', 'ats_plugin'),
		'add_new' 			=> __('Add New', 'ats_plugin'),
		'add_new_item' 		=> __('Add New Reel', 'ats_plugin'),
		'edit_item' 		=> __('Edit Reel', 'ats_plugin'),
		'new_item' 			=> __('New Reel', 'ats_plugin'),
		'view_item' 		=> __('View Reel', 'ats_plugin'),
		'search_items' 		=> __('Search Reels', 'ats_plugin'),
		'not_found' 		=> __('No reels found', 'ats_plugin'),
	);

	$capabilities = array(
		'edit_post' 			=> 'edit_reel',
		'edit_posts' 			=> 'edit_reels',
		'edit_others_posts' 	=> 'edit_others_reels',
		'publish_posts' 		=> 'publish_reels',
		'read_post' 			=> 'read_reel',
		'read_private_posts' 	=> 'read_private_reels',
		'delete_post' 			=> 'delete_reel',
		'delete_posts' 			=> 'delete_reels'
	);

	register_post_type('reel', 
		array(
			'labels' 				=> $labels,
			'public' 				=> true,
			'show_in_rest' 			=> true,
			'supports' 				=> array('title', 'editor', 'thumbnail', 'custom-fields'),
			'rewrite' 				=> array('slug' => 'reels'),
			// 'has_archive' 			=> true,
			'menu_icon' 			=> 'dashicons-art',
			'capability_type' 		=> 'post',
			'hierarchical' 			=> false,
			// 'capabilities' 			=> $capabilities,
		)
	);
}
