<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
global $flatsome_opt;
global $woocommerce;
?>

<?php foreach ( $messages as $message ) : ?>
	<div class="row" id="DeleteMoveToDown">
	<div class="large-12 columns" id="moveToDown">
		<div class="woocommerce-message message-success">
			<?php echo wp_kses_post( $message ); ?>
            <a class="AddBagButton" href="<?php echo ($woocommerce->cart->get_cart_url()); ?>"><div class="auxiliar">Ver Bolsa <img src="<?php echo get_template_directory_uri(); ?>/images/icons/black_bag.png" alt="Ver bolsa"></div></a>
			<?php // Dropdown cart if product is added  // ?>
			<?php if($flatsome_opt['cart_dropdown_show'] && is_product()) { ?>  
			    <script>
                    jQuery('.woocommerce-message a').addClass("deletMe");
                    jQuery('.woocommerce-message .AddBagButton').removeClass("deletMe");
                    jQuery('.woocommerce-message .deletMe').remove();
                    jQuery('.mini-cart').addClass('active cart-active');
                    jQuery('#main-content').click(function(){ jQuery('.mini-cart').removeClass('active cart-active');});
                    jQuery('.mini-cart').hover(function(){jQuery('.cart-active').removeClass('cart-active');});
                    setTimeout(function(){jQuery('.cart-active').removeClass('active')}, 6000);
			    </script>
			<?php } ?>
		</div>
	</div>
	</div>
<?php endforeach; ?>