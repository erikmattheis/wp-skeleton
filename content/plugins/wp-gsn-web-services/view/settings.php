<div class="gsn-content gsn-settings">

	<h3>Web Services Configuration</h3>

	<?php if ( $this->are_key_constants_set() ) : ?>

		<p>
			<?php _e( 'You&#8217;ve already defined your GSN Web Services credential in your wp-config.php. If you&#8217;d prefer to manage them here and store them in the database (not recommended), simply remove the lines from your wp-config.', 'gsn-web-services' ); ?>
		</p>

	<?php else : ?>

		<p>
			<?php printf( __( 'If you don&#8217;t have an Gsn Web Services credential yet, you need to contact a GSN Account representative to <a href="%s">sign up</a>.', 'gsn-web-services' ), 'http://www.groceryshopping.net/contact-us/' ); ?>
		</p>

		<pre>define( 'GSN_API_BASE_URL', 'https://clientapi.gsn2.com/api/v1' );
define( 'GSN_SITE_ID', '****************' );
define( 'GSN_CLIENT_SECRET', '****************************************' );</pre>

		<p class="reveal-form">
			<?php _e( 'If you&#8217;d rather not to edit your wp-config.php and are ok storing the keys in the database, <a href="">click here to reveal a form.</a>', 'gsn-web-services' ); ?>
		</p>

		<form method="post" <?php echo ( ! $this->get_api_base_url() && ! $this->get_site_id() && ! $this->get_client_secret() ) ? 'style="display: none;"' : ''; // xss ok ?>>

			<?php if ( isset( $_POST['gsn_site_id'] ) ) { // input var okay ?>
				<div class="gsn-updated updated">
					<p><strong>Settings saved.</strong></p>
				</div>
			<?php } ?>

			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field( 'gsn-save-settings' ) ?>

			<table class="form-table">
        <tr valign="top">
					<th width="33%" scope="row"><?php _e( 'API Base URL:', 'gsn-web-services' ); ?></th>
					<td>
						<input type="text" name="api_base_url" value="<?php echo $this->get_api_base_url() // xss ok; ?>" size="30" autocomplete="off" />
					</td>
				</tr>
				<tr valign="top">
					<th width="33%" scope="row"><?php _e( 'Site Id:', 'gsn-web-services' ); ?></th>
					<td>
						<input type="text" name="site_id" value="<?php echo $this->get_site_id() // xss ok; ?>" size="10" autocomplete="off" />
					</td>
				</tr>
				<tr valign="top">
					<th width="33%" scope="row"><?php _e( 'Client Secret:', 'gsn-web-services' ); ?></th>
					<td>
						<input type="text" name="client_secret" value="<?php echo $this->get_client_secret() ? '-- not shown --' : ''; // xss ok ?>" size="40" autocomplete="off" />
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2" scope="row">
						<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'gsn-web-services' ); ?></button>
						<?php if ( $this->get_site_id() || $this->get_client_secret() ) : ?>
							&nbsp;
							<button class="button remove-keys"><?php _e( 'Remove Keys', 'gsn-web-services' ); ?></button>
						<?php endif; ?>
					</th>
				</tr>
			</table>

		</form>

	<?php endif; ?>

</div>