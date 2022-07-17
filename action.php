<?php 
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$response=[];
$order_id = wc_clean($_POST['order_id'] );
$rv_width = wc_clean($_POST['rv_width'] );
$rv_height = wc_clean($_POST['rv_height'] );
$rv_length = wc_clean($_POST['rv_length'] );
$rv_weight = wc_clean($_POST['rv_weight'] );
$rv_type = wc_clean($_POST['rv_type'] );

// API
$request_doc_template = <<<EOT
<?xml version="1.0"?>
<USPSReturnsLabelRequest  USERID="001WPPAN5493">
	<Revision>1</Revision>
	<Address ID="0">
		<Address1>2335 S State</Address1>
		<Address2>Suite 300</Address2>
		<City>Provo</City>
		<State>UT</State>
		<Zip5>84604</Zip5>
		<Zip4/>
	</Address>
</USPSReturnsLabelRequest>
EOT;
$doc_string = preg_replace('/[\t\n]/', '', $request_doc_template);
$doc_string = urlencode($doc_string);
$url = "https://secure.shippingapis.com/ShippingAPI.dll?API=USPSReturnsLabel&XML=" . $doc_string;
echo $url . "\n\n";
$response = file_get_contents($url);
$xml=simplexml_load_string($response) or die("Error: Cannot create object");
print_r($xml);
exit;

update_post_meta( $order_id, 'rv_width', $rv_width);
update_post_meta( $order_id, 'rv_height',  $rv_height);
update_post_meta( $order_id, 'rv_length', $rv_length);
update_post_meta( $order_id, 'rv_weight',  $rv_weight);
update_post_meta( $order_id, 'rv_type', $rv_type);

// SUCCESS
$response['id']=$order_id;
$response['label']="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMSIgaGVpZ2h0PSIxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InJlZCIvPjwvc3ZnPg==";
$label = explode(',', $response['label']);
$labelEncode = base64_decode($label[1]);

$fullPath = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/tracks/'.date('m')."/";
$absolutePath = '/wp-content/uploads/tracks/'.date('m')."/" . $response['id'].'.pdf';
if (!file_exists($fullPath)) {
    mkdir($fullPath, 0777, true);
}
$file = file_put_contents($fullPath . $response['id'].'.pdf', $labelEncode);
$response['trackid']='trackid_aGVpZ2h0PSIxIiB4bWx';
$response['result']=true;
update_post_meta( $order_id, 'rv_trackId', $response['trackid']);
update_post_meta( $order_id, 'rv_label', $path);
echo json_encode($response);
exit;


// FAIL
$response['result']=false;
$response['text']="Error:";
echo json_encode($response);
exit;
?>