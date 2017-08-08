<?php
/*
Template name: Discounted products
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $flatsome_opt;
get_header('shop'); ?>

<div class="cat-header">
    <?php
// GET CUSTOM HEADER CONTENT FOR CATEGORY
    if(function_exists('get_term_meta')){
        $queried_object = get_queried_object();

        if (isset($queried_object->term_id)){

            $term_id = $queried_object->term_id;
            $content = get_term_meta($term_id, 'cat_meta');

            if(isset($content[0]['cat_header'])){
                echo do_shortcode($content[0]['cat_header']);
            }
        }
    }
    ?>
    <?php if(isset($flatsome_opt['html_shop_page']) && is_shop()) {
    // Add Custom HTML for shop page header
        if($wp_query->query_vars['paged'] == 1 || $wp_query->query_vars['paged'] < 1){
            echo do_shortcode($flatsome_opt['html_shop_page']);
        }
    }
    ?>
</div>

<div class="page-header">
    <?php if( has_excerpt() ) the_excerpt();?>
</div>

<div  class="page-wrapper page-featured-item">
    <div class="row">


<!--    <div class="large-12 columns">-->
<!--        <div class="breadcrumb-row pleca_uandb">-->
<!--            <div class="corner-left"></div>-->
<!--            <div class="corner-right"></div>-->
<!--            <div class="left">-->
<!--                <p class="order_by">Ordenar por:</p>-->
<!--                --><?php //if ( have_posts() ) : do_action( 'woocommerce_before_shop_loop' ); ?><!----><?php //endif; ?>
<!--            </div>-->
<!--            <div class="right">-->
<!--                --><?php
//                do_action( 'woocommerce_after_shop_loop' );
//                ?>
<!---->
<!--                <div class="next-prev-nav">-->
<!--                    --><?php //// edit this in inc/template-tags.php // ?>
<!--                    --><?php //next_post_link_product('%link', 'icon-angle-left next', true); ?>
<!--                    --><?php //previous_post_link_product('%link', 'icon-angle-right prev', true); ?>
<!--                </div>-->
<!---->
<!--                --><?php //dynamic_sidebar('product-sidebar'); ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <header class="entry-header">
        <div class="breadcrumb-row pleca_uandb text-center">
            <div class="corner-left"></div>
            <div class="corner-right"></div>
            <h1 class="margin_0">Productos en promoción</h1>
        </div><!-- .breadcrumb-row -->
        <!--		<h1 class="entry-title"></h1>-->
    </header>

        <div id="content" class="large-12 columns" role="main">
            <div class="item-intro">
                <?php while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
                <?php endwhile; // end of the loop. ?>
            </div>


            <ul class="large-block-grid-4 small-block-grid-2">
                <?php
                global $flatsome_opt;
                $temp = $wp_query;
                $wp_query= null;
                $post_counter = 0;
                $wp_query = new WP_Query(array(
                    'post_type' => 'product',
                    'stock' => 1,
                    'showposts' => 12,
                    'order' => 'DESC' ,
                    'meta_query'  => array(
                        array(
                            'key' => '_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'numeric'
                        )
                    ),
                    'posts_per_page' => $flatsome_opt['featured_items_pr_page'],
                    'orderby'=> 'menu_order',
                    'paged'=>$paged
                ));
                while ($wp_query->have_posts()) : $wp_query->the_post();
                    $post_counter++;
                    ?>

                    <li <?php $classes[] = 'product-small  grid1'; post_class( $classes ); ?>>
                        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
                        <a href="<?php the_permalink(); ?>">
                            <div class="product-image hover_<?php echo $flatsome_opt['product_hover']; ?>">
                                <div class="front-image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog') ?></div>
                                <?php if($flatsome_opt['product_hover'] == "fade_in_back" || !isset($flatsome_opt['product_hover'])) { ?>
                                <?php
                                if ( $attachment_ids ) {
                                    $loop = 0;
                                    foreach ( $attachment_ids as $attachment_id ) {
                                        $image_link = wp_get_attachment_url( $attachment_id );
                                        if ( ! $image_link )
                                            continue;
                                        $loop++;
                                        printf( '<div class="back-image back">%s</div>', wp_get_attachment_image( $attachment_id, 'shop_catalog' ) );
                                        if ($loop == 1) break;
                                    }
                                } else {
                                    ?>
                                    <div class="back-image">
                                        <div class="auxiliar">
                                            <div class="icon">
                                                <div href="<?php the_permalink(); ?>" rel="nofollow" class="tip-top add-to-cart-grid" title="Ver articulos">
                                                    <img src="http://ummiandbala.com.mx/shop/wp-content/themes/flatsome1.9.9.5/flatsome/images/icons/product.png" alt="Agregar a la bolsa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php } else { ?>
                                <div class="back-image">
                                    <div class="auxiliar">
                                        <div class="icon">
                                            <div href="<?php the_permalink(); ?>" rel="nofollow" class="tip-top view-product-grid" title="Ver articulo">
                                                <img src="http://ummiandbala.com.mx/shop/wp-content/themes/flatsome1.9.9.5/flatsome/images/icons/product.png" alt="Agregar a la bolsa">
                                            </div>
                                        </div>
                                        <!--                        <div class="icon" alt="Ver articulo"></div>-->

                                    </div>
                                </div>
                                <?php } ?>
                                <!--          <div class="quick-view" data-prod="--><?php //echo $post->ID; ?><!--">+ --><?php //_e('Vista rápida','flatsome'); ?><!--</div>-->

                                <?php if($stock_status == "1") { ?><div class="out-of-stock-label"><?php _e( 'Out of stock', 'woocommerce' ); ?></div><?php }?>

                                <?php if($flatsome_opt['add_to_cart_icon'] == "show") :
                                $link = array(
                                    'url'   => '',
                                    'label' => '',
                                    'class' => ''
                                );

                                $handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

                                switch ( $handler ) {
                                    case "variable" :
                                        $link['url'] 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
                                        $link['label'] 	= apply_filters( 'variable_add_to_cart_text', __( 'Select options', 'woocommerce' ) );
                                        break;
                                    case "grouped" :
                                        $link['url'] 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
                                        $link['label'] 	= apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'woocommerce' ) );
                                        break;
                                    case "external" :
                                        $link['url'] 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
                                        $link['label'] 	= apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woocommerce' ) );
                                        break;
                                    default :
                                        if ( $product->is_purchasable() ) {
                                            $link['url'] 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
                                            $link['label'] 	= apply_filters( 'add_to_cart_text', __( 'Add to cart', 'woocommerce' ) );
                                            $link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
                                        } else {
                                            $link['url'] 	= apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
                                            $link['label'] 	= apply_filters( 'not_purchasable_text', __( 'Read More', 'woocommerce' ) );
                                        }
                                        break;
                                }
                                echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('
            <div href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s tip-top add-to-cart-grid" title="%s">

                    <img src="http://ummiandbala.com.mx/shop/wp-content/themes/flatsome1.9.9.5/flatsome/images/product_car.png" alt="Agregar a la bolsa">

            </div>
	    ', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), esc_html( $link['label'] ) ), $product, $link );
                                ?>
                                <?php endif; ?>
                            </div><!-- end product-image -->

                            <?php
                            // GRID STYLE 1
                            if(!isset($flatsome_opt['grid_style']) || $flatsome_opt['grid_style'] == "grid1"){ ?>

                                <div class="info text-center">
                                    <?php $product_cats = strip_tags($product->get_categories('|', '', '')); ?>
                                    <h5 class="category"><?php list($firstpart) = explode('|', $product_cats); echo $firstpart; ?></h5>
                                    <div class="tx-div small"></div>
                                    <p class="name"><?php the_title(); ?></p>
                                    <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
                                    <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                    <?php } ?>
                                </div><!-- end info -->

                                <?php }
                            // GRID STYLE 2
                            else if($flatsome_opt['grid_style'] == "grid2") { ?>

                                <div class="info style-<?php echo $flatsome_opt['grid_style']; ?>">
                                    <p class="name"><?php the_title(); ?></p>
                                    <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
                                    <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                    <?php } ?>
                                </div><!-- end info -->

                                <?php }
                            // GRID STYLE 3
                            else if($flatsome_opt['grid_style'] == "grid3") { ?>

                                <div class="info style-<?php echo $flatsome_opt['grid_style']; ?>">
                                    <table>
                                        <tr>
                                            <td>
                                                <?php $product_cats = strip_tags($product->get_categories('|', '', '')); ?>
                                                <p class="name"><?php the_title(); ?></p>
                                                <h5 class="category"><?php list($firstpart) = explode('|', $product_cats); echo $firstpart; ?></h5>

                                                <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
                                                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                                                <?php } ?></td>
                                            <td><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div><!-- end info -->

                                <?php } ?>
                        </a>

                        <?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
                    </li><!-- end product -->
                    <?php endwhile; // end of the loop. ?>

            </ul>
            <!-- PAGINATION -->
            <div class="large-12 columns">
                <div class="pagination-centered">
                    <?php
                    echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
                        'base' 			=> str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
                        'format' 		=> '',
                        'current' 		=> max( 1, get_query_var('paged') ),
                        'total' 		=> $wp_query->max_num_pages,
                        'prev_text' 	=> '<span class="icon-angle-left"></span>',
                        'next_text' 	=> '<span class="icon-angle-right"></span>',
                        'type'			=> 'list',
                        'end_size'		=> 3,
                        'mid_size'		=> 3
                    ) ) );
                    ?>

                </div><!--  end pagination container -->
            </div><!-- end large-12 -->
            <!-- end PAGINATION -->

            <?php $wp_query = null; $wp_query = $temp;?>


        </div><!-- end #content large-12  -->

    </div><!-- end row -->
</div><!-- end portfolio container -->


<?php get_footer(); ?>
