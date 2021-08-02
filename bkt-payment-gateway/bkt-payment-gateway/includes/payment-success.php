<?php

add_action( 'woocommerce_thankyou', 'bkt_details_after_success_payment', 10, 1 );

    function bkt_details_after_success_payment( $order_id ) {

        global $woocommerce;
        $order = new WC_Order( $order_id );

    // Let's return some required data for the receipt if payment successful
    if(($_POST["ProcReturnCode"] == "00" && $order->get_payment_method() == "bkt_payment_gateway")) {

        // empty the shopping cart
        $woocommerce->cart->empty_cart();

        $order->update_status('completed', __( 'We received your payment, thank you!', 'woocommerce' ));

        // some notes to customer (replace true with false to make it private)
        $order->add_order_note( 'Order completed. Payment received!', true );

        // reduce the stock, if any
        $order->reduce_order_stock();

    $company = get_bloginfo( 'name' );
    $logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );
    $address = get_option( 'woocommerce_store_address' );
    $site_url = get_site_url();
    $email = get_option( 'admin_email' );

    $card = $_POST['MaskedPan'];
    $authcode = $_POST['AuthCode'];
    $transtype = $_POST['TxnType'];

    $billing_name = $_POST['BillingNameSurname'];
    $billing_email = $_POST['BillingEmail'];
    $billing_phone = $_POST['BillingPhone'];
    $billing_address = $_POST['BillingAddress'];

    echo "

    <h2 class='woocommerce-order-details__title'>Company details</h2>

    <ul class='woocommerce-order-overview woocommerce-thankyou-order-details order_details'>

    <li class='woocommerce-order-overview__order bkt'>
    Company:
    <strong>$company</strong></br>
    <img src='$logo' width='100' />
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Address:
    <strong>$address</strong>
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Website URL:
    <strong>$site_url</strong>
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Support:
    <strong>$email</strong>
    </li>
            
    </ul>

    <h2 class='woocommerce-order-details__title'>Payment details</h2>

    <ul class='woocommerce-order-overview woocommerce-thankyou-order-details order_details'>

    <li class='woocommerce-order-overview__order bkt'>
    Billing Details:
    <strong>$billing_name</strong>
    <strong>$billing_email</strong>
    <strong>$billing_phone</strong>
    <strong>$billing_address</strong>
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Transaction Type:
    <strong>$transtype</strong>
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Authorization Code:
    <strong>$authcode</strong>
    </li>

    <li class='woocommerce-order-overview__order bkt'>
    Last 4 digits of the card:
    <strong>$card</strong>
    </li>
            
    </ul>

    <p class='woocommerce-notice' style='text-decoration: underline;font-style: italic;'>
    *Read our Terms and Conditions for cancellations or refunds.
    </p>

    <hr>

    ";

    } // end if

}

    /**
     * Add "Print" link to Order Received page and View Order page
     */
    wp_register_style( 'bkt-css', home_url() . '/wp-content/plugins/bkt-payment-gateway/assets/css/style.css' );

    function bkt_print_receipt($order_id) {

    $company = get_bloginfo( 'name' );
    $logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );
    $address = get_option( 'woocommerce_store_address' );
    $site_url = get_site_url();
    $email = get_option( 'admin_email' );

    $card = $_POST['MaskedPan'];
    $authcode = $_POST['AuthCode'];
    $transtype = $_POST['TxnType'];

    $billing_name = $_POST['BillingNameSurname'];
    $billing_email = $_POST['BillingEmail'];
    $billing_phone = $_POST['BillingPhone'];
    $billing_address = $_POST['BillingAddress'];

        $get_data = 'OrderId='.$order_id.'&MaskedPan='.$card.'&AuthCode='.$authcode.'&TxnType='.$transtype.'&BillingNameSurname='.$billing_name.'&BillingEmail='.$billing_email.'&BillingPhone='.$billing_phone.'&BillingAddress='.$billing_address;
        $print_url = home_url().'/wp-content/plugins/bkt-payment-gateway/view/pdf-receipt.php?'.$get_data;

        if($_POST["ProcReturnCode"]) {

            wp_enqueue_style('bkt-css');

            echo '<a href="javascript:window.print()" id="bkt-print-button" style="margin-right:10px;">Print</a>';
            echo '<a href="'.$print_url.'" target="_blank" id="bkt-print-button">PDF</a>';
        }

        }

        add_action( 'woocommerce_thankyou', 'bkt_print_receipt', 10, 1);
        add_action( 'woocommerce_view_order', 'bkt_print_receipt', 8 );