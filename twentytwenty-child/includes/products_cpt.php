<?php

create_products_cpt();
create_department_taxonomy();


#region Add Custom Post Type - Products

/** Add cpt Products */
//add_action( 'after_theme_setup', 'create_products_cpt', 5 );

/* register custom post type: Products */
function create_products_cpt() {
    // Set labels
    $labels = array(
        'name'                => __( 'Products', 'twentytwenty_child' ),
        'singular_name'       => __( 'Product', 'twentytwenty_child' ),
        'menu_name'           => __( 'Products', 'twentytwenty_child' ),
        'parent_item_colon'   => __( 'Parent Product', 'twentytwenty_child' ),
        'all_items'           => __( 'All Products', 'twentytwenty_child' ),
        'view_item'           => __( 'View Product', 'twentytwenty_child' ),
        'add_new_item'        => __( 'Add New Product', 'twentytwenty_child' ),
        'add_new'             => __( 'Add New Product', 'twentytwenty_child' ),
        'edit_item'           => __( 'Edit Product', 'twentytwenty_child' ),
        'update_item'         => __( 'Update Product', 'twentytwenty_child' ),
        'search_items'        => __( 'Search Product', 'twentytwenty_child' ),
        'not_found'           => __( 'Product Not Found', 'twentytwenty_child' ),
        'not_found_in_trash'  => __( 'Product Not found in Trash', 'twentytwenty_child' ),
    );
 
    // product args
    $args = array(
        'label'                 => __( 'Products', 'twentytwenty_child' ),
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 10,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'hierarchical'          => true,
        'rewrite' => array('slug'=>'products'),
        'taxonomies' => ['department'],
        'supports' => array(
            'title', 'thumbnail', 'revisions' // , 'category' , 'editor'
        ),
        //'register_meta_box_cb' => 'create_products_metaboxes'
    );
    // Register the post type 
    register_post_type( 'products', $args);
}

#endregion


#region **Register TAXOMONY**

/* Add Taxonomy for our CPT 'products' */ 
//add_action( 'after_theme_setup', 'create_department_taxonomy', 0 );
 
/* Register function */
function create_department_taxonomy() {
    // Set the taxomony labels
    $labels = array(
        'name' => _x( 'Departments', 'twentytwenty_child' ),
        'singular_name' => _x( 'Department', 'twentytwenty_child' ),
        'search_items' =>  __( 'Search Department' ),
        'all_items' => __( 'All Departments' ),
        'parent_item' => __( 'Parent Department' ),
        'parent_item_colon' => __( 'Parent Department:' ),
        'edit_item' => __( 'Edit Department' ),
        'update_item' => __( 'Update Department' ),
        'add_new_item' => __( 'Add New Department' ),
        'new_item_name' => __( 'New Department Name' ),
        'menu_name' => __( 'Departments' ),
    );
    // Register the Department taxonomy
    register_taxonomy('department','products', array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        // 'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        ));

    /** Add terms:  new categoris for department taxomony */
    /** (for use in add_products file) */
    wp_insert_term( 'Cheese', 'department' );
    wp_insert_term( 'Other', 'department' );
    wp_insert_term( 'Wine', 'department' );

}
#endregion TAXOMONY

?>