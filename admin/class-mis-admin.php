<?php

class WP_MIS_Admin {

	private $version;

	public function __construct( $version ) {
		$this->version = $version;
	}

	public function enqueue_styles() {

		wp_enqueue_style(
			'wp-mis',
			plugin_dir_url( __FILE__ ) . 'css/wp-mis.css',
			array(),
			$this->version,
			FALSE
		);

		wp_enqueue_script(
			'wp-mis',
			plugin_dir_url( __FILE__ ) . 'js/wp-mis.js',
			array(),
			$this->version,
			FALSE
		);

		wp_localize_script( 'wp-mis', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

	}

	public function check_img_size($file){

			$mis_params = get_option( 'mis_img_params' );

			// Check to see what kind of input the user is editing
			$meta_key = get_user_meta(get_current_user_id(),  'last_user_field_mod', true );

			// check if there is a custom field image restriction
			if (is_array($mis_params['custom_field']) && array_key_exists($meta_key['field_key'], $mis_params['custom_field'])) {
				$min_width = $mis_params['custom_field'][$meta_key['field_key']][0];
				$min_height = $mis_params['custom_field'][$meta_key['field_key']][1];
			}
			// No field name? Restrict image size by post_type
			elseif($meta_key['field_key'] === false){

				$post_type = get_post_type( $_REQUEST['post_id'] );
				if (is_array($mis_params['post_type']) && array_key_exists($post_type, $mis_params['post_type'] )) {
					$min_width = $mis_params['post_type'][$post_type][0];
					$min_height = $mis_params['post_type'][$post_type][1];
				}

			}

			if (isset($min_width) && isset($min_height)) {
				$img=getimagesize($file['tmp_name']);

				$minimum = array('width' => $min_width, 'height' => $min_height);

				$width= $img[0];

				$height =$img[1];


				if ($width < $minimum['width'] )
				    return array("error"=>"Image dimensions are too small. Minimum width is {$minimum['width']}px. Uploaded image width is $width px");

				elseif ($height <  $minimum['height'])
				    return array("error"=>"Image dimensions are too small. Minimum height is {$minimum['height']}px. Uploaded image height is $height px");

				else
				    return $file;
			}

			else{
				return $file;
			}



	}


	public	function update_user_mod_field() {
		global $post;
		$user_ID = get_current_user_id();

		if(isset($_POST['fieldKey'])){
			$fieldKey = $_POST['fieldKey'];
		}else{
			$fieldKey = false;
		}

		// Save a record of what field we are editing. We'll check this in the CHECK_IMG_SIZE function later and restrict img uploads accordingly.
		update_user_meta( $user_ID, 'last_user_field_mod', array( 'field_key' => $fieldKey ) );

		die;
	}


}