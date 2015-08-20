<?php
class WP_MIS_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Min Image Sizes',
            'manage_options',
            'wp-mis-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'mis_img_params' );
        ?>
        <div id="mis-options" class="wrap">
            <h2>Minimum Image Size Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'mis_img_params', // Option name
            array( $this, 'sanatize_img_params' ) // Sanitize
        );

        add_settings_section(
            'post_type_section', // ID
            'Post Types', // Title
            array( $this, 'print_post_type_header' ), // Callback
            'my-setting-admin' // Page
        );

        // Post types
        $post_types = get_post_types();
        foreach ($post_types as $type) {
            add_settings_field(
                'mis_post_type_' . $type,
                $type,
                array( $this, 'render_post_type' ),
                'my-setting-admin',
                'post_type_section',
                array('key' =>  $type, 'type' => 'post_type' )
            );
        }

        add_settings_section(
            'custom_field_section', // ID
            'Custom Fields', // Title
            array( $this, 'print_custom_field_header' ), // Callback
            'my-setting-admin' // Page
        );

        // Custom fields
        global $wpdb;
        $query = "SELECT DISTINCT meta_key FROM wp_postmeta WHERE meta_key not LIKE '\_%' AND meta_key not LIKE 'field_%'";
        $keys = $wpdb->get_results($query);
        foreach ($keys as $key) {
            add_settings_field(
                'mis_post_type_' . $key->meta_key,
                $key->meta_key,
                array( $this, 'render_post_type' ),
                'my-setting-admin',
                'custom_field_section',
                array('key' =>  $key->meta_key, 'type' => 'custom_field' )
            );
        }



    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanatize_img_params( $inputs )
    {
        $new_input = array();
        foreach ($inputs as $type => $val) {

            foreach ($val as $key => $img_px) {

                foreach ($img_px as $px) {
                    if(strlen(trim($px)) && absint($px ) != 0)
                    $new_input[$type][$key][] = absint( $px );
                }

            }
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_post_type_header()
    {
        print '<small>Post Type image settings. Enter minimum dimensions in pixels:</small>';
    }

    public function print_custom_field_header()
    {
        print '<small>Custom Field image settings. Enter minimum dimensions in pixels:</small>';
    }


    /**
     * Get the settings option array and print one of its values
     */
    public function render_post_type($args)
    {
    		// Check if the options exist in the options array...
            if(array_key_exists($args['type'], $this->options) && array_key_exists($args['key'], $this->options[$args['type']])){
                $width = $this->options[$args['type']][$args['key']][0];
                $height = $this->options[$args['type']][$args['key']][1];
            }else{
                $width = '';
                $height = '';
            }
           echo '<label>Width</label> <input type="text" placeholder="No Minimum Size" name="mis_img_params[' . $args['type'] . '][' . $args['key'] . '][]" value="' . $width .'" />';
           echo ' <label>Height</label> <input type="text" placeholder="No Minimum Size" name="mis_img_params[' . $args['type'] . '][' . $args['key'] . '][]" value="' . $height .'" /> <span class="clear-vals">X</span>';

    }
}

if( is_admin() )
    $WP_MIS_Settings = new WP_MIS_Settings();