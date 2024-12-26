 <?php

function ats_plugin_enqueue_scripts() {
	wp_register_script(
		'ats_plugin-barba-js', 
		'https://unpkg.com/@barba/core', 
		array(), 
		null, 
		true
	);	
	wp_enqueue_script('ats_plugin-barba-js');

	wp_register_script(
		'ats_plugin-gsap', 
		'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js', 
		array(), 
		null, 
		true
	);
	wp_enqueue_script('ats_plugin-gsap');

	wp_register_script(
		'ats_plugin-scrolltrigger',
		'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js',
		array('ats_plugin-gsap'),
		null,
		true
	);
	wp_enqueue_script('ats_plugin-scrolltrigger');

	wp_register_script(
		'ats_plugin-scrollto',
		'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js',
		array('ats_plugin-scrolltrigger'),
		null,
		true
	);
	wp_enqueue_script('ats_plugin-scrollto');


	wp_register_script(
		'ats_plugin-page-transitions',
		ats_plugin_PLUGIN_URL . '/assets/js/page_transitions.js',
		array('ats_plugin-barba-js', 'ats_plugin-gsap', 'ats_plugin-scrolltrigger', 'ats_plugin-scrollto'), // dependencies
		wp_get_theme()->get('Version'),
		true
	);
	wp_enqueue_script('ats_plugin-page-transitions');

}
