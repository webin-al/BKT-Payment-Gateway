<?php

global $woocommerce;
$order = new WC_Order( $order_id );

if($order->get_payment_method() == "bkt_payment_gateway") {

// allow pending orders without login when using this gateway, required to view API response
    add_filter('user_has_cap', 'pay_without_login', 9999, 3);

}
 
    function pay_without_login( $allcaps, $caps, $args ) {

            if (isset($caps[0], $_GET['key'])) {
                if ($caps[0] == 'pay_for_order') {
                    $order_id = isset($args[2]) ? $args[2] : null;
                    $order = wc_get_order($order_id);
                    if ($order) {
                        $allcaps['pay_for_order'] = true;
                    }
                }
            }
            return $allcaps;

    }