<?php 

@session_start();

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
            'fpi_app_secret', // ID
            'App Page Secret', // Title 
            array( $this, 'fpi_app_secret_callback' ), // Callback
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

        add_settings_field(
            'fpi_access_token', // ID
            'Access Token', // Title 
            array( $this, 'fpi_access_token' ), // Callback
            'fpi-admin-page', 
            'fpi-section' // Section           
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

        if( !empty( $input['fpi_app_secret'] ) )
            $input['fpi_app_secret'] = sanitize_text_field( $input['fpi_app_secret'] );

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

    public function fpi_app_secret_callback(){
        
        printf(
            '<input type="password" id="fpi_app_secret" name="fpi_option[fpi_app_secret]" value="%s" />',
            // esc_attr( '12345' )
            isset($this->options['fpi_app_secret']) ? esc_attr( $this->options['fpi_app_secret'] ) : ''
        );   
    }

    public function fpi_page_id_callback(){
    	printf(
            '<input type="text" id="fpi_page_id" name="fpi_option[fpi_page_id]" value="%s" />',
            esc_attr( $this->options['fpi_page_id'] )
        );
        echo '<p class="description"><i><a href="//findmyfbid.com/">Click here</a> to find your Page ID.</i></p>';
    }

    public function fpi_access_token(){

        printf(
            '<input type="password" id="fpi_access_token" name="fpi_option[fpi_access_token]" value="%s" /><br>',
            // esc_attr( '12345' )
            isset($this->options['fpi_access_token']) ? esc_attr( $this->options['fpi_access_token'] ) : ''
        );


    }

}

add_action('init', function(){

	// FPI Class page settings
    if( !is_admin() ):
    	return false;
    endif;
        
    $my_settings_page = new FPI();

    // Remove Token
    if( FPI_SDK::is_new_token() ):
        FPI_SDK::remove_token();
    endif;

    // Create session, Get new Token
    if( FPI_SDK::is_logged() ):
       FPI_SDK::create_session();
    endif;

});