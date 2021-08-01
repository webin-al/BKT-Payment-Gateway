<?php

add_action( 'woocommerce_thankyou', 'bkt_details_after_success_payment', 10, 1 );

    function bkt_details_after_success_payment( $order_id ) {

    // Let's return some required data for the receipt if payment successful
    if(($_POST["ProcReturnCode"] == "00")) {	

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