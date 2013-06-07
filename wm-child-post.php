<?php
/*
Plugin Name: WM Child Post
Version: 1.01
Description: WM Child Post wordpress plugins use for showing child post in parent post, page or widget area. You can add child post in page, post or custom post type. Enable FAQ checkbox for activating FAQ feature.
Author: Repon Hossain
Author URI: 
Plugin URI: 
*/

if ( !defined( 'WM_DIR' ) ) define( 'WM_DIR', plugin_dir_path( __FILE__ ) );
if ( !defined( 'WM_URL' ) ) define( 'WM_URL', plugins_url( '', __FILE__ ) );
if ( !defined( 'WM_VERSION' ) ) define( 'WM_VERSION', 1.01);

if ( is_admin() ) {
	include_once('includes/wm-cp.class.php');
	WM_childpost::init();
}

//Add admin style and javascript in the wp admin panel
function register_wm_admin_style () {
	wp_register_style( 'wm-child-admin-style', WM_URL . '/css/wm-child-admin-post.css', false, WM_VERSION );
	wp_enqueue_style( 'wm-child-admin-style' );

	wp_register_script( 'wm-child-post', WM_URL . '/js/wm-child-post.js', array('jquery'), WM_VERSION, true);
	wp_enqueue_script( 'wm-child-post' );
}
add_action( 'admin_enqueue_scripts', 'register_wm_admin_style' );

//Add style and javascript in wp frontend
function register_wm_style () {
	wp_register_style( 'wm-child-style', WM_URL . '/css/wm-child-post.css', false, WM_VERSION );
	wp_enqueue_style( 'wm-child-style' );

	wp_register_script( 'wm-child-post', WM_URL . '/js/wm-child-faq.js', array('jquery'), WM_VERSION, true);
	wp_enqueue_script( 'wm-child-post' );
}
add_action( 'wp_enqueue_scripts', 'register_wm_style' );

//load wp-child-post language file
function wp_child_post_language() {
  load_plugin_textdomain( 'wp-child-post', false, dirname( plugin_basename( __FILE__ ) ) ); 
}
add_action('plugins_loaded', 'wp_child_post_language');

//Create custom child-post post type
add_action( 'init', 'wm_child_post');
function wm_child_post() {

	//labels array for child-post post type
	$labels = array(
		'name' 					=> _x('Child Posts', 'wm-child-post'),
		'singular_name' 		=> _x('Child Post', 'wm-child-post'),
		'add_new' 				=> _x('New Child Post', 'wm-child-post'),
		'add_new_item' 			=> _x('Add New Child Post', 'wm-child-post'),
		'edit_item' 			=> _x('Edit Child Post', 'wm-child-post'),
		'new_item' 				=> _x('New Child Post', 'wm-child-post'),
		'all_items' 			=> _x('All Child Post', 'wm-child-post'),
		'view_item' 			=> _x('View Child Post', 'wm-child-post'),
		'search_items' 			=> _x('Search Child Post', 'wm-child-post'),
		'not_found' 			=> _x('No Post Found', 'wm-child-post'),
		'not_found_in_trash'	=> _x('No Post in Trash', 'wm-child-post'),
		'menu_name' 			=> _x('Child Post', 'wm-child-post'),
	);

	//Setting child-post post, you can change slug value
	register_post_type( 'child-post', array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'show_ui' 				=> true, 
		'show_in_menu' 			=> true, 
		'query_var' 			=> true,
		'rewrite' 				=> array( 'slug' => 'wmcp' ),
		'capability_type' 		=> 'post',
		'has_archive' 			=> true, 
		'hierarchical' 			=> false,
		'supports' 				=> array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'				=> WM_URL . '/css/icon.png'
	));
}

//create custom taxonomy for groups
function child_group_taxonomy() {

	//Group taxonomy labels
	$labels = array(
		'name'							=> _x('Groups', 'wm-child-post'),
		'singular_name'					=> _x('Group', 'wm-child-post'),
		'add_new_item'					=> _x('Add New Group', 'wm-child-post'),
		'new_item_name'					=> __('New Group'),
		'all_items'						=> __('All Group'),
		'edit_item'						=> __('Edit Group'),
		'view_item'						=> __('View Group'),
		'update_item'					=> __('Update Group'),
		'search_items'					=> __('Search Groups'),
		'separate_items_with_commas'	=> __('Seperate Groups with commas'),
		'choose_from_most_used'			=> __('Choose from the most used groups'),
		'not_found'						=> __('No Groups Found')
	);

	//Group taxonomy settings
	register_taxonomy('child-group', 'child-post',  array(  
		'labels' 				=> $labels,
		'public'				=> true,
		'hierarchical'          => false,
		'query_var'             => true,
		'rewrite' 				=> array( 'slug' => 'instruments')
	) );
}  
add_action( 'init', 'child_group_taxonomy' );

//Create custom posts column for child post type
add_filter( 'manage_child-post_posts_columns', 'set_custom_column', 10, 2 );
function set_custom_column ($columns) {
	$columns['description'] = __( 'Description');
	$columns['groups'] = __( 'Groups');
	return $columns;
}

//Child Post type column value
add_action( 'manage_child-post_posts_custom_column' , 'display_child_post_groups', 10, 2 );
function display_child_post_groups( $column, $post_id ) {
	switch ($column) {
		case 'description':
			echo get_the_excerpt($post_id);
			break;

		case 'groups':
		    echo get_the_term_list( $post_id, 'child-group', '', ', ', '' );
			break;
		
		default:
			break;
	}
    
}

//wm-child post shortcode function
function show_wm_child_post($atts) {
	if ( get_post_type() == 'child-post' ) { echo ''; return;}

	extract(shortcode_atts(array( 
		'title'			=> '',
		'groups' 		=> '',
		'excerpt'		=> false,
		'showposts'		=> '',
		'faq'			=> false
	), $atts));

	//declared $arguments array
	$arguments = array();

	//add a post_type and showposts key in $arguments array
	$arguments['post_type'] = 'child-post';

	//add a group key in $arguments array if group key available in shortcode
	if ( isset($groups) && !empty($groups) )
		$arguments['child-group'] = $groups;

	//add a showpost key in $arguments array if showposts key available in shortcode
	if ( isset($showposts) && !empty($showposts) )
		$arguments['showposts'] = $showposts;

	//return excerpt value or set to false
	$excerpt = isset($excerpt) && !empty($excerpt) ? $excerpt: 'false';

	//declared a $WM_post class for query wmchild post
	$WM_post = new WP_Query( $arguments );

	if ( $WM_post->have_posts() ) :

		//add a id to wmchild post widget
		$wm_id = ($faq == 'true') ? 'id="wmchild-faq"' : 'id="wmchild-post"';

		$wm_value = '<div class="wmchild-post" '. $wm_id .'>';
		//show title if title attr not empty
		if ( isset( $title ) && !empty( $title ) )
			$wm_value .= '<h2 class="wm-title">' . $title . '</h2>';

		while ( $WM_post->have_posts() ) : $WM_post->the_post();

			$wm_value .= '<article id="wm-' . get_the_ID() . '" class="wmcp">';

				if ( $excerpt != 'true' || $faq = 'true' ) {
					$wm_value .= '<h3 class="post-title">'. get_the_title() .'</h3>';
				} else {
					$wm_value .= '<a title="'. get_the_title() .'" href="'. get_permalink() .'"><h3 class="post-title">'. get_the_title() .'</h3></a>';
				}

				if ( $excerpt != 'true' ) {
					$wm_value .= '<div class="wm-details">' . get_the_content() . '</div>';
				} else {
					$wm_value .= '<div class="wm-details excerpt">' . get_the_post_thumbnail() . get_the_excerpt() . '</div>';
				}

			$wm_value .= '</article>';
		endwhile;
		$wm_value .= '</div>';
	endif;

	//reset custom wp query
	wp_reset_query();

	//return shortcode value
	return $wm_value;
} 
add_shortcode('wmpost', 'show_wm_child_post');
add_filter('widget_text', 'do_shortcode');

//Create shortcode button
add_action('init', 'wm_child_post_short_button');
function wm_child_post_short_button() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'wm_child_post_script' );
      add_filter( 'mce_buttons', 'wm_register_short_button' );
   }

}

//Register shortcode button
function wm_register_short_button( $buttons ) {
   array_push( $buttons, "|", "wmcp" );
   return $buttons;
}

//Add shortcode button script
function wm_child_post_script( $plugin_array ) {
   $plugin_array['wmcp'] = WM_URL . '/js/wm-cp-shortbtn.js';
   return $plugin_array;
}

?>