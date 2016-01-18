<?php 

class FPI {
	
	public function __construct() {
		
		// Register settings
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	
	}
    
    // Init settings
    public function page_init(){

    	// Register setting
    	register_setting(
            'fpi_option_group', // Option group
            'fpi_option', // Option name
            array( $this, 'fpi_option_callback' ) // Sanitize
        );

    	// Register section
        add_settings_section(
            'fpi-section', // ID
            'Get Started', // Title
            array( $this, 'print_section_info' ), // Callback
            'fpi-admin-page' // Page
        );

        add_settings_section(
            'fpi-import', // ID
            'Import Info', // Title
            array( $this, 'print_section_import' ), // Callback
            'fpi-admin-import' // Page
        );



        // Register field
        add_settings_field(
            'fpi_app_id', // ID
            'App ID', // Title 
            array( $this, 'fpi_app_id_callback' ), // Callback
            'fpi-admin-page', 
            'fpi-section' // Section           
        );

        add_settings_field(
            'fpi_page_id', 
            'Page ID', 
            array( $this, 'fpi_page_id_callback' ), 
            'fpi-admin-page', 
            'fpi-section'
        );

    }

    // Register page
    public function add_plugin_page(){
        add_submenu_page(
            'tools.php',
            'Facebook Page Importer', 
            'Facebook Page Importer', 
            'manage_options', 
            'fpi-settings', 
            array( $this, 'create_admin_page' ),
            '',
            81
        );
    }

    public function create_admin_page(){ 
        include 'admin-page.fpi.php';
    }

    public function print_section_info(){
    	?>
    	<ol id="get_started">
            <li>Add your <a href="//developers.facebook.com/apps/" target="_blank">new Facebook App</a>.</li>
            <li>Authorize your website with your Facebook App (Login Popup).</li>
	        <li>Add your App ID, App Secret and Page ID to start. <br> You will some of this info under your <a target="_blank" href="//developers.facebook.com/apps/">Facebook Developers account</a>.
	        <img src="<?php echo plugin_dir_url( __FILE__ ) ?>/img/myapp.png" alt="" width="400px"></li>
            <li>Update your settings and start importing!</li>
            <li>Check your imported <a href="<?php echo admin_url('edit.php?post_type=facebookposts'); ?>" target="_blank">Facebook Post</a></li>
    	</ol>

        <?php

    }

    public function print_section_import(){

    }

    /*
     *
     * Sanitize 
     *
     */
    public function fpi_option_callback( $input ){
        
         if( !empty( $input['fpi_app_id'] ) )
            $input['fpi_app_id'] = sanitize_text_field( $input['fpi_app_id'] );  

        if( !empty( $input['fpi_page_id'] ) )
            $input['fpi_page_id'] = sanitize_text_field( $input['fpi_page_id'] );

        return $input;
    }


    /* 
     *
     * Template Callbacks
     *
     */
    public function fpi_app_id_callback(){
    	printf(
            '<input type="text" id="fpi_app_id" name="fpi_option[fpi_app_id]" value="%s" />',
            esc_attr( $this->options['fpi_app_id'] )
        );
    }

    public function fpi_page_id_callback(){
    	printf(
            '<input type="text" id="fpi_page_id" name="fpi_option[fpi_page_id]" value="%s" />',
            esc_attr( $this->options['fpi_page_id'] )
        );
        echo '<p class="description"><i><a href="//findmyfbid.com/">Click here</a> to find your Page ID.</i></p>';
    }

}

add_action('init', function(){

	// FPI Class page settings
    if( !is_admin() ):
    	return false;
    endif;
        
    $my_settings_page = new FPI();

});