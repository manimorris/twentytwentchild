<?php

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp/v2/products', '/department/(?P<param>[a-zA-Z0-9-]+)', array(
    'methods' => 'GET',
    'callback' => 'return_products_func',
  ));
});


function return_products_func( WP_REST_Request $request ) {
    $result = array();
     
    // Get the Paramenter (id or name or slug) of department requested.
    $param = $request->get_param( 'param' );

    // Get posts by the param
    $products = get_posts( array(
        'post_type' => 'products',
        'order'     => 'ASC',
        'tax_query' => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'department',
                'field'    => 'term_id',
                'terms'    => $param,
            ),
            array(
                'taxonomy' => 'department',
                'field'    => 'slug',
                'terms'    => $param,
            ),
            array(
                'taxonomy' => 'department',
                'field'    => 'name',
                'terms'    => $param,
            )
        ),
    ));
    
    

    foreach( $products as $product ) {
        // Get the post's meta
        $postmeta = get_post_meta( $product->ID);

        // Get the Main image url
        $image = get_the_post_thumbnail_url( $product->ID );

        // Arrange the post output data in array
        $prodArr = array(
            'title'         => $product->post_title,
            'description'   => $postmeta["_products_description"][0],
            'image'         => $image,
            'price'         => $postmeta["_products_price"][0],
            'is_on_sale'    => $postmeta["_is_onsale"][0],
            'sale_price'    => $postmeta["_products_sale_price"][0],
        );  
        
        // Add the data to result arr.
        $result[] = $prodArr;
    }

    return $result;
}

?>