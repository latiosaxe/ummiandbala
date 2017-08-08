<?php

    /**
     * The template for displaying lookbook product style content within loops.
     *
     * Override this template by copying it to yourtheme/woocommerce/content-product.php
     *
     * @author      WooThemes
     * @package     WooCommerce/Templates
     * @version     1.6.4
     */

 
    global $post, $product, $flatsome_opt;

    // Get category permalink
    $permalinks     = get_option( 'woocommerce_permalinks' );
    $category_slug  = empty( $permalinks['category_base'] ) ? _x( 'product-category', 'slug', 'woocommerce' ) : $permalinks['category_base'];
 
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>> 
    
<div class="row">

    <div class="large-12 columns moveAfterMe">
        <div class="breadcrumb-row pleca_uandb">
            <div class="corner-left"></div>
            <div class="corner-right"></div>
            <div class="right">
                <?php
                do_action( 'woocommerce_after_shop_loop' );
                ?>

                <div class="next-prev-nav">
                    <?php // edit this in inc/template-tags.php // ?>
                    <?php next_post_link_product('%link', 'icon-angle-left next', true); ?>
                    <?php previous_post_link_product('%link', 'icon-angle-right prev', true); ?>
                </div>

                <?php dynamic_sidebar('product-sidebar'); ?>
            </div>
        </div><!-- .breadcrumb-row -->
    </div><!-- .large-12 breadcrumb -->


    <?php
            /**
             * woocommerce_before_single_product hook
             *
             * @hooked woocommerce_show_messages - 10
             */
             do_action( 'woocommerce_before_single_product' );
        ?>    
        <div class="large-4 columns product-gallery">
            <div class="product-image">
                <div class="front-image overflow">

                    <?php
                    /**
                     * woocommerce_show_product_images hook
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    do_action( 'woocommerce_before_single_product_summary' );
                    ?>
                </div>
            </div>
        
        </div><!-- end large-6 - product-gallery -->
        
        <div class="product-info large-5 small-12 columns left">
                <?php
                    /**
                     * woocommerce_single_product_summary hook
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked ProductShowReviews() (inc/template-tags.php) - 15
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     */
                    do_action( 'woocommerce_single_product_summary' );
                ?>
        
        </div><!-- end product-info large-4 -->

        <div class="large-3 small-12 columns also_love">
            <div class="borderLeft">
                <h3>También te encantará</h3>
                <?php
    //            do_action( 'woocommerce_after_single_product_summary' );
                $related = $product->get_related($limit=2);

                if ( sizeof($related) == 0 ) return;

                $args = apply_filters('woocommerce_related_products_args', array(
                    'post_type'				=> 'product',
                    'ignore_sticky_posts'	=> 1,
                    'no_found_rows' 		=> 1,
                    'posts_per_page' 		=> $posts_per_page,
                    'orderby' 				=> $orderby,
                    'post__in' 				=> $related
                ) );

                $products = new WP_Query( $args );

                $woocommerce_loop['columns'] 	= $columns;

                if ( $products->have_posts() ) : ?>

                    <div class="related products">
                        <ul class="products">
                            <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <?php woocommerce_get_template_part( 'content', 'product' ); ?>
                            <?php endwhile; // end of the loop. ?>
                        </ul>
                    </div>
                    <?php endif;
                wp_reset_postdata();
                ?>
            </div>

        </div><!-- .product-page-aside -->
     
        
</div><!-- end row -->
    
    
<?php
    //Get the Thumbnail URL for pintrest
    $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
?>


    
<div class="row">
    <div class="large-12 columns">
        <div class="product-details <?php echo $flatsome_opt['product_display']; ?>-style">
               <div class="row">

                    <div class="large-12 columns ">
                    <?php woocommerce_get_template('single-product/tabs/tabs.php'); ?>
                    </div><!-- .large-9 -->
                
               </div><!-- .row -->
        </div><!-- .product-details-->

        <hr/><!-- divider -->
    </div><!-- .large-12 -->
</div><!-- .row -->


    <div class="related-product">

    </div><!-- related products -->

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>