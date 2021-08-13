<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-load.php';

require_once $path . '/wp-content/plugins/bkt-payment-gateway/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

	$company = get_bloginfo( 'name' );
	$nipt = $_POST['Nipt'];
    $logo = esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );
    $address = get_option( 'woocommerce_store_address' );
    $site_url = get_site_url();
    $email = get_option( 'admin_email' );
	
	$date = $_POST['TransactionDate'];
    $card = $_POST['CardMask'];
    $authcode = $_POST['AuthCode'];
    $transtype = $_POST['TxnType'];

    $order_id = $_POST['OrderId'];

    $billing_name = $_POST['BillingNameSurname'];
    $billing_email = $_POST['BillingEmail'];
    $billing_phone = $_POST['BillingPhone'];
    $billing_address = $_POST['BillingAddress'];

    $total = $_POST['Total'];
    $currency = $_POST['Currency'];

    if ($_POST['OrderId']) {

$mpdf->SetTitle("Order-$order_id");
$mpdf->WriteHTML("

	<img src='$logo' width='200' />
	<p>Payment receipt for your order</p> <br />

	<h2>Company details</h2>

    <ul>

	<li>
	Company:
	<strong>$company</strong><br>
	NIPT/NUIS: <strong>$nipt</strong>
	</li>

	<li>
	Address:
	<strong>$address</strong>
	</li>

	<li>
	Website URL:
	<strong>$site_url</strong>
	</li>

	<li>
	Support:
	<strong>$email</strong>
	</li>
			
	</ul>

	<h2>Payment details</h2>

    <ul>
	
	<li>
	Transaction Date:
	<strong>$date</strong>
	</li>
	
	<br>
	
	<li>
	Billing Details for Order #$order_id
	<br><strong>$billing_name</strong>
	<br><strong>$billing_email</strong>
	<br><strong>$billing_phone</strong>
	<br><strong>$billing_address</strong>
	</li>

	<li>
	Transaction Type:
	<strong>$transtype</strong>
	</li>

	<li>
	Authorization Code:
	<strong>$authcode</strong>
	</li>

	<li>
	Digits of the card used:
	<strong>$card</strong>
	</li>

	<br>

	<li>
	<strong>Total:</strong>
	<strong>$total ( $currency )</strong>
	</li>
			
	</ul>

	<p>
	*Read our Terms and Conditions for cancellations or refunds.
	</p>

	<hr>

	");

}

else {

$mpdf->SetTitle(" Are you ");
$mpdf->WriteHTML(" Lost? ");

}

$mpdf->Output();
