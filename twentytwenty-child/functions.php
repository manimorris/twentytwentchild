<?php
 
 /**
  * Twenty Twenty Child Theme
  * *********************
  * Table of contents:
  * -------------------
  * Register styles
  *
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

