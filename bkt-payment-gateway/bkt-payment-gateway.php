<?php

/*
 * Plugin Name: BKT Payment Gateway
 * Plugin URI: https://webin.al/
 * Description: BKT payment gateway for Albanian stores.
 * Author: web:in development house
 * Author URI: https://webin.al/
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

if ( ! function_exists( 'is_plugin_active' ) ) {

    require_once ABSPATH . 'wp-admin/includes/plugin.php';

}

/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'bkt_add_gateway_class' );

function bkt_add_gateway_class( $gateways ) {

    $gateways[] = 'WC_BKT_Gateway'; 

    return $gateways;

}

// Add custom action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bkt_add_gateway_class_links' );

function bkt_add_gateway_class_links( $links ) {

  $plugin_links = array(
    '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=bkt_payment_gateway' ) . '">' . __( 'Settings', 'bkt_add_gateway_class' ) . '</a>',
    '<a href="' . home_url( 'wp-content/plugins/bkt-payment-gateway/logs/' ) . '" target="_blank">' . __( 'View Logs', 'bkt_add_gateway_class' ) . '</a>'

  );

  return array_merge( $plugin_links, $links );

}

// display warning if Woocommerce is deactivated or not installed

function bkt_gateway_class_install_woocommerce_admin_notice() {

    ?>
        <div class="error">

            <p><?php esc_html_e( 'BKT Payment Gateway is enabled but not effective. It requires WooCommerce plugin activated.', 'woocommerce' ); ?></p>

        </div>

    <?php
}

add_action( 'plugins_loaded', 'bkt_gateway_class' );

function bkt_gateway_class() {

    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {

    add_action( 'admin_notices', 'bkt_gateway_class_install_woocommerce_admin_notice' );

    return;

    }

   // logic

    class WC_BKT_Gateway extends WC_Payment_Gateway {

        public $domain;

        /**
         * Class constructor
         */
    public function __construct() {

    $this->domain = 'bkt_payment_gateway';
    $this->id = 'bkt_payment_gateway'; // payment gateway plugin ID
    $this->icon = $this->get_option( 'logo', plugin_dir_url( __FILE__ ).'assets/images/bkt.png' ); // URL of the icon that will be displayed on checkout page near your gateway name
    $this->has_fields = true;
    $this->method_title = 'BKT Payment Gateway';
    $this->method_description = 'BKT payment gateway for Albanian stores.'; // will be displayed in options page
    // gateways can support subscriptions, refunds, saved payment methods
    $this->supports = array(
        'products'
    );

    // Method with all the options fields
    $this->init_form_fields();

    // Load the settings.
    $this->init_settings();
    $this->title = $this->get_option( 'title' );
    $this->description = $this->get_option( 'description' );
    $this->enabled = $this->get_option( 'enabled' );
    $this->testmode = $this->get_option( 'testmode' );
    $this->apiuser = $this->get_option( 'apiuser' );
    $this->apipassword = $this->get_option( 'apipassword' );
    $this->posturl = $this->get_option( 'posturl' );
    $this->mbrid = $this->get_option( 'mbrid' );
    $this->merchantid = $this->get_option( 'merchantid' );
    $this->merchantpass = $this->get_option( 'merchantpass' );
    $this->lang = $this->get_option( 'lang' );
    $this->currency = $this->get_option( 'currency' );
    // messages in frontend
    $this->pay_now_description = $this->get_option( 'pay_now_description' );
    $this->pay_now_button = $this->get_option( 'pay_now_button' );
    $this->payment_failed = $this->get_option( 'payment_failed' );
    $this->frontend_description = $this->get_option( 'frontend_description' );

    // This action hook saves the settings
    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

    // Go to BKT API
    add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'bkt_pay_page' ) );

    // include patch to skip login in order pay page ( pay the order without account )
    require_once ABSPATH . '/wp-content/plugins/bkt-payment-gateway/includes/skip-login.php';
    // display receipt details when payment is successful
    require_once ABSPATH . '/wp-content/plugins/bkt-payment-gateway/includes/payment-success.php';

    } // constructor end

        /**
         * Plugin options
         */
        public function init_form_fields(){

        $bkt_icon = plugin_dir_url( __FILE__ ).'assets/images/bkt.png';
        $woo_currency = get_woocommerce_currency();

        $this->form_fields = array(
        'enabled' => array(
            'title'       => 'Enable/Disable',
            'label'       => 'Enable BKT Secure Payments',
            'type'        => 'checkbox',
            'description' => '',
            'default'     => 'no'
        ),
        'logo' => array(
            'title'       => 'Icon URL',
            'type'        => 'text',
            'description' =>  '<img src="'.$bkt_icon.'" height="50" /><br>
                                Default: ' .plugin_dir_url( __FILE__ ).'assets/images/bkt.png',
            'default'     =>  $bkt_icon,
        ),
        'title' => array(
            'title'       => 'Title',
            'type'        => 'text',
            'description' => 'BKT Secure Payments',
            'default'     => 'BKT Secure Payments',
            'desc_tip'    => true,
        ),
        'description' => array(
            'title'       => 'Description',
            'type'        => 'textarea',
            'description' => 'Pay with your debit/credit card via BKT Bank.',
            'default'     => 'Pay with your debit/credit card via BKT Bank.',
        ),
        'testmode' => array(
            'title'       => 'Test API',
            'type'        => 'checkbox',
            'description' => 'Working with test API?<br/>
                              Also enables debuging to view API responses in frontend ( when payment fails )',
            'default'     => 'yes',
        ),
        'apiuser' => array(
            'title'       => 'API User/User Code',
            'type'        => 'text',
            'description' => 'API User supplied from the bank',
            'default'     => '',
            'desc_tip'    => true,
        ),
        'apipassword' => array(
            'title'       => 'API Password/User Password',
            'type'        => 'password',
            'description' => 'API Password supplied from the bank',
            'default'     => '',
            'desc_tip'    => true,
        ),
        'posturl' => array(
            'title'       => 'POST URL',
            'type'        => 'text',
            'description' => 'POST URL supplied from the bank',
            'default'     => 'https://payfortestbkt.cordisnetwork.com/Mpi/3DHost.aspx',
            'desc_tip'    => true,
        ),
        'mbrid' => array(
            'title'       => 'MBR ID',
            'type'        => 'number',
            'description' => 'Mbr Id supplied from the bank',
            'default'     => '9',
            'desc_tip'    => true,
        ),
        'merchantid' => array(
            'title'       => 'Merchant ID',
            'type'        => 'number',
            'description' => 'Merchant ID supplied from the bank',
            'default'     => '',
            'desc_tip'    => true,
        ),
        'merchantpass' => array(
            'title'       => 'User/Merchant Password',
            'type'        => 'password',
            'description' => 'User/Merchant Password supplied from the bank',
            'default'     => '',
            'desc_tip'    => true,
        ),

        'lang' => array(
            'title'       => 'Language',
            'type'        => 'select',
            'options' => array(
            'EN' => 'English',
            'TR' => 'Turkish'
            ),
            'description' => 'Language in the bank page',
            'default'     => 'EN',
            'desc_tip'    => true,
        ),
        'currency' => array(
            'title'       => 'Payment Currency',
            'type'        => 'select',
            'options' => array(
            '978' => 'EUR',
            '008' => 'ALL',
            '840' => 'USD',
            '949' => 'TL'
            ),
            'description' => 'Make sure to match currency in Woocommerce <br/>
                              Current Woocommerce currency is: ' .$woo_currency,
            'default'     => '978',
        ),
        'template' => array(
            'title'       => 'Template Type',
            'type'        => 'select',
            'options' => array(
            '1' => 'Type 1',
            '2' => 'Type 2'
            ),
            'description' => 'Type of Payment Template Design',
            'default'     => '1',
            'desc_tip'    => true,
        ),
        'frontend_description' => array(
            'title'       => 'Description in checkout',
            'type'        => 'text',
            'description' => 'Description to display in checkout<br>
                                Default: We will take you to the BKT Bank page to enter your card details.',
            'default'     => 'We will take you to the BKT Bank page to enter your card details.',
        ),
        'pay_now_description' => array(
            'title'       => 'Pay Now description text',
            'type'        => 'text',
            'description' => 'Text to display after user checks out<br>
                                Default: Your order is now pending. Click Pay Now button below to complete the payment.',
            'default'     => 'Your order is now pending. Click Pay Now button below to complete the payment.',
        ),
        'pay_now_button' => array(
            'title'       => 'Pay Now button text',
            'type'        => 'text',
            'description' => 'Text of Pay Now button<br>
                                Default: Pay Now',
            'default'     => 'Pay Now',
        ),
        'payment_failed' => array(
            'title'       => 'Payment fails text',
            'type'        => 'text',
            'description' => 'Text to display if payment fails<br>
                                Default: Payment unsuccessful, please try again or contact us!',
            'default'     => 'Payment unsuccessful, please try again or contact us!',
        )
    );
    
        }

        /**
         * Lets's display some info
         */
        public function payment_fields() {
 
            // ok, let's display some description before the payment form
            if ( $this->description ) {
                // you can instructions for test mode, I mean test card numbers etc.
                if ( $this->testmode ) {
                    $this->description .= '<br /><br /> <p style="color:red;">TEST MODE ENABLED!</p>';
                    $this->description  = trim( $this->description );
                }

                $this->description .= '<br /><br />'.$this->get_option('frontend_description', 'We will take you to the BKT Bank page to enter your card details.');
                    $this->description  = trim( $this->description );
                    echo wpautop( wp_kses_post( $this->description ) );
            }

        }

        /*
         * We're processing the payments here
         */
        public function process_payment( $order_id ) {

        global $woocommerce;
        $order = new WC_Order( $order_id );

        // check the response from API
        include_once plugin_dir_url( __FILE__ ).'includes/check-response.php';

            if(!is_wp_error($response)) {

                $order->update_status('pending', __( 'Awaiting BKT payment!', 'woocommerce' ));

                // some notes to customer (replace true with false to make it private)
                $order->add_order_note( 'Order pending. Waiting for BKT payment.', true );

                return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url( true ) // sends us to the POST form
                );

                }

                else {

                    wc_add_notice(  'Something went wrong, Please try again.', 'error' );

                    $order->update_status('uncompleted', __( 'Order cannot be completed.', 'woocommerce' ));

                    // some notes to customer (replace true with false to make it private)
                    $order->add_order_note( 'Order uncompleted.', true );

                    return array(
                        'result' => 'failure',
                        'redirect' =>  $this->get_return_url( $order ) // scrolls up with error
                    );
            
            }
                
            }

            /*
         * We're taking the customer to the hidden POST form
         */
        function bkt_pay_page( $order ) {

            echo '<p>' . __( $this->get_option('pay_now_description', 'Your order is now pending. Click <strong>Pay Now</strong> button below to complete the payment.'), 'woocommerce' ) . '</p>';

            echo $this->generate_form( $order );

            }

            /*
         * POST the request to BKT
         */
          function generate_form( $order_id ) {

              $order = new WC_Order( $order_id );

              $MbrId = $this->get_option( 'mbrid');                                                                        
              $MerchantPass= $this->get_option( 'merchantpass' );                                                      
              $TxnType= "Auth";                                                                          
              $InstallmentCount= "0";                                                                    
              $OkUrl = $order->get_checkout_order_received_url();
              $FailUrl = wc_get_checkout_url().'order-pay/'.$order_id.'/?key='.$order->get_order_key();
              $OrderId = rand(10000,300000).'_'.$order_id;
              $PurchAmount = $order->total;

              $Rnd = microtime(); 
              $hashstr = $MbrId.$OrderId.$PurchAmount.$OkUrl.$FailUrl.$TxnType.$InstallmentCount.$Rnd.$MerchantPass;
              $Hash = base64_encode(pack('H*',sha1($hashstr)));

                // required parameters to POST
                $args = array(
     
                "MbrId" => $this->get_option( 'mbrid' ),
                "MerchantID" => $this->get_option( 'merchantid' ),
                "MerchantPass" => $this->get_option( 'merchantpass' ),
                "UserCode" => $this->get_option( 'apiuser' ),
                "UserPass" => $this->get_option( 'apipassword' ),
                "SecureType" => "3DHost",
                "TxnType" => "Auth",
                "InstallmentCount" => "0",
                "Currency" => $this->get_option( 'currency' ),
                "OkUrl" => $order->get_checkout_order_received_url(),
                "FailUrl" => wc_get_checkout_url().'order-pay/'.$order_id.'/?key='.$order->get_order_key(),
                "OrderId" => $OrderId,
                "OrgOrderId" => '',
                "PurchAmount" => $order->total,
                "Lang" => $this->get_option( 'lang', 'EN' ),

                "TemplateType" => $this->get_option( 'template', '1' ),

                "ShippingNameSurname" => $order->billing_first_name.' '.$order->billing_last_name,
                "ShippingEmail" => $order->billing_email,
                "ShippingPhone" => $order->billing_phone,
                "ShippingCompanyName" => $order->billing_company,
                "ShippingAddress" => $order->billing_address_1.' '.$order->billing_address_2,
                "ShippingTown" => $order->billing_state,
                "ShippingCity" => $order->billing_city,
                "ShippingZipCode" => $order->billing_postcode,
                "ShippingCountry" => $order->billing_country,

                "BillingNameSurname" => $order->billing_first_name.' '.$order->billing_last_name,
                "BillingEmail" => $order->billing_email,
                "BillingPhone" => $order->billing_phone,
                "BillingCompanyName" => $order->billing_company,
                "BillingAddress" => $order->billing_address_1.' '.$order->billing_address_2,
                "BillingTown" => $order->billing_state,
                "BillingCity" => $order->billing_city,
                "BillingZipCode" => $order->billing_postcode,
                "BillingCountry" => $order->billing_country,

                "Rnd" => $Rnd,
                "hashstr" => $hashstr,
                "Hash" => $Hash
                );

                // automatically click Pay Now button after 3 seconds
                // wc_enqueue_js( '
                //    jQuery("#post-button").delay(3000).click();
                //' );

                ob_start();
                include ( plugin_dir_path( __FILE__ ) . 'view/form.php');
                $gotobkt = ob_get_contents();
                ob_end_clean();

                return $gotobkt;

        }

    }

}