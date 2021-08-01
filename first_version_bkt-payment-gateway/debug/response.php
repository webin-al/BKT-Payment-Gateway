<?php

add_action( 'woocommerce_pay_order_after_submit', 'bkt_error_response', 10, 1 );

    function bkt_error_response( $order_id ) {

    echo '<br><br>
    <strong>API RESPONSE</strong>
    <br><br>
    <p>Order ID: '. $_POST['OrderId'] .'</p>';
    echo '<p>TxnResult: '. $_POST['TxnResult'] .'</p>';
    echo '<p>Message: '. $_POST['ErrorMessage'] .'</p>';
    echo '<p>Msg Err: '. $_POST['ErrMsg'] .'</p>';

    echo '<p>Hash: '. $_POST['Hash'] .'</p>';
    echo '<p>Response Hash: '. $_POST['ResponseHash'] .'</p>';

    echo '<p>Auth Code: '. $_POST['AuthCode'] .'</p>';
    echo '<p>Proc Return Code: '. $_POST['ProcReturnCode'] .'</p>';
    echo '<p>Proc Response Code: '. $_POST['ResponseCode'] .'</p>';
    echo '<p>Response Msg: '. $_POST['ResponseMessage'] .'</p>';

    }