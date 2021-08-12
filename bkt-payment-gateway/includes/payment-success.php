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

    $card = $_POST['CardMask'];
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
	Digits of the card used:
	<strong>$card</strong>
	</li>
			
	</ul>

	<p class='woocommerce-notice' style='text-decoration: underline;font-style: italic;'>
	*Read our Terms and Conditions for cancellations or refunds.
	</p>

	<hr>

    ";

    $rawpost = print_r($_POST, true);

	$logfile = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/bkt-payment-gateway/logs/post.log-'.$order_id;
	$path = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/bkt-payment-gateway/logs/';
	if (!file_exists($path)) {
	    mkdir($path, 0777, true);
	}
	$fh = fopen($logfile,'a') or die("can't open log file");
	fwrite($fh, "DATE: ".date('l jS \of F Y h:i:s A')."\r\n");
	fwrite($fh, "\r\nRAW POST: =\t ".$rawpost."\r\n");
	fwrite($fh, "-------------------------------------------------------\r\n");
	fclose($fh);

	} // end if

}

	/**
     * Add "Print" link to Order Received page and View Order page
     */
    wp_register_style( 'bkt-css', home_url() . '/wp-content/plugins/bkt-payment-gateway/assets/css/style.css' );

    function bkt_print_receipt($order_id) {

    global $woocommerce;
    $order = new WC_Order( $order_id );
    $order_data = $order->get_data(); // The Order data

    $company = get_bloginfo( 'name' );
    $logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );
    $address = get_option( 'woocommerce_store_address' );
    $site_url = get_site_url();
    $email = get_option( 'admin_email' );

    $card = $_POST['CardMask'];
    $authcode = $_POST['AuthCode'];
    $transtype = $_POST['TxnType'];

    $billing_name = $_POST['BillingNameSurname'];
    $billing_email = $_POST['BillingEmail'];
    $billing_phone = $_POST['BillingPhone'];
    $billing_address = $_POST['BillingAddress'];

    $total = $_POST['PurchAmount'];
    $currency = $order_data['currency'];

        $print_url = home_url().'/wp-content/plugins/bkt-payment-gateway/view/pdf-receipt.php';

        if($_POST["ProcReturnCode"]) {

            wp_enqueue_style('bkt-css');

            echo '<a href="javascript:window.print()" id="bkt-print-button" style="margin-right:10px;">Print</a>';
            echo '<form method="post" action="'.$print_url.'" target="_blank">
            <input type="hidden" name="OrderId" value="'.$order_id.'">
			 <input type="hidden" name="CardMask" value="'.$card.'">
			 <input type="hidden" name="AuthCode" value="'.$authcode.'">
			 <input type="hidden" name="TxnType" value="'.$transtype.'">
			 <input type="hidden" name="BillingNameSurname" value="'.$billing_name.'">
			 <input type="hidden" name="BillingEmail" value="'.$billing_email.'">
			 <input type="hidden" name="BillingPhone" value="'.$billing_phone.'">
			 <input type="hidden" name="BillingAddress" value="'.$billing_address.'">
			 <input type="hidden" name="Total" value="'.$total.'">
			 <input type="hidden" name="Currency" value="'.$currency.'">
			 <button type="submit" id="bkt-pdf-button">PDF</button>
			</form>';
        }

        }

        add_action( 'woocommerce_thankyou', 'bkt_print_receipt', 10, 1);
        add_action( 'woocommerce_view_order', 'bkt_print_receipt', 8 );