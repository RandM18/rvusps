<?php 
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$order_id = wc_clean($_POST['order_id'] );
$response = [];

$order = wc_get_order( $order_id );
if (!empty($order)) {
    $order->update_status( 'completed' );
}
// $response['result'] = wp_mail();
echo json_encode($response);
?>