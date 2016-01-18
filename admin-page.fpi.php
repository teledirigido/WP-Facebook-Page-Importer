<?php 
	
	$this->options = get_option( 'fpi_option' );

?>

<div class="ef-wrap wrap">

	<?php screen_icon(); ?>

	<h2>Facebook Page Importer</h2>

	<?php 
		if( isset($_GET['fpi_action']) && $_GET['fpi_action'] == 'import' ):
			// ef_importer_result('Sync completed.');
			echo 'Something happened';
		endif; 
	?>

	<form class="options" method="post" action="options.php">
		<?php
			
			// This prints out all hidden setting fields
			settings_fields( 'fpi_option_group' );   
			do_settings_sections( 'fpi-admin-page' );

		?>
		
		<hr>
		<div class="ef_actions">
			
			<span class="el">
				<?php submit_button( 'Update settings', 'primary', 'update-form', ''); ?>
			</span>
			

	</form>

	<?php /*a href="<?php echo wp_nonce_url( add_query_arg( array( 'ab_action' => 'sync' ) ), 'sync' ); ?>" title="Import Events" class="button-primary">Import from Eventfinda</a*/?>
	
</div>

<div class="ef-wrap wrap">
	
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
		<?php submit_button( 'Start Import', 'large', 'fpi-import', '' ); ?>

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
