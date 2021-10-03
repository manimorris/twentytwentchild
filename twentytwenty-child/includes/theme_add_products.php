<?php

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

$theme_img_path = CHILD_DIR . '\includes\product-images';


/***ADD new products */

// ready data for products. same for all..
$yt_url = 'https://youtu.be/tspdJ6hxqnc';
$description = '<h3>Lorem Ipsum</h3>
    <h6>"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</h6>
    <p>"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain..."
    What is Lorem Ipsum?</p>

    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
        Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, 
        remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets 
        containing Lorem Ipsum passages, and more recently with desktop publishing software .</p>
';

/** Set's all the products data */
$new_products = array(
    "Blue Cheese" => array(
        "f_img" =>"Blue_Cheese_category.png",
        "taxo" => ['Cheese', 'Other'],
        "onSale" => 0
    ),
    "Nuts" => array(
        "f_img" =>"Nuts_category.png",
        "taxo" => ['Wine', 'Other'],
        "onSale" => 1
    ),
    "Wine" => array(
        "f_img" =>"Wine_category.png",
        "taxo" => 'Wine',
        "onSale" => 1
    ),
    "Vegetables" => array(
        "f_img" =>"Vegetables_category.png",
        "taxo" => 'Other',
        "onSale" => 1
    ),
    "Fruit" => array(
        "f_img" =>"Fruit_category.png",
        "taxo" => 'Other',
        "onSale" => 0
    ),
    "Hard Cheese" => array(
        "f_img" =>"Hard_Cheese_category.png",
        "taxo" => ['Cheese', 'Wine'],
        "onSale" => 0
    ),
);


/** Add the products (after validating they don't exsist) */
foreach ($new_products as $pr_title => $args) {
    $terms = get_terms() ;
    
    // run only if the products dont exsist
    if (!get_page_by_title( $pr_title, 'OBJECT' , 'products' )) {

        // Copy default imgs to upload dir
        copy_product_imgs( $theme_img_path, imgUploadDir());

        // Prepare the Maim Image (fetured image)
        $imgfile = imgFullPath($args['f_img']);
        $main_img_id = prepare_product_image($imgfile);

        // generate and prepare images for wp use
        $all_imgs = imgsList();
        // set 6 imgs
        $product_imgs='';
        for ($i=2; $i<8; $i++) {
            $imgfile = imgFullPath($all_imgs[$i]);
            $img_id = prepare_product_image($imgfile);
            $product_imgs .= $img_id .',';
        }
       /** END Img preperation */ 
        
        
       // set some price
        $price = rand(100, 500); 
        $onSale = $args['onSale'];
        $sale_price = 1 == $onSale ? $price - rand(50, 100) : 0;
        
        // Add the post
        $post_id = wp_insert_post( array(
            'post_status'   => 'publish',
            'post_type'     => 'products',
            'post_title'    => $pr_title,
            'tax_input'     => array('department' => 13), 
            'meta_input'    => array(
                '_product_images_ids' => $product_imgs,
                '_products_description' => $description,
                '_yt_vid_url' => $yt_url,
                '_products_price' => $price,
                '_products_sale_price' => $sale_price,
                '_is_onsale' => $onSale,
                '_thumbnail_id' => $main_img_id, 
            ),
        ));
        $taxo_arr = wp_set_post_terms( $post_id, [13, 14], 'department' );
    }
}

/** FUNCTIONS - for this part */

// list all products images (defaults)
function imgsList() {
    $theme_img_path = CHILD_DIR . '\includes\product-images';
    $imgsList = array_diff(scandir( $theme_img_path ),array('..', '.'));
    return $imgsList;
}

// prepare uplosd dir path
function imgUploadDir() {
    $upload = wp_upload_dir() ;
    $upload_dir = $upload['basedir'] . '/productImgs';
    return $upload_dir;
}
// prepare vars: $imgFullPath, $imgTitle,
function imgFullPath($imgBasename) {
    $upload_dir = imgUploadDir();
    // get basename with ext. - returns full path to uploads file
    return $upload_dir .'/'. $imgBasename;
}

function imgTitle($imgFile) { 
    // gets img path or basename and returns name without ext.
    $imgPathinfo = pathinfo($imgFile);
    return $imgPathinfo['filename'];
}

/** Register new images as attachment post (after valitating exsistents) */
function prepare_product_image($imgFullPath) {
    $imgTitle = imgTitle($imgFullPath);

    // if the image exsists -> return the id
    $is_attach = get_page_by_title( $imgTitle, 'OBJECT' , 'attachment' );
    if($is_attach) {
        return $is_attach->ID;
    }

    // get the file type
    $wp_filetype = wp_check_filetype($imgFullPath, null);
    // prepare attachment args
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $imgTitle,
        'post_content' => '',
        'post_status' => 'inherit'
    );
    // Insert the attachment
    $attach_id = wp_insert_attachment( $attachment, $imgFullPath );
  
    // genereate wp metadata for attachment
    $attach_data = wp_generate_attachment_metadata( $attach_id, $imgFullPath );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;
}

/** Copy images for creating new products. 
 * better put them in upload dir.
 * Should be called  once cretating the new products.  */
function copy_product_imgs($path) {

    // get all the imgs files names
    $imgs = imgsList();

    // create the dir for products pics
    $upload_dir = imgUploadDir();
    if ( !is_dir( $upload_dir ) ) {
        mkdir( $upload_dir );       
    }
    
    // move the images.
    foreach( $imgs as $file ) {
        $orig_file = $path .'/'. $file;
        $dest_file = $upload_dir . '/' . $file;
        if (!file_exists( $dest_file)) {
            $result = copy( $orig_file , $dest_file);
        }   
    }
}


 /** 
  * Products grid list for home page
  * After adding the products this is going to insert 
  * them into the home page  - using a shortcode [products_grid]
  */    
add_filter('the_content', 'products_grid');

function products_grid($content) {

    if ( is_home() || is_front_page() ) {

        echo '<h3>Products</h3>';
        // add products_grid shortcode to the home page.
        $products = do_shortcode( '[products_grid]');
        
        // first add the products grid then rest of homepage content
        return $products . $content;
    }
}



