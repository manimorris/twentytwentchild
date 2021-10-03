<?php
 
 /**
  * Twenty Twenty Child Theme
  * 
  * Register styles
  * Theme setup
  * - Add Custom Post Type - Products
  * - Add new taxomony for the Products post type
  *  other helpers
  */

define( 'CHILD_DIR', get_stylesheet_directory() );


/* Register the parent styles */
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );
function child_theme_enqueue_styles() {
    /* Use bootstrap - use first in order to position twenty twenty style later  */
    wp_enqueue_style( 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );

    wp_enqueue_style( "single_product", get_stylesheet_uri() . '/css/single-product.css');

    /* enqueue stylesheet from parent theme */
    $parenthandle = 'parent-style'; 
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css');

    /* enqueue child theme style, with parent theme style as a dependencie */
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parenthandle ));
    
    /* Register the themes shortcodes */
    get_template_part( 'includes/products-shortcodes', 'products_cpt_shortcodes');
}


#region

/** include the file that adds product cpt and taxomonies */
get_template_part( 'includes/products_cpt', 'products_cpt_and_taxo_registeration' );


/* Some theme setups */
add_action( 'after_setup_theme', 'twentytwenty_child_setup', 10 );
function twentytwenty_child_setup() {
    
    /* Register metada for ;products;' post type */
    get_template_part( 'includes/products-metadata', 'products_cpt_metadata' );
   
    /* Make available for translation. not asked to,
        but it's a good practice */
    load_child_theme_textdomain( 'twentytwenty_child' );

    /* Add support for thumbnails  */
    add_theme_support( 'post-thumbnails' ); 

}

/** Cretates custom user and products */
add_action( 'init', 'theme_custom_data' , 10);
function theme_custom_data() {

    /* Get the theme_new_user and load the code. this is `Part-3` of dev test :) */
    get_template_part( 'includes/theme_new_user', 'new_user_part3' );

    /* Get the theme_add_products and add 6 new products 'Part-4' of dev teat :) */
    /* This file will also add a grid list of these products to the home page using a shortcode */
    get_template_part( 'includes/theme_add_products', 'add_products_part4' );
}

/** Add custom address bar color for mobile browsers */
add_action('wp_headers', function() {
    echo '<meta name="theme-color" content="rgb(184, 180, 183)">';
});


/** Helper functions for this theme **/

/* Get metadata content - echo'd to frontend */
function the_postmeta( $meta_key) {
    global $post;
    echo get_post_meta( $post->ID, 
        $meta_key, true );
}


#endregion


?>