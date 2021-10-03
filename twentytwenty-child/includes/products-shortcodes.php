<?php

/** 
 * This short code is for displaying a grid of products.
 * It's used to show products: 1. On the home page, 2. In the single product footer
 * It's set to display until 6 products.
 */
add_shortcode( 'products_grid', 'products_grid_shortcode' );

function products_grid_shortcode( $atts ) {

    $posts_per_page = isset($atts['items']) ? $atts['items'] : 6;
    if (isset($atts['terms'])) {
        $terms = $atts['terms'];
    } else {
        $terms ='*';
    }

    // div should be with full width. (It lookes good also in 50% width)
    $products_grid = '<div class="container grid " style="width:90%;min-width:90%;text-align:center;">';
    $products_grid .= '<div class="row justify-content-md-center mb-4">';
        
    // get posts from DB.
    $products = get_posts( array(
        'post_type'=> 'products',
        'order'    => 'ASC',
        'posts_per_page' => $posts_per_page,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'department',
                'field'    => 'term_id',
                'terms'    => $terms
            )
        ),
    ));

    foreach ($products as $product) {
        // load the product's card with this function.
        $products_grid .= load_template_part($product);
    }

    $products_grid .= '</div>';
    $products_grid .= '<a href=' . get_post_type_archive_link('products') . '>
        <button >' . esc_html__('More Products') . '</button>
        </a>';
    $products_grid .= '<hr /></div>'; 


    return $products_grid;
}


/**Shortcodes helper FUNCTIONS */

/* This function loads a file into a var. used only for shortcodes so it's stored here */
function load_template_part($product) {
    ob_start();
    get_template_part( 'includes/products-archive-content' ,
            'archive product_content' , array( 'productObj' => $product)  );
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

?>