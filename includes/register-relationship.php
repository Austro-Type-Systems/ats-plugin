	
	<?php

// For Work and Show Relationship
require_once ats_plugin_PLUGIN_PATH . 'includes/Bidirectional_Relationship.php';


/**
 * Work and Reel Relationship
 */
new ats_plugin_Bidirectional_Relationship(array(
    'from_post_type' => 'work',
    'to_post_type' => 'reel',
    'from_meta_key' => '_ats_plugin_related_reels',
    'to_meta_key' => '_ats_plugin_related_works',
    'nonce_action' => 'ats_plugin_save_work_reel',
    'nonce_name' => 'ats_plugin_work_reel_nonce',
    'ajax_action' => 'ats_plugin_reel_search',
    'metabox_id' => 'ats_plugin_work_reel_metabox',
    'metabox_title' => 'Related reels',
));

/**
 * Reel and Work Relationship
 */
new ats_plugin_Bidirectional_Relationship(array(
    'from_post_type' => 'reel',
    'to_post_type' => 'work',
    'from_meta_key' => '_ats_plugin_related_works',
    'to_meta_key' => '_ats_plugin_related_reels',
    'nonce_action' => 'ats_plugin_save_reel_work',
    'nonce_name' => 'ats_plugin_reel_work_nonce',
    'ajax_action' => 'ats_plugin_work_search',
    'metabox_id' => 'ats_plugin_reel_work_metabox',
    'metabox_title' => 'Related Works',
));

