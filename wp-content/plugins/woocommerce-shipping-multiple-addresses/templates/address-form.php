<?php

if ( empty($addresses) ) {
//    echo '<p>'. __('No address on file. Please add one below.', 'wc_shipping_multiple_address') .'</p>';
} else {

    $addresses = $this->array_sort( $addresses, 'shipping_first_name', SORT_ASC );
    /* @var $woocommerce Woocommerce */

    echo '<div class="address-container">';
    foreach ( $addresses as $idx => $address ) {

        include 'address-block.php';

    }
    echo '</div>';

}

?>


<?php
$address_id = '-1';
$address    = array();

if ( isset($_GET['edit']) ):
    $address_id = intval($_GET['edit']);
    $address    = $addresses[ $address_id ];

?>
    <h2 class="addressH2"><?php _e('Editar dirección de envío', 'wc_shipping_multiple_address'); ?></h2>
<?php else: ?>
    <h2 class="addressH2"><?php _e('Agregar dirección de envío', 'wc_shipping_multiple_address'); ?></h2>
<?php endif; ?>

<form id="add_address_form">
    <div class="shipping_address address_block" id="shipping_address">
        <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

        <div class="address-column">
            <?php
            foreach ($shipFields as $key => $field) :
                $val    = (isset($address[$key])) ? $address[$key] : '';
                $key    = 'address['. $key .']';
                $id     = rtrim( str_replace( '[', '_', $key ), ']' );
                $field['return'] = true;

                echo str_replace( 'id="'. $key .'"', 'id="'. $id .'"', woocommerce_form_field( $key, $field, $val ) );
            endforeach;

            do_action('woocommerce_after_checkout_shipping_form', $checkout);
            ?>
            <input type="hidden" name="id" id="address_id" value="<?php echo $address_id; ?>" />
            <input type="hidden" name="return" value="list" />
        </div>

    </div>

    <?php if ( $address_id > -1 ): ?>
        <p class="auxliarInput"><input type="submit" class="button alt" id="use_address" value="<?php _e('Actualizar dirección de envío', 'wc_shipping_multiple_address'); ?>" /></p>
    <?php else: ?>
        <p class="auxliarInput"><input type="submit" class="button alt" id="use_address" value="<?php _e('Guardar Dirección', 'wc_shipping_multiple_address'); ?>" /></p>
    <?php endif; ?>

</form>