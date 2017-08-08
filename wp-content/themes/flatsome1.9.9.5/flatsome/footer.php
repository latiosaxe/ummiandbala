<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>

</div><!-- #main-content -->


<footer class="footer-wrapper" role="contentinfo">	
<?php if(isset($flatsome_opt['html_before_footer'])){
	// BEFORE FOOTER HTML BLOCK
	echo do_shortcode($flatsome_opt['html_before_footer']);
} ?>


<!-- FOOTER 1 -->
<?php if ( is_active_sidebar( 'sidebar-footer-1' ) ) : ?>
<div class="footer footer-1 <?php echo $flatsome_opt['footer_1_color']; ?>"  style="background-color:<?php echo $flatsome_opt['footer_1_bg_color']; ?>">
	<div class="row">
   		<?php dynamic_sidebar('sidebar-footer-1'); ?>        
	</div><!-- end row -->
</div><!-- end footer 1 -->
<?php endif; ?>


<!-- FOOTER 2 -->
<?php if ( is_active_sidebar( 'sidebar-footer-2' ) ) : ?>
<div class="footer footer-2 <?php echo $flatsome_opt['footer_2_color']; ?>" style="background-color:<?php echo $flatsome_opt['footer_2_bg_color']; ?>">
	<div class="row">

   		<?php dynamic_sidebar('sidebar-footer-2'); ?>        
	</div><!-- end row -->
</div><!-- end footer 2 -->
<?php endif; ?>


<?php if(isset($flatsome_opt['html_after_footer'])){
	// AFTER FOOTER HTML BLOCK
	echo do_shortcode($flatsome_opt['html_after_footer']);
} ?>


<div class="absolute-footer <?php echo $flatsome_opt['footer_bottom_style']; ?>" style="background-color:<?php echo $flatsome_opt['footer_bottom_color']; ?>">
<div class="row">
	<div class="large-12 columns">
		<div class="left">
			 <?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php  
						wp_nav_menu(array(
							'theme_location' => 'footer',
							'menu_class' => 'footer-nav',
							'depth' => 1,
							'fallback_cb' => false,
						));
				?>						
			<?php endif; ?>
		<div class="copyright-footer">2014 Ummi&Bala Todos los derechos reservados</div>
<!--		<div class="copyright-footer">--><?php //if(isset($flatsome_opt['footer_left_text'])) {echo $flatsome_opt['footer_left_text'];} else{ echo 'Define left footer text / navigation in Theme Option Panel';} ?><!--</div>-->
		</div><!-- .left -->
		<div class="right">
				<?php if(isset($flatsome_opt['footer_right_text'])){ echo do_shortcode($flatsome_opt['footer_right_text']);} else {echo 'Define right footer text in Theme Option Panel';} ?>
		</div>
	</div><!-- .large-12 -->
</div><!-- .row-->
</div><!-- .absolute-footer -->
</footer><!-- .footer-wrapper -->
</div><!-- #wrapper -->

<!-- back to top -->
<a href="#top" id="top-link"><span class="icon-angle-up"></span></a>
<div class="scroll-to-bullets"></div>

<?php if(isset($flatsome_opt['html_scripts_footer'])){
	// Insert footer scripts
	echo $flatsome_opt['html_scripts_footer'];
} ?>

<div class="mob-helper"></div>
<?php wp_footer(); ?>
<style type="text/css">
    .sliderNav.small a{
        background: transparent !important;
    }
    .header-search, strong.cart-name, span.cart-price, ul.header-nav a.nav-top-link{
        color: #677471 !important;
        opacity: 1 !important;
    }

    .woocommerce-message {color: #677471 !important;}
    .woocommerce-message:before,.woocommerce-message:after{color: #FFF!important; background-color:#677471!important }
</style>
</body>
</html>