<?php 

$this->options = get_option( 'fpi_option' );



?>


<div class="ef-wrap wrap">

	<?php screen_icon(); ?>

	<h2>Facebook Page Importer</h2>

	<?php 

	// Login
	if( !FPI_SDK::is_logged() ):
		$fb = FPI_SDK::init();	
	    $helper = $fb->getRedirectLoginHelper();
	    $permissions = ['email', 'user_likes']; // optional
	    $login_url = $helper->getLoginUrl( admin_url('/tools.php?page=fpi-settings&logged=1'), $permissions);
	    echo '<a class="fpi-loggin dashicons-before dashicons-facebook" href="' . $login_url . '">Log in with Facebook</a>';
	endif;  

	// Initialize import
	if( isset($_GET['fpi_action']) && $_GET['fpi_action'] == 'import' ):
		
		$args = array(
			'display_notices' => true
		);
		
		FPI_SDK::init_import( $args );

	endif; 

	
	?>

	<ol id="get_started">
        <li>Add your <a href="//developers.facebook.com/apps/" target="_blank">new Facebook App</a>.</li>
        <li>Authorize your website with your Facebook App (Login Popup).</li>
        <li>Add your App ID, App Secret and Page ID to start. <br> You will some of this info under your <a target="_blank" href="//developers.facebook.com/apps/">Facebook Developers account</a>. <br>
           <img src="<?php echo plugin_dir_url( __FILE__ ) ?>/img/myapp.png" alt="" width="400px"></li>
        <li>Update your settings and start importing!</li>
        <li>Check your imported <a href="<?php echo admin_url('edit.php?post_type=facebookposts'); ?>" target="_blank">Facebook Posts</a></li>

		<?php if( FPI_SDK::is_logged() ): ?>		
        	<li>Just in case you need. You can <a href="<?php echo admin_url('tools.php?page=fpi-settings&remove_token=1' ); ?>">remove your current Access Token here</a>. <br> After doing this you might need to authorize Facebook again</li>
        <?php endif; ?>

	</ol>



	<form class="options" method="post" action="options.php">
		<?php
			
			// This prints out all hidden setting fields
			settings_fields( 'fpi_option_group' );   
			do_settings_sections( 'fpi-admin-page' );

		?>

		
		<div class="ef_actions">
			
			<span class="el">
				<?php submit_button( 'Update settings', 'primary', 'update-form', ''); ?>
			</span>
		</div>	

	</form>
	
</div>

<hr>

<div class="ef-wrap wrap">
	<form id="ef-get-events" class="el ef-submit" method="POST" action="<?php echo wp_nonce_url( add_query_arg( array( 'fpi_action' => 'import' ) ), 'import' ); ?>">
		<h3>Start your import here:</h3>
		<p>Once your details have been save you can start your import from here:</p>
		<?php submit_button( 'Start Import', 'large', 'fpi-import', '' ); ?>
		<p class="description"><i>Verify your settings before importing your data.</i></p>
	</form>
</div>

<?php 


/*

DEPRECATED


<div class="ef-wrap wrap" style="display:none">
	

	<form id="ef-get-events" class="el ef-submit" method="POST" action="<?php echo wp_nonce_url( add_query_arg( array( 'fpi_action' => 'import' ) ), 'import' ); ?>">
		
		<?php do_settings_sections( 'fpi-admin-import' ); ?>
		
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th scope="col" id="title" class="manage-column column-title column-primary">Facebook posts</th>
				</tr>
			</thead>

			<tbody id="fpi-list">
				
				<tr class="no-items">
					<td class="colspanchange">No Facebook Posts found</td>
				</tr>

			</tbody>

		</table>
		

		<hr>
		<?php // submit_button( 'Start Import', 'large', 'fpi-import', '' ); ?>

		<p class="description"><i>Verify your settings before importing your data.</i></p>
	</form>

</div>


<script id="facebook-post-message" type="x-tmpl-mustache">
	<tr class="no-items">
		<td class="colspanchange">{{ message }}</td>
	</tr>
</script>

<script id="facebook-post-el" type="x-tmpl-mustache">
	{{#data}}
	<tr>
		<td>
			<p><strong>{{message}}</strong></p>
			{{{story}}}
		</td>
	</tr>
	{{/data}}
</script>

*/ ?>
