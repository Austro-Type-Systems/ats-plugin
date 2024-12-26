<?php
/**
 * Bidirectional Relationship
 * 
 * This class creates a bidirectional relationship between 
 * two post types. It creates a meta box in the from post 
 * type and a select field for the related posts. It also 
 * creates a ajax action to search for related posts.
 */

class ats_plugin_Bidirectional_Relationship {
    private $from_post_type;
    private $to_post_type;
    private $from_meta_key;
    private $to_meta_key;
    private $nonce_action;
    private $nonce_name;
    private $ajax_action;
    private $metabox_id;
    private $metabox_title;

    public function __construct($args) {
        $this->from_post_type = $args['from_post_type'];
        $this->to_post_type = $args['to_post_type'];
        $this->from_meta_key = $args['from_meta_key'];
        $this->to_meta_key = $args['to_meta_key'];
        $this->nonce_action = $args['nonce_action'];
        $this->nonce_name = $args['nonce_name'];
        $this->ajax_action = $args['ajax_action'];
        $this->metabox_id = $args['metabox_id'];
        $this->metabox_title = $args['metabox_title'];

        add_action( 'admin_enqueue_scripts', array( $this, 'ats_plugin_enqueue_select2' ) );
        add_action( 'add_meta_boxes', array( $this, 'ats_plugin_add_relationship_metabox' ) );
        add_action( 'save_post_' . $this->from_post_type, array( $this, 'ats_plugin_save_relationship' ) );
        add_action( 'wp_ajax_' . $this->ajax_action, array( $this, 'ats_plugin_related_post_search' ) );
		add_action('init', array($this, 'register_bidirectional_relationship_meta'));

    }

    public function ats_plugin_enqueue_select2($hook) {
        global $post_type;

        if ( ('post.php' == $hook || 'post-new.php' == $hook) && $post_type == $this->from_post_type ) {
            wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', array(), '4.0.13' );
            wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
        }
    }

    public function ats_plugin_add_relationship_metabox() {
        add_meta_box(
            $this->metabox_id,
            $this->metabox_title,
            array( $this, 'ats_plugin_relationship_metabox_callback' ),
            $this->from_post_type,
            'side',
            'default'
        );
    }

	public function ats_plugin_relationship_metabox_callback($post) {
		// Add nonce for security
		wp_nonce_field($this->nonce_action, $this->nonce_name);
	
		// Get existing related posts
		$related_posts = get_post_meta($post->ID, $this->from_meta_key, false); // false returns all meta values as an array
		if (!is_array($related_posts)) {
			$related_posts = array();
		}
	
		// Generate unique ID and name for the select element
		$select_id = esc_attr($this->metabox_id . '_select');
		$select_name = esc_attr($this->from_meta_key . '[]');
	
		// Output the select field
		echo '<select id="' . $select_id . '" name="' . $select_name . '" multiple="multiple" style="width: 100%;">';
	
		// Pre-populate with selected posts
		if (!empty($related_posts)) {
			$selected_posts = get_posts(array(
				'post_type' => $this->to_post_type,
				'post__in' => $related_posts,
				'numberposts' => -1,
				'post_status' => 'publish',
			));
			foreach ($selected_posts as $related_post) {
				echo '<option value="' . esc_attr($related_post->ID) . '" selected="selected">' . esc_html($related_post->post_title) . '</option>';
			}
		}
	
		echo '</select>';
	
		// Initialize Select2 with the unique ID
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#<?php echo esc_js($select_id); ?>').select2({
					placeholder: '<?php echo esc_js($this->metabox_title); ?>',
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								action: '<?php echo esc_js($this->ajax_action); ?>',
								q: params.term, // search term
								page: params.page
							};
						},
						processResults: function (data, params) {
							params.page = params.page || 1;
	
							return {
								results: data.items,
								pagination: {
									more: data.more
								}
							};
						},
						cache: true
					},
					minimumInputLength: 1,
				});
			});
		</script>
		<?php
	}

    function ats_plugin_save_relationship($post_id) {
	
		// Verify nonce
		if (!isset($_POST[$this->nonce_name]) || !wp_verify_nonce($_POST[$this->nonce_name], $this->nonce_action)) {
			return;
		}

		// Check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Check if this is the correct post type
		if (get_post_type($post_id) != $this->from_post_type) {
			return;
		}

		// Check user permissions
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		// Get submitted related posts
		$related_posts = isset($_POST[$this->from_meta_key]) ? array_map('intval', $_POST[$this->from_meta_key]) : array();

		// Remove all existing relationships
		delete_post_meta($post_id, $this->from_meta_key);
	
		// Add each related post ID as a separate meta entry
		foreach ($related_posts as $related_post_id) {
			add_post_meta($post_id, $this->from_meta_key, $related_post_id);
		}
	
		// Now update the reverse relationships
		// Get all posts that previously had this post related
		$args = array(
			'post_type'      => $this->to_post_type,
			'meta_key'       => $this->to_meta_key,
			'meta_value'     => $post_id,
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);
		$existing_related_posts = get_posts($args);
	
		// Remove the relationship from posts no longer related
		$posts_to_remove = array_diff($existing_related_posts, $related_posts);
		foreach ($posts_to_remove as $related_post_id) {
			delete_post_meta($related_post_id, $this->to_meta_key, $post_id);
		}
	
		// Add the current post ID to the related posts of newly related posts
		foreach ($related_posts as $related_post_id) {
			add_post_meta($related_post_id, $this->to_meta_key, $post_id);
		}
	}

   function ats_plugin_related_post_search() {

		$results = array();
		$search_term = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
		$paged = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$query_args = array(
			'post_type'      => $this->to_post_type,
			's'              => $search_term,
			'posts_per_page' => 10,
			'paged'          => $paged,
			'post_status'    => 'publish',
		);

		$query = new WP_Query($query_args);
		$items = array();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$items[] = array(
					'id'   => get_the_ID(),
					'text' => get_the_title(),
				);
			}
		}

		$more = $query->max_num_pages > $paged;

		wp_send_json(array('items' => $items, 'more' => $more));
	}

	function register_bidirectional_relationship_meta(){
		register_post_meta($this->from_post_type, $this->from_meta_key, array(
			'show_in_rest' => true,
			'single' => false, // Since we'll be storing multiple values
			'type' => 'integer',
		));
	
		register_post_meta($this->to_post_type, $this->to_meta_key, array(
			'show_in_rest' => true,
			'single' => false, // Since we'll be storing multiple values
			'type' => 'integer',
		));
	}
}
