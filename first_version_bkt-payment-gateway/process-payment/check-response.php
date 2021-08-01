<?php

              $MbrId = $this->get_option( 'mbrid');                                                                        
              $MerchantPass= $this->get_option( 'merchantpass' );                                                      
              $TxnType= "Auth";                                                                          
              $InstallmentCount= "0";                                                                    
              $OkUrl = $order->get_checkout_order_received_url();
              $FailUrl = $order->get_checkout_payment_url( $on_checkout = false );
              $OrderId = $order_id;
              $PurchAmount = $order->total; 

              $Rnd = microtime(); 
              $hashstr = $MbrId.$OrderId.$PurchAmount.$OkUrl.$FailUrl.$TxnType.$InstallmentCount.$Rnd.$MerchantPass;
              $Hash = base64_encode(pack('H*',sha1($hashstr)));

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
                "FailUrl" => $order->get_checkout_payment_url( $on_checkout = false ),
                "OrderId" => $order_id,
                "OrgOrderId" => $order_id,
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

                $url = $this->get_option( 'posturl' );

                $response = wp_remote_post( $url , array(
                'method'    => 'POST',
                'body'    => http_build_query( $args ),
                'timeout'   => 90,
                'sslverify' => false, )
                );