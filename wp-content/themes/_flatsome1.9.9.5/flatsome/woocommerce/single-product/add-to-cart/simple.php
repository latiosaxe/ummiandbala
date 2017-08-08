<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product;

if ( ! $product->is_purchasable() ) return;
?>

<?php
	// Availability
	$availability = $product->get_availability();

	if ($availability['availability']) :
		echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>', $availability['availability'] );
    endif;
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action('woocommerce_before_add_to_cart_form'); ?>
<hr class="product">
<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>


	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

    <?php

    if($product->get_stock_quantity() == 1){
        ?>
        <fieldset>
            <span style="text-align: center">Por el momento contamos con un solo producto disponible</span>
            <input type="text" disabled="disabled" value="1" class="input_just_one">
        </fieldset>
        <?php
    }else{
        ?>
        <fieldset>
            <span>Cantidad</span>
            <?php
            if ( ! $product->is_sold_individually() )
                woocommerce_quantity_input( array(
                    'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                    'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
                ) );
            ?>
        </fieldset>
        <?php
    }
    ?>

    <fieldset>

	 	<button type="submit" class="single_add_to_cart_button button secondary">
             <span class="auxiliar">Añadir a mi bolsa
                <img src="http://ummiandbala.com.mx/shop/wp-content/themes/flatsome1.9.9.5/flatsome/images/icons/bolsita.png" alt="Añadir a la bolsa">
             </span>
         </button>

    </fieldset>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

    <hr class="product">
	<?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>