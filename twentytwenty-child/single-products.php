<?php
/**
 * The template for displaying single post type :products.
 *
 * Products data:
 * 	- post basic:
 * 		* title
 * 		* fetured image
 * 
 * 	- taxomony:	
 * 		* department (category)
 * 			** tax_name = department
 * 
 * 	- meta data
 * 		* product images - as a gallery
 * 			** key = _product_images_ids
 * 		* descrtiption
 * 			** key = _products_description
 * 		* youtube viedo - embed into description
 * 			** key = _yt_vid_url
 * 		* price
 * 			** key =_products_price
 * 		* sale price
 * 			** key = _products_sale_price
 * 		* is on sale	
 * 			** key = _is_onsale
 * 			*** if the product is on sale, then show the sale price in a badge
 * 
 *   - At the bottom:
 * 		* display of related item from same category
 */
?>

<?php


add_action( 'wp_head', function() {
	wp_register_style('single_product_style', get_stylesheet_directory_uri() . '/css/single-product.css');
	wp_enqueue_style( 'single_product_style');

	get_template_part( 'template/single-products-content',
	 'get_single_product_view.php'
	);
});

?>
<?php


get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) { 
			the_post();
			
			// prepare the images attached to product
			$imgs = get_post_meta( $post->ID, '_product_images_ids', true);
			$imgArr = explode(',', $imgs);
			// output the imgs to html elements
			$imgHtml ='';
			foreach($imgArr as $imgID) {
				$imgHtml .= '<a>' . wp_get_attachment_image($imgID, 'thumbnail') . '</a>';
			}


			?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

			<div id="title" class="text-center">
				<h2><?php the_title(); ?></h2>
			</div>
			
			<div class="container bootdey">
				<div class="col-md-12" >
				<section class="panel">
					<div class="panel-body">
						<div class="col-md-6 float-left">
							<div class="pro-img-details row">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>

							<div id="image-gallery" class=" row justify-content-md-center pro-img-list">
								<?php echo $imgHtml; ?>
							</div>

							<div class="product_meta">
								<span class="posted_in"> <strong>Department: </strong>
									<?php
										// get post terms
										$terms = wp_get_post_terms( $post->ID, 'department' );
										foreach($terms as $term) {
											$link = get_term_link( $term );
											echo "<a rel='tag' href='$link'>$term->name</a>, ";
										}
									?>								
							</div>
							<div class="m-bot15"> 
								<strong>Price : </strong> 
								<?php 
									// price area
									if( !get_post_meta( $post->ID, '_is_onsale', true )) { ?>
										<span class="pro-price"><strong>
											<?php the_postmeta('_products_price');   ?>
										</strong></span>
									<?php } else { ?>
										<span class="amount-old"><strong>
											<?php the_postmeta('_products_price'); ?>
										</strong></span> 
										<span class="pro-price"><strong>
											<?php the_postmeta('_products_sale_price'); ?>
										</strong></span>
									<?php
									}
								?>
							</div>
						</div>
						
						<div class="col-md-6 float-right mt-0">

							<div id='product-description'>
								<?php
									// Description 
									the_postmeta('_products_description'); 
									// Embed the products youtube video into the description
									$yt_vid_url = get_post_meta( $post->ID, '_yt_vid_url' , true );
									echo $GLOBALS['wp_embed']->run_shortcode( '[embed] '. $yt_vid_url .' [/embed]'); 
								?>
							</div>
							
						</div>
					</div>
				</section>
				</div>
			</div>
		</article><!-- .post -->
		<?php
		}
	}	

	?>

	<div id="products-footer" class="" style="display:inline-block;width:90%">
		<!-- Add products from same category in a row at the bottom -->
		<?php  ////////////////////////////////////////////////////
		//grid shortcode.
		
			if ( is_single() || get_post_type() == 'products' ) {
			?>
				<h5 style="text-align:center;">Related Products</h5>
			<?php
				// add products_grid shortcode to the home page.
				$grid_terms='';
				foreach ($terms as $term) {
					$grid_terms .= $term->term_id .",";
				}
				$products = do_shortcode( "[products_grid items='3' terms='$grid_terms']");
				
				// first add the products grid then rest of homepage content
				echo $products ;
			}
		/////////////////////////////////////////////////////////////////
		?>
	</div>

</main><!-- #site-content -->

<?php 
	//get_template_part( 'template-parts/footer-menus-widgets' );
	//do_shortcode( '[products_grid called="1"]');
?>

<?php get_footer(); ?>



