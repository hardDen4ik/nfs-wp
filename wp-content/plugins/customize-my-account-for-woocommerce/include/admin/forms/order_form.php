<table>
	<tr valign="top" >
		
		
		<td width="100%" class="forminp">
			<?php echo esc_html__('Feature to Manage Order Columns and Actions Available In Pro Version Only','customize-my-account-for-woocommerce'); ?> 

			&emsp;
			<a type="button" href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal"  class="btn btn-warning wcmamtx_pro_link" >
				<span class="dashicons dashicons-lock"></span>
				<?php echo esc_html__( 'Upgrade to pro' ,'customize-my-account-for-woocommerce'); ?>
			</a>

			<div class="modal fade" id="wcmamtx_upgrade_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">

						<div class="modal-body">

							<a type="button" target="_blank" href="<?php echo pro_url; ?>" name="submit" id="wcmamtx_frontend_link" class="btn btn-primary wcmamtx_frontend_link" >
								<span class="dashicons dashicons-lock"></span>
								<?php echo esc_html__( 'Visit Pro Version Page' ,'customize-my-account-for-woocommerce'); ?>
							</a>

							<a type="button" target="_blank" href="https://www.sysbasics.com/go/customize-demo/" name="submit" id="wcmamtx_frontend_link" class="btn btn-success wcmamtx_frontend_link" >
								<span class="dashicons dashicons-lock"></span>
								<?php echo esc_html__( 'Visit Pro Version Demo' ,'customize-my-account-for-woocommerce'); ?>
							</a>

							

						</div>
						<div class="modal-footer">
							
						</div>
					</div>
				</div>
			</div>
		</table>
	</td>
</tr>
</table>