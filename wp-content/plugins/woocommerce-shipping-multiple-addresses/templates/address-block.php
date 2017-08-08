<div class="address-block">
<?php

$addr = array(
    'first_name'    => $address['shipping_first_name'],
    'last_name'     => $address['shipping_last_name'],
    'company'       => $address['shipping_company'],
    'address_1'     => $address['shipping_address_1'],
    'address_2'     => $address['shipping_address_2'],
    'city'          => $address['shipping_city'],
    'state'         => $address['shipping_state'],
    'postcode'      => $address['shipping_postcode'],
    'country'       => $address['shipping_country']
);
$formatted_address = $woocommerce->countries->get_formatted_address( $addr );

if (!$formatted_address)
    _e( 'You have not set up a shipping address yet.', 'woocommerce' );
else
    echo '<address>'.$formatted_address.'</address>';

$edit_link      = add_query_arg( array('address-form' => 1, 'edit' =>  $idx) ) . '#add_address_form';
$delete_link    = add_query_arg( array( 'address-delete' => 1, 'id' => $idx) );
?>
    <div class="buttons">
        <a class="button" href="<?php echo $edit_link; ?>"><span class="auxiliar"><?php _e('Editar', 'wc_shipping_multiple_address'); ?></span></a>
        <a class="button" href="<?php echo $delete_link; ?>"><span class="auxiliar"><?php _e('Eliminar', 'wc_shipping_multiple_address'); ?></span></a>
    </div>

</div>