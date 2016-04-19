<?php

class FPI_SDK {

	public static function init(){
		
		$options = get_option( 'fpi_option' );

		return $fb = new Facebook\Facebook([
			'app_id' => $options['fpi_app_id'],
			'app_secret' => $options['fpi_app_secret'],
			'default_graph_version' => 'v2.5',
		]);
	}

	public static function create_session( $fb = null ){

		$fb = self::init();
		$helper = $fb->getRedirectLoginHelper();

	    try {
	      $accessToken = $helper->getAccessToken();
	    
	    } catch(Facebook\Exceptions\FacebookResponseException $e) {
	      // When Graph returns an error
	      echo 'Graph returned an error: ' . $e->getMessage();
	      exit;
	    
	    } catch(Facebook\Exceptions\FacebookSDKException $e) {
	      // When validation fails or other local issues
	      echo 'Facebook SDK returned an error: ' . $e->getMessage();
	      exit;
	    }

	    if (isset($accessToken)) {
			
			// Get option and update
	    	$options = get_option('fpi_option');
			$options['fpi_access_token'] = (string) $accessToken;
			// Update Option
			update_option('fpi_option' , $options );

			// $_SESSION['facebook_access_token'] = (string) $accessToken;
			// dump($accessToken);
			wp_redirect( admin_url('/tools.php?page=fpi-settings') );

	    } elseif ($helper->getError()) {
		  // The user denied the request
		  exit;
		}

	}

	public static function remove_token(){
		$option = get_option('fpi_option');
		unset($option['fpi_access_token']);
		update_option( 'fpi_option', $option );
	}

	public static function init_import( $args = false){

		$options 	= get_option( 'fpi_option' );
		$fb 		= self::init();

		try {

		  	// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get('/'.$options['fpi_page_id'].'/feed?fields=link,full_picture,story,message,created_time,actions', $options['fpi_access_token'] );
			// {"fields":"link,full_picture,story,message,created_time,actions"},

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;

		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$graphEdge = $response->getGraphEdge();

		$list = array();
		foreach ($graphEdge as $graphNode) {

			$item = $graphNode->asArray();

			$elem = array(
				'id' 			=> $item['id'],
				'story'			=> $item['story'],
				'message'		=> $item['message'],
				'created_time' 	=> $item['created_time'],
				'created_time' 	=> date_format( $item['created_time'] , 'dd/mm/YYYY' ),
				'permalink'		=> $item['actions'][0]['link'],
				'full_picture'	=> $item['full_picture'],
				'direct_url'	=> $item['actions'][0]['link']
			);

			array_push( $list, $elem ); 
		}

		$new_import = new FPI_Import($list);

		if( $new_import->get_status() == 200 && (isset($args['display_notices']) && $args['display_notices'] == 1 )  ):
			echo '<div class="notice notice-success is-dismissible"><p>Success! <a href="'.admin_url('edit.php?post_type=facebookposts').'">See your Facebook posts here</a></p></div>';
		endif;

	}


	public static function is_new_token(){
		return ( isset($_GET['remove_token']) && $_GET['remove_token'] == 1 );
	}


	public static function is_logged(){
		$option = get_option('fpi_option');

		return ( isset($_GET['logged']) && ($_GET['logged'] == 1) || isset($option['fpi_access_token']) );
	}

	public function has_credentials(){

		$options = get_option( 'fpi_option' );	

		if( !isset($options['fpi_app_secret']) || empty($options['fpi_app_secret']) )
			return false;

		if( !isset($options['fpi_app_id']) || empty($options['fpi_app_id']) )
			return false;

		return true;
	}
	
}