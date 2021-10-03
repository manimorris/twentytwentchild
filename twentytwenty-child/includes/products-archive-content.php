<?php
    /**
     * Archive components to be displayed in products-archive.php
     * This fucntion will return a list of all given post
     */
    $product = $args['productObj']; //This is reqiured.
?>
      
        <div class="col-xl-3 mb-3" style="position:relative;min-width:24vw;">
          <div class="card border-0 shadow">
            <a href="<?php the_permalink( $product->ID ); ?>"  class="link-dark" >
                <img src="<?php echo get_the_post_thumbnail_url($product->ID, 'small') ?>" class="card-img-top" alt="..." >
                <div id="product-price" class="card-body bg-light  text-center link-dark" >
                    <span id="badge" class="" 
                    style=" position:absolute !important; top:10px; left:5px; padding:5px;background-color:#ff4d4d; color:white;
                    transform:rotate(-20deg);font-size:700; font-style:italic;
                    <?php  echo get_post_meta( $product->ID, '_is_onsale', true ) ? '':'display:none' ; ?>;"
                    >On Sale!!</span>
                <h5 class="card-title mb-0 link-dark"><?php echo $product->post_title ?></h5>
                <!-- <div class="card-text text-black-50"><p>More information</p></div> -->
                </div>
            </a>
          </div>
        </div>

    <!-- End post  -->