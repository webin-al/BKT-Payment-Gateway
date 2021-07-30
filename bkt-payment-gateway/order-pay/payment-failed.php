<?php

add_action( 'woocommerce_pay_order_before_submit', 'bkt_error_try_again', 10, 1 );

    function bkt_error_try_again( $order_id ) {

    echo '<br><br><strong>Payment unsuccessful, please try again or contact us!</strong><br><br>';

    }