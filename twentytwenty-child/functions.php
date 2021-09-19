<?php
 
 /**
  * Twenty Twenty Child Theme
  * *********************
  * Table of contents:
  * -------------------
  * Register styles
  * Theme setup
  */


/* Register the parent styles */
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );

/* Function to enqueue stylesheet from parent theme */
function child_theme_enqueue_styles() {
    // enqueue parent(2020) theme style
    $parenthandle = 'parent-style'; 
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css');
    // enqueue child theme style, with parent theme style as a dependencie
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parenthandle ));
}

/* Some theme setups */
add_action( 'after_setup_theme', 'twentytwenty_child_setup' );
function twentytwenty_child_setup() {

    /* Make available for translation. not asked to,
        but it's a good practice */
    load_child_theme_textdomain( 'twentytwenty_child' );

    /* Get the theme_new_user and load the code. this is `Part-3` of dev test :) */
    get_template_part( 'includes/theme_new_user', 'new_user_part3' );


}
