<?php
 /**
  * Product post type - new CTP
  *
  *
  * Table of contents:
  * - Add metaboxes for the Products post type
  * -   - editor view for products post type
  * -   - save post function (with mb)
  * - Other function for handling CPT -Products
  */



#region **Register METABOXES**

/** 
 * Add all the metaboxes:
 * Main image
 *      - in editor: use the "Set featured image"
 * Gallery (6 img)
 *      - Added the wp media button
 * Title 
 *      - Post title
 * Description
 * Price
 * Sale price 
 * is on sale 
 * Toutube video (url) 
 * Category
 *      - Set in functions.php = Department taxomony 
 **/

add_action( 'add_meta_boxes', 'add_products_metaboxes' );
function add_products_metaboxes () {
    $screen = 'products';

    /* Images */
    add_meta_box(
        'media_box',
        __('Add Product Images',  'twentytwenty_child' ),
        'media_metabox_html'
    );

    /* Description */
    add_meta_box(
        'products_description',   //id (includes: price, sale price, is on sale)
        __('Product Description' , 'twentytwenty_child' ), //title
        'description_html', //callback
        $screen, //screens,
        'advanced',
        'high'
    );

    /* Price: Price, Sale price, is on sale: */
    add_meta_box(
        'products_price',   //id (includes: price, sale price, is on sale)
        __('Products price' , 'twentytwenty_child' ), //title
        'price_html', //callback
        $screen, //screens
        'side',
        'core'
    );

    /* You Tube video url */
    add_meta_box(
        'yt_vid_url',   //id
        __('Youtube video url' , 'twentytwenty_child' ), //title
        'youtube_url_html', //callback
        $screen, //screens
        'advanced',
        'core'
    );

}

#endregion


#region ** -- editor view for products post type**
/* Html elements for metaboxes. callbacks */

/* Media metabox*/
//-jquery handler is enqueued in the bottom of this page
function media_metabox_html ($post) {
    
    $img_ids = get_post_meta( $post->ID, '_product_images_ids', true);

    // This input eill be saved to the post meta
    echo "<input type='hidden' id='product_images_ids' name='product_images_ids'
            value='$img_ids' />";
    
    // Imgs are stored with comma seperator. turn them into an array.
    $imgs = explode(',' ,$img_ids);

    // Output a div for each image.
    for ($i=0; $i<6; $i++) {  //run only 6 times, anyway there will be only 6 imgs.

        echo "<div id='img-$i' tag='$i' class='image-div'>";
        // get image if exsist. if not, add upload button. give place for 6 imgs.
        if  (!empty($imgs[$i])) {
            // add a delete button 
            echo "<input type='button' tag='$i' class='button button-secondary delete-button' 
            value='Remove Product Image' style='color:red;border-color:red;' />";
            // add hidden input with, set it's value to the image (post) id. so it wont be delted on saving the post.
            echo "<input type='hidden' id='product_image_id-$i' name='product_image_id'  value='" .$imgs[$i] ."' />";
            // show out the image.
            echo wp_get_attachment_image($imgs[$i], ['100', '100']);
        } else {
            // add the upload button
            echo "<input type='button'  tag='$i' class='button button-secondary upload-button' 
                value='Upload Product Image' tag='$i' >";
        }
        echo "</div> <hr />";
    }  
}

/* Description html - editor box */
function description_html ($post) {
 
    $content = get_post_meta( $post->ID, '_products_description', true );
    wp_editor( $content, 'product_description_editor', array(
        'media_buttons' => false,
        'textarea_rows' => 8,
        'quicktags' => false
    ));
}

/* Price box */
function price_html ($post) {
    
    $price = get_post_meta( $post->ID, '_products_price', true);
    $sale_price = get_post_meta( $post->ID, '_products_sale_price', true);
    $is_onsale = get_post_meta( $post->ID, '_is_onsale', true);        

    ?>
    <div class="">
        <label for="products_price" class="">Price</label>
        <input name="products_price" id="products_price" 
            value="<?php echo $price ?>" type="number" />
    </div>
    <div>
        <label for="products_sale_price">Sale price</label>
        <input name="products_sale_price" id="products_sale_price" 
            value="<?php echo $sale_price ?>" type="number" />
    </div>
    <div class="">
        <label for="products_isonsale">is on sale</label>
        <input name="products_isonsale" id="products_isonsale" class="" 
            type="checkbox" <?php  checked( $is_onsale, 1) ?> />
    </div>
    
    <?php   
}

/* You tube video html */
function youtube_url_html ($post) {
    $value = get_post_meta( $post->ID, '_yt_vid_url', true );
    ?>
    <label for="yt_vid_url">Enter the youtube video url here:</label>
    <input name="yt_vid_url" id="yt_vid_url" class="widefat" 
        value="<?php echo esc_url($value) ?>"  />
    <div id="video-embed">
        <?php  echo $GLOBALS['wp_embed']->run_shortcode( '[embed height="150px"] '. $value .' [/embed]'); ?>
    </div>
    <?php
}

/* END html callbacks for add_meta_box */
#endregion 


#region Save function for products metadata

/* SAVE the meta boxes inputs on admins pannel */
add_action( 'save_post', 'products_save_postdata' );
function products_save_postdata( $post_id ) {

    /* Save and Images -jquery handler is enqueued in the bottom of this page */
    if ( array_key_exists( 'product_images_ids', $_POST ) ) {

        $value = $_POST['product_images_ids']; 
        // remove duplicates
        $value = implode(",", array_unique(explode(',' ,$value)));   
        
        // save the data
        update_post_meta(
            $post_id,
            '_product_images_ids',
            $value
        );
    }
        
    
    /* Save and update Description box */
    if ( array_key_exists( 'product_description_editor', $_POST ) ) {
        update_post_meta(
            $post_id,
            '_products_description',
            $_POST['product_description_editor']
        );
    }
    
    /* Update price data */
    if ( array_key_exists( 'products_price', $_POST ) ) {
       update_post_meta(
            $post_id,
            '_products_price', 
            $_POST['products_price']);
    }

    /* Update Sale Price data*/
    if ( array_key_exists( 'products_sale_price', $_POST ) ) {
        update_post_meta($post_id,
            '_products_sale_price',
            $_POST['products_sale_price']
        );
    }

    /* Update Is on sale (checkbox) data*/
    if ( array_key_exists( 'products_isonsale', $_POST ) ) {
        update_post_meta( $post_id, '_is_onsale', 1 );
    }

    /* Update You tube vid url meta box*/
    if ( array_key_exists( 'yt_vid_url', $_POST ) ) {
        $url = esc_url_raw( $_POST['yt_vid_url']);
        update_post_meta( $post_id, '_yt_vid_url', $url);
    }    

}

#endregion


#region Other function for CPT - Products handling

/* Add js scripts and functions to the Products editing page */
add_action('admin_enqueue_scripts', 'scripts_for_product_edit');
function scripts_for_product_edit () {

    if (is_admin() && get_post_type() == 'products') {
        // Load wp media uploader for adding images.
        wp_enqueue_script('product_img_uploader', 
            get_stylesheet_directory_uri() .'/js/media-uploader.js',
            array('jquery'));

    }
}


#endregion