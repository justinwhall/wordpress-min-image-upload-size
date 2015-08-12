<?php

class WP_MIS {

	protected $loader;

	protected $plugin_slug;

	protected $version;


	public function __construct() {

		$this->plugin_slug = 'wp-min-image-size';
		$this->version = '0.1.0';

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mis-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mis-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-mis-loader.php';
		if(file_exists(plugin_dir_path( __FILE__ ) . 'mis-addon_cf.php')){
			require_once plugin_dir_path( __FILE__ ) . 'mis-addon_cf.php';
		}

		$this->loader = new WP_MIS_Loader();
	}

	private function define_admin_hooks() {

		$admin = new WP_MIS_Admin( $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'wp_handle_upload_prefilter', $admin, 'check_img_size' );
		$this->loader->add_action( 'wp_ajax_update_user_mod_field', $admin, 'update_user_mod_field' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_version() {
		return $this->version;
	}

}
