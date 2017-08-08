<?php
/*
 * Plugin Name:  Example Modify Price
 */
class Example_Modify_Price {
    private static $instance;
    public static function register() {
        if (self::$instance == null) {
            self::$instance = new Example_Modify_Price();
        }
    }
    public function __construct() {
        // Add to cart
        add_filter('woocommerce_add_cart_item', array($this, 'add_cart_item'), 10, 1);
        // Add item data to the cart
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 2);
        // Load cart data per page load
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'get_cart_item_from_session'), 10, 2);
        // Validate when adding to cart
        add_filter('woocommerce_add_to_cart_validation', array($this, 'validate_add_cart_item'), 10, 3);
        // Add meta to order
        add_action('woocommerce_order_item_meta', array($this, 'order_item_meta'), 10, 2);
        // Add meta to order - WC 2.x
        add_action('woocommerce_add_order_item_meta', array($this, 'order_item_meta_2'), 10, 2);
    }
    /**
     * add_cart_item function.
     *
     * @access public
     * @param mixed $cart_item
     * @return void
     */
    function add_cart_item($cart_item) {
        $extra_cost = 10.00;
        $_POST['cart-gift[][qty]'];

        $cart_item['data']->adjust_price($extra_cost);
        return $cart_item;
    }
    /**
     * get_cart_item_from_session function.
     *
     * @access public
     * @param mixed $cart_item
     * @param mixed $values
     * @return void
     */
    function get_cart_item_from_session($cart_item, $values) {
        $cart_item = $this->add_cart_item($cart_item);
        return $cart_item;
    }
    /**
     * order_item_meta function.
     *
     * @access public
     * @param mixed $item_meta
     * @param mixed $cart_item
     * @return void
     */
    function order_item_meta($item_meta, $cart_item) {
        $item_meta->add('Your Meta Name', 'Your Meta Value');
    }
    /**
     * order_item_meta_2 function.
     *
     * @access public
     * @param mixed $item_id
     * @param mixed $values
     * @return void
     */
    function order_item_meta_2($item_id, $values) {
        if (function_exists('woocommerce_add_order_item_meta')) {
            woocommerce_add_order_item_meta($item_id, 'Your Meta Name', 'Your Meta Value');
        }
    }
}
Example_Modify_Price::register();

?>