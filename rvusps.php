<?php
/*
Plugin Name: RVUSPS
*/
define("RV_URL", plugin_dir_url( __FILE__ ));
add_action( 'admin_enqueue_scripts', function(){
	wp_register_script( 'rv_script.js', RV_URL.'rv_script.js', array('jquery') );
	wp_enqueue_script( 'rv_script.js' );
});
// New fields
add_action( 'woocommerce_admin_order_data_after_billing_address', function($order){
	$id = $order->get_id();
	$width = get_post_meta( $id, 'rv_width', true );
	$height = get_post_meta( $id, 'rv_height', true );
	$length = get_post_meta( $id, 'rv_length', true );
	$weight = get_post_meta( $id, 'rv_weight', true );
	$type = get_post_meta( $id, 'rv_type', true );
	woocommerce_wp_text_input(
		array(
			'id'        => 'rv_width',
			'value'     => $width,
			'label'     => __( 'Width', 'woocommerce' ),
			'data_type' => 'decimal',
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'        => 'rv_height',
			'value'     => $height,
			'label'     => __( 'Height', 'woocommerce' ),
			'data_type' => 'decimal',
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'        => 'rv_length',
			'value'     => $length,
			'label'     => __( 'Length', 'woocommerce' ),
			'data_type' => 'decimal',
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'        => 'rv_weight',
			'value'     => $weight,
			'label'     => __( 'Weight', 'woocommerce' ),
			'data_type' => 'decimal',
		)
	);
	woocommerce_wp_select( array(
		'id'      => 'rv_type',
		'label' => __( 'Choose insurance ', 'woocommerce' ),
		'value' => $type,
		'options' => array(
			'' => 'Select...',
			'normal' => 'Normal',
			'extended' => 'Extended',
		)
	) );

	$trackId = get_post_meta( $id, 'rv_trackId', true );
	if(isset($trackId)){
		$label = get_post_meta( $id, 'rv_label', true );
		echo "
		<div class='form-field'>
			<p>Track Id: $trackId</p>
			<a href='$label' target='_blank'>Check Label</a>
			<button type='button' id='rv_end' data-id='$id' class='button save_order button-primary'>Send to client</button>
		</div>
		";
	}else{
		echo "
		<div class='form-field'>
			<button type='button' id='rv_send' data-id='$id' data-post='".RV_URL."action.php' class='button save_order button-primary' value='Send'>".__( 'Send', 'woocommerce' )."</button>
		</div>
		";
	}

	
}, 25 );
 
