<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $flatsome_opt;

wc_print_notices();

?>

<?php do_action( 'woocommerce_before_cart' ); ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".entry-header").remove();
        jQuery(".page-wrapper").css({'padding-top':0});
        jQuery(".woocommerce-message a").remove();

//        jQuery("input").change(function(){
//            jQuery("#update_cart").trigger('click');
//        });
    });
</script>
<style type="text/css">
    .woocommerce-message {
        margin: 20px 0 10px 0 !important;
    }
</style>
<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">

<div class="row">
    <div class="large-12 columns">
        <div class="breadcrumb-row pleca_uandb text-center">
            <div class="corner-left"></div>
            <div class="corner-right"></div>
            <h1 class="margin_0">Bolsa de Compras</h1>
        </div><!-- .breadcrumb-row -->
    </div>
</div>
<div class="row">
<div class="large-10 small-12 columns cartMarginRight">

    <?php do_action( 'woocommerce_before_cart_table' ); ?>
    <div class="row">
        <div class="large-12 small-12">

            <div class="cart-wrapper">
                <table class="shop_table cart responsive" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-name" colspan="3"><?php _e( 'Product', 'woocommerce' ); ?></th>
                        <th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
                        <th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                        <th class="product-quantity"><a href="#">Envoltura para Regalo</a></th>
                        <th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>


                    <?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            ?>
                        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <td class="remove-product">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><span class="icon-close"></span></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
                                ?>
                            </td>

                            <td class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', str_replace( array( 'http:', 'https:' ), '', $_product->get_image() ), $cart_item, $cart_item_key );

                                if ( ! $_product->is_visible() )
                                    echo $thumbnail;
                                else
                                    printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                                ?>
                            </td>

                            <td class="product-name">
                                <?php
                                if ( ! $_product->is_visible() )
                                    echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                                else
                                    echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

                                // Meta data
                                echo WC()->cart->get_item_data( $cart_item );

                                // Backorder notification
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                    echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
                                ?>
                            </td>

                            <td class="product-price">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                ?>
                            </td>

                            <td class="product-quantity">
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                                ?>
                            </td>

                            <td class="product-url">
                                <?php


                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart-gift[{$cart_item_key}][qty]",
                                        'input_value' => 0,
                                        'max_value'   => $cart_item['quantity'],
//                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                    ), $_product, false );

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                                ?>
                            </td>

                            <td class="product-subtotal">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                ?>
                            </td>
                        </tr>
                            <?php
                        }
                    }

                    do_action( 'woocommerce_cart_contents' );
                    ?>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>



                <?php do_action('woocommerce_cart_collaterals'); ?>


            </div><!-- .cart-wrapper -->
        </div>
    </div>

    <div class="row borderTopBottom">
        <div class="large-7 small-12 columns verticalAlign">
            <div class="cart-sidebar">
                <?php if ( WC()->cart->coupons_enabled() ) { ?>
                <div class="coupon">
                    <!--                            <h3 class="widget-title">--><?php //_e( '', 'woocommerce' ); ?><!--</h3>-->
                    <p class="text-center">Coupon de descuento</p>
                    <input type="text" name="coupon_code"  id="coupon_code" value="" placeholder="<?php _e( 'Ingresa el cupón', 'flatsome' ); ?>"/>
                    <div class="auxiliar">
                        <input type="submit" class="button small expand" name="apply_coupon" value="<?php _e( 'Aplicar cupón', 'woocommerce' ); ?>" />
                    </div>
                    <?php do_action('woocommerce_cart_coupon'); ?>

                </div>
                <?php } ?>


                <?php woocommerce_shipping_calculator(); ?>



            </div><!-- .cart-sidebar -->

        </div><!-- .large-3 -->
        <div class="large-5 small-12 columns verticalAlign auxBorder">
            <div class="cart-sidebar">
                <?php woocommerce_cart_totals(); ?>
            </div><!-- .cart-sidebar -->

        </div><!-- .large-3 -->
        <!--                <div class="row">-->
        <!--                </div>-->
    </div>
    <div class="row">
        <div class="large-12 finalStepCart">
            <div class="auxiliar yeah"><input type="submit" class="checkout-button secondary expand button" name="proceed" value="<?php _e( 'Realizar pedido', 'woocommerce' ); ?>" /></div>
            <div class="auxiliar"><input type="submit" class="button expand" name="update_cart" id="update_cart" value="<?php _e( 'Actualizar bolsa', 'woocommerce' ); ?>" /></div>
            <div class="auxiliar"><a href="javascript:history.back()">Seguir comprando</a></div>
            <?php do_action('woocommerce_proceed_to_checkout'); ?>
            <?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </div>
    </div>

</div><!-- .large-9 -->


<div class="large-2 small-12 columns" style="padding-left: 30px;">
    <p>
        <a href="<?php echo site_url(); ?>/forma-de-pago/" target="_blank" class="CartButton ShotFancy">
            <span class="auxiliar">
                Forma de pago
            </span>
        </a>
    </p>
    <p>
        <a href="<?php echo site_url(); ?>/envio/" target="_blank" class="CartButton ShotFancy">
            <span class="auxiliar">
                <ins>
                    Envío
                </ins>
            </span>
        </a>
    </p>
    <p>
        <a href="<?php echo site_url(); ?>/devoluciones/" target="_blank" class="CartButton ShotFancy">
<!--        <a href="--><?php //echo site_url(); ?><!--/terminos-y-condiciones/" target="_blank" class="CartButton ShotFancy">-->
            <span class="auxiliar">
                <ins>
                    Devoluciones
                </ins>
            </span>
        </a>
    </p>
</div>
</div><!-- .row -->

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>

<div class="Shootfancy hidden">
    <div class="new_facy" id="auxHeightFancy">
        <div class="close_fancy">
            <div class="auxiliar">
                <p>X</p>
            </div>
        </div>
        <div class="content">
            <div class="fancyScroll" id="fancyScroll">
                <div class="here" id="FancyHere">
                    <p class='text-center'>Cargando...</p>
                </div>
            </div>
            <div class="image"></div>
        </div>
    </div>
</div>