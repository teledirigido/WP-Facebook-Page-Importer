<?php

/*
	
	// Facebook list

	$list = array(
	
		// Facebook post

		array(
			'id' 			=> integer,
			'created_time' 	=> datetime,
			'permalink' 	=> string,
			'full_picture'	=> string,
			'direct_url'	=> string
		)
	)


*/


class FPI_Import {

	private $list;
	private $status;

	function __construct($list){
		$this->list = $list;

		// Create posts
		$this->compose();

	}

	private function compose(){

		foreach($this->list as $item ):

			$new_post 		= $this->get_new_post_args($item);
			$new_post_id	= wp_insert_post($new_post);
			update_post_meta( $new_post_id, 'facebook_id', $item['id'] );
			update_post_meta( $new_post_id, 'facebook_created_time', $item['created_time'] );
			update_post_meta( $new_post_id, 'facebook_permalink', $item['permalink'] );
			update_post_meta( $new_post_id, 'facebook_full_picture', $item['full_picture'] );
			update_post_meta( $new_post_id, 'facebook_direct_url', $item['direct_url'] );

		endforeach;

		$this->status = 200;

	}

	private function get_new_post_args($new_post){

		$current_user = wp_get_current_user();

		// If Message or Story
		if( isset($new_post['story']) ):
			$post_title = $new_post['story'];
		else:
			$post_title = $new_post['message'];
		endif;

		// Default args
		$args = array(
			'post_title'     => $post_title,
			'post_date'		 => $new_post['created_time'],
			'post_type'      => 'facebookposts',
			'post_status'    => 'publish',
			'post_author'    => $current_user->ID,
		);


		// Extra args
		$post_exists = $this->submitted_post_exists($new_post);
		if( $post_exists['status'] ):
			$args['ID'] = $post_exists['ID']; // Copy ID
		endif;

		// Return Default args
		return  $args;


	}


	// ON progress
	private function submitted_post_exists($post_to_search){

		$found = array(
			'status' => false
		);

		$args = array(
            'post_type'     => 'facebookposts',
            'meta_key'      => 'facebook_id',
            'meta_value'    => $post_to_search['id'],
            'post_status'   => 'any'
		);

		// Check if post exists
        $the_query = new WP_Query($args);
        if( $the_query->have_posts() ):
        	while( $the_query->have_posts() ):
        		$the_query->the_post();
        		global $post;

	        	$found = array(
	        		'status' => true,
	        		'ID'	 => $post->ID
	        	);

        	endwhile;
        endif;
        wp_reset_query();

        return $found;

	}

	public function get_status(){
		return $this->status;
	}

}


/*

	DEPRECATED

	----------

function do_fpi_ajax(){

	$list = isset( $_POST['list'] ) ? (array)$_POST['list']['data'] : array();

	foreach( $list as &$item ):
		$item['message'] = esc_attr( $item['message'] );
	endforeach;

	$import = new FPI_Import($list);

	print_r($result);

	die;

}

add_action('wp_ajax_nopriv_do_ajax', 'do_fpi_ajax');
add_action('wp_ajax_do_ajax', 'do_fpi_ajax');
*/