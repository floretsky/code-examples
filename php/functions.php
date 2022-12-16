<?php

add_action( 'wp_enqueue_scripts', 'buzzblogpro_child_theme_enqueue_styles' );
function buzzblogpro_child_theme_enqueue_styles() {

    $parent_style = 'buzzblogpro-style';
    wp_register_style( $parent_style, get_template_directory_uri() . '/style.css', array('bootstrap'), '1.0', 'all' );
	$filename = get_stylesheet_directory_uri() . '/style.css';
    wp_enqueue_style( $parent_style);
    wp_enqueue_style( 'buzzblogpro-child-style',
        $filename . '?v=' . filemtime(get_stylesheet_directory() . '/style.css'),
        array( $parent_style ),
        1.1
    );
}

add_action('wp_enqueue_scripts', 'load_bootstrap');
function load_bootstrap(){
	wp_enqueue_script( 'isotope', get_stylesheet_directory_uri() . '/js/isotope.pkgd.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'accordion-min-js', get_stylesheet_directory_uri() . '/js/accordion.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'modal', get_stylesheet_directory_uri() . '/js/modal.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/js/slick.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'history', get_stylesheet_directory_uri() . '/js/history.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'main-script', get_stylesheet_directory_uri() . '/js/script.js?v=' . filemtime(get_stylesheet_directory() . '/js/script.js'), array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'crazy-buttons', get_stylesheet_directory_uri() . '/js/buttonss.js?v=' . filemtime(get_stylesheet_directory() . '/js/buttonss.js'), array ( 'jquery' ), 1.1, true); // 19.11.21

	wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/css/bootstrap-grid.min.css');
	wp_enqueue_style('accordion', get_stylesheet_directory_uri() . '/css/accordion.min.css');
	wp_enqueue_style('slick', get_stylesheet_directory_uri() . '/css/slick.css');
	wp_enqueue_style('slick-theme', get_stylesheet_directory_uri() . '/css/slick-theme.css');
	wp_enqueue_style('crazy-buttons', get_stylesheet_directory_uri() . '/css/buttonss.css?v=' . filemtime(get_stylesheet_directory() . '/css/buttonss.css')); // 19.11.21
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {
 
    $classes[] = 'gradient gradient-' . rand(1, 4);
    if (!is_front_page() && !is_page(3416)) {
		$classes[] = 'regular-page';
	}
	if (is_front_page()) {
		$classes[] = 'page-template-home-page-new';
	}
    return $classes;
     
}

add_shortcode("wpdiscuz_comments", "my_wpdiscuz_shortcode");
function my_wpdiscuz_shortcode( $_atts ) {
    $defaults = array(
        'post_id' => '',
    );
    $atts = shortcode_atts( $defaults, $_atts );
    // Confirm that $post_id is an integer.
    $atts['post_id'] = absint( $atts['post_id'] );

    // ----
    // Now you can use $atts['post_id'] - it will contain
    // the integer value of the post_id set in the shortcode, or
    // 0 if nothing is set in the shortcode.
    // ----

    $html = "";
    if (file_exists(ABSPATH . "wp-content/plugins/wpdiscuz/themes/default/comment-form.php")) {
        ob_start();
        include_once ABSPATH . "wp-content/plugins/wpdiscuz/themes/default/comment-form.php";
        $html = ob_get_clean();
    }
    return $html;
}

add_action('admin_head', 'my_column_width');
function my_column_width() {
    echo '<style type="text/css">';
    echo '.column-primary { width:200px !important; overflow:hidden }';
    echo '</style>';
}

add_filter( 'alm_query_args_relevanssi', 'my_alm_query_args_relevanssi');
function my_alm_query_args_relevanssi($args){
   $args = apply_filters('alm_relevanssi', $args);
   return $args;
}

add_filter( 'avatar_defaults', 'new_gravatar' );
function new_gravatar ($avatar_defaults) {
    $myavatar = 'https://reya.media/wp-content/uploads/2021/06/1.png';
    $avatar_defaults[$myavatar] = "Default Gravatar";
    return $avatar_defaults;
}

add_action( 'trash_comment', 'f711_trash_child_comments' );
function f711_trash_child_comments( $comment_id ) {
    global $wpdb;
    $children = $wpdb->get_col( $wpdb->prepare("SELECT comment_ID FROM $wpdb->comments WHERE comment_parent = %d", $comment_id) ); 
    if ( !empty($children) ) {
        foreach( $children as $thischild => $childid ) {
            wp_trash_comment( $childid, false ); 
        }
    }
}

add_theme_support('post-thumbnails');

add_action( 'init', 'tpl_doctor' );
function tpl_doctor() {

	register_taxonomy(
        'doctor_tag', 
        'doctor', 
        array( 
            'hierarchical'  => false, 
            'label'         => __( 'Tags for doctor', 'CURRENT_THEME' ), 
            'singular_name' => __( 'Tag for doctor', 'CURRENT_THEME' ), 
			'show_ui'       => true,
            'rewrite'       => true, 
            'query_var'     => true,
			'show_in_rest' => true
        )  
    );
	
    register_post_type( 'doctor', array(
        'public' => true,
        'labels' => array(
        'name' => 'Doctor Blog',
        'all_items' => 'All posts',
        'add_new' => 'Add new doctor',
        'add_new_item' => 'Add new doctor record'
        ),
        'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt', 'comments', 'post-formats'),
        'show_in_rest' => true,
        'taxonomies' => array( 'doctor_tag', 'category ', 'title' , 'thumbnail', 'meta_query'),
        'rewrite' => array('slug' => 'doctor')
    )
  );
};

add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate'); // for logged in user
function wp_infinitepaginate (){
	$loopFile = $_POST['loop_file'];
	$ids = $_POST['ids'];
	$action = $_POST['what'];
	
	$new_ids = explode(",", $ids);
	
	$arg = array('post__in' => $new_ids, 'post_status' => 'publish', 'posts_per_page' => 2, 'orderby' => 'post_views' , 'order' => 'ASC',);
	
	query_posts( $arg );
	get_template_part( $loopFile );
	exit;
}

add_action('init', 'do_rewrite');
function do_rewrite(){
    // Rewriting rule
    add_rewrite_rule( '^(tags)/([^/]*)/?', 'index.php?pagename=$matches[1]&tag=$matches[2]', 'top' );
    // Have to put ?p=123 if this rule is created for post ID 123
    // frist parameter is for posts: p or name, for pages: page_id or pagename

    add_filter( 'query_vars', function( $vars ){
        $vars[] = 'tag';
        return $vars;
    } );
}
