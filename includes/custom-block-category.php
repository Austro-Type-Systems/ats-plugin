	<?php
function ats_plugin_custom_block_categories( $categories, $post ) {
    // Define your custom category
    $my_category = array(
        array(
            'slug'  => 'ats_plugin',
            'title' => __( 'ats_plugin', 'ats_plugin_' ),
            'icon'  => null, // Optional: You can specify a Dashicon here
        ),
    );

    // Merge your category with the existing categories
    return array_merge( $my_category, $categories );
}
