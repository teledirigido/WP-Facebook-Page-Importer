<?php

	/**
	* PARAMETERS
	* ----------
	*
	*
	* Required (Array)
	*
	* $options = array(
	* 	@param 'plural'		=> str 'Plural name',			# required
	* 	@param 'singular'	=> str 'singular name',			# required
	* 	@param 'args'		=> str 'Rest of argumentes',	# required
	* );
	*
	*
	* USAGE
	* -------
	* $post_type_news = new post_type_register( $options , $args );
	*
	**/

class fpi_post_type_register {

	private $plural;
	private $singular;
	private $slug;
	private $args;

	public function __construct( $args ) {

		$this->args 		= ( isset($args['args']) ? $args['args'] : '' );
		$this->plural 		= $args['plural'];
		$this->singular 	= $args['singular'];

		add_action( 'init', array( $this, 'register' ) );
	}
	
	public function register() {

		$labels = array(
			'name'               => ucfirst($this->plural),
			'singular_name'      => ucfirst($this->singular),
			'menu_name'          => ucfirst($this->plural),
			'add_new'            => 'Add ' . ucfirst($this->singular),
			'add_new_item'       => 'Add New ' . ucfirst($this->singular),
			'edit_item'          => 'Edit ' . ucfirst($this->singular),
			'new_item'           => 'New ' . ucfirst($this->singular),
			'all_items'          => 'All ' . ucfirst($this->plural),
			'view_item'          => 'View ' . ucfirst($this->singular),
			'search_items'       => 'Search ' . ucfirst($this->plural),
			'not_found'          => 'No ' . $this->plural . ' found',
			'not_found_in_trash' => 'No ' . $this->plural . ' found in the Trash'
		);

		$default_args = array(
			'labels'        	=> $labels,
			'description'   	=> 'Holds our ' . $this->plural . ' and specific data',
			'public'        	=> true,
			'menu_icon'			=> 'dashicons-list-view',
			'supports'	    	=> array( 'title' , 'editor' , 'thumbnail' ),
			'map_meta_cap'		=> true,
			'has_archive'   	=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
		);

		$args = wp_parse_args( $this->args , $default_args );

		register_post_type( $this->plural, $args ); 

	}

}

// Register Facebook posts type
$args = array(
	'plural' 	=> 'Facebook Posts',
	'singular' 	=> 'Facebook post',
	'args'		=> array(
		'slug' 		=> 'facebook-post',
		'menu_icon' => 'dashicons-facebook',
		'supports'	=> array('title','custom-fields'),
	)
);

$_new = new fpi_post_type_register($args);