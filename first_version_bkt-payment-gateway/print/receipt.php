<?php 

	/**
     * Add "Print Receipt" link to Order Received page and View Order page
     */
    wp_register_style( 'bkt-css', plugin_dir_url( __FILE__ ).'assets/css/style.css' );

    function bkt_print_receipt() {

        wp_enqueue_style('bkt-css');
        
        echo '<a href="javascript:window.print()" id="bkt-print-button">Print Receipt</a>';

        }

        add_action( 'woocommerce_thankyou', 'bkt_print_receipt', 10, 1);
        add_action( 'woocommerce_view_order', 'bkt_print_receipt', 8 );
    