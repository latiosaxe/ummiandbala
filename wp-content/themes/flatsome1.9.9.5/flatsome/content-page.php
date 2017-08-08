<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package flatsome
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
            <div class="breadcrumb-row pleca_uandb text-center">
                <div class="corner-left"></div>
                <div class="corner-right"></div>
                <h1 class="margin_0"><?php the_title(); ?></h1>
            </div><!-- .breadcrumb-row -->
<!--		<h1 class="entry-title"></h1>-->
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'flatsome' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
