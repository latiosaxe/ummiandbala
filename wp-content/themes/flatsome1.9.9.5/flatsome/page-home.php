<?php
/*
Template name: Home 1
*/
get_header(); ?>

<div class="page-header">
<?php if( has_excerpt() ) the_excerpt();?>
</div>

<div class="page-wrapper page-right-sidebar">
<div class="row">

<div id="content" class="large-12  columns" role="main">
	<div class="page-inner">

        <?php

        array(
            'per_page' => '12',
            'columns' => '4',
            'orderby' => 'title',
            'order' => 'asc',
            'category' => ''
        )
        ?>
        [product_category category="appliances"]

        <?php
        array(
            'per_page' => '12',
            'columns' => '4',
            'orderby' => 'title',
            'order' => 'asc'
        )
        ?>
        [top_rated_products per_page="12"]





	</div><!-- .page-inner -->
</div><!-- .#content large-9 left -->


</div><!-- .row -->
</div><!-- .page-right-sidebar container -->

<?php get_footer(); ?>
