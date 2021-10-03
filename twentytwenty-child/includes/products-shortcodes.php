<?php

/** 
 * This short code is for displaying a grid of products.
 * It's used to show products: 1. On the home page, 2. In the single product footer
 * It's set to display until 6 products.
 */
add_shortcode( 'products_grid', 'products_grid_shortcode' );
function products_grid_shortcode( $atts ) {

    // Remove atts keys with empty values.
    if(is_array($atts)) $atts = array_filter( $atts, 'ucfirst' );

    $a = shortcode_atts( array(
        'items' => 6,
        'terms' => '*'
    ), $atts);
   
    // div should be with full width. (It lookes good also in 50% width)
    $products_grid = '<div class="container grid " style="width:90%;min-width:90%;text-align:center;">';
    $products_grid .= '<div class="row justify-content-md-center mb-4">';
        
    // get posts from DB.
    $products = get_posts( array(
        'post_type'=> 'products',
        'order'    => 'ASC',
        'posts_per_page' => $a['items'],
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'department',
                'field'    => 'term_id',
                'terms'    => $a['terms'],
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


/**
 * This Shortcode will display a box shoeing a product
 * **Part5**
 * attrs => product_id, bg-color
 * output => Main image, Title, price(or sale price), bg-color as specified
 */
add_shortcode( 'display_product', 'product_display_shortcode' );
function product_display_shortcode( $atts ) {

    // Remove atts keys with empty values.
    if(is_array($atts)) $atts = array_filter( $atts, 'ucfirst' );

    // When no product id given as attr, return the latest product.
    $args = array(
        'post_type' =>'products',
        'posts_per_page' => 1
    );
    $recent_post = wp_get_recent_posts($args, OBJECT);

    // set default attrs
    $a = shortcode_atts(array(
        'product_id' => $recent_post[0]->ID,
        'bg_color' => 'blue'
    ), $atts );

    // the output
    $style = "max-width:20vw;border:2px solid ".$a['bg_color'].";background-color:". $a['bg_color'] .";";
    ?>

        <div class="col-xl-4 mb-3 p-4" style="<?php echo $style ?>">
            <div class="card border-0 shadow" >
                <a href="<?php the_permalink( $a['product_id'] ); ?>"  class="link-dark" >
                    <img src="<?php echo get_the_post_thumbnail_url($a['product_id'], 'small') ?>" class="card-img-top" alt="..." >
                    <div id="product-price" class="card-body bg-light  text-center link-dark" >
                        <p>Price:
                        <?php  echo get_post_meta($a['product_id'], '_products_price', true ) ?>
                        </p>
                        <h5 class="card-title mb-0 link-dark"><?php echo get_the_title($a['product_id']) ?></h5>
                    </div>
                </a>
            </div>
        </div>
 
    <?php

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