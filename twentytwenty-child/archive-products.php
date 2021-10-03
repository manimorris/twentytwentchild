<?php


get_header(); ?>


<!-- Header -->
<header class=" text-center ">
  <div class="container">
    <h1 class="fw-light "><?php echo get_queried_object()->label; ?></h1>
  </div>
</header>

<!-- Page Content -->
  <div class="container grid">
    <div class="row"> 
    
    <?php 
    // Here all the posts will be called
    if(have_posts()) : while(have_posts()) : the_post(); 

      get_template_part( 'includes/products-archive-content' ,
           'archive product_content' , array( 'productObj' => $post)  );
    
    endwhile; endif; 
    ?>
    </div>
  </div>
  <!-- /.container -->

<?php get_footer(); ?>

