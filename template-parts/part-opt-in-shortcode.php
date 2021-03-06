<?php
	if (!$optin_id) $optin_id = '-1';

	//fetch the opt in
	$optin_object = get_post($optin_id);

	if($optin_object && $optin_object->post_type == "inti-opt-in" && $optin_object->post_status == "publish") :

		// get its meta
		$action = get_field('url', $optin_id);
		$target = get_field('target', $optin_id);
		$hidden = get_field('hidden', $optin_id);
		$button_text = get_field('button_text', $optin_id);
		$button_name = get_field('button_name', $optin_id);
		$form_name = get_field('form_element_name', $optin_id);

		$first_name_name = get_field('first_name_name', $optin_id);
		$first_name_placeholder = get_field('first_name_placeholder', $optin_id);
		$first_name_required = get_field('first_name_required', $optin_id);

		$email_name = get_field('email_name', $optin_id);
		$email_placeholder = get_field('email_placeholder', $optin_id);
		$email_required = get_field('email_required', $optin_id);

		$add_gdpr_fields = get_field('add_gdpr_fields', $optin_id);
		$give_permissions_description = get_field('give_permissions_description', $optin_id);
		$give_permissions = get_field('give_permissions', $optin_id);
		$extra_gdpr_notices = get_field('extra_gdpr_notices', $optin_id);

?>
		<section class="opt-in shortcode">
			<div class="grid-container">
				<div class="grid-x grid-margin-x">
					<div class="small-12 cell">
						<div class="opt-in-lead-in">
							<?php echo wpautop(do_shortcode($optin_object->post_content)); ?>
						</div>
					</div>
				</div>
				<div class="grid-x grid-margin-x">
					<div class="small-12 cell">

						<form action="<?php echo $action; ?>" method="post" id="shortcode-opt-in-<?php echo rand(0,999); ?>" name="<?php if ($form_name) : echo $form_name; else : echo "form-" . $optin_object->ID; endif; ?>" <?php if ($target) echo 'target="_blank"'; ?>>
							<div class="hide">
								<?php echo stripslashes($hidden); ?>
							</div>

							<fieldset>
								<div class="grid-x">
									<div class="medium-6 mlarge-4 cell">
										<input type="text" name="<?php echo $first_name_name; ?>" id="shortcode-opt-in-<?php echo $first_name_name; ?>" placeholder="<?php echo $first_name_placeholder; ?>" class=""<?php if ($first_name_required) echo ' required'; ?>>
									</div>
									<div class="medium-6 mlarge-4 cell">
										<input type="email" name="<?php echo $email_name; ?>" id="shortcode-opt-in-<?php echo $email_name; ?>" placeholder="<?php echo $email_placeholder; ?>" class=""<?php if ($email_required) echo ' required'; ?>>
									</div>
									<div class="medium-12 mlarge-4 cell">
										<input type="submit" value="<?php echo $button_text ?>" name="<?php echo $button_name; ?>" id="submit-<?php echo $button_name; ?>" class="button expanded">
									</div>
								</div>
							</fieldset>
							
							<?php if ($add_gdpr_fields) : ?>
							<fieldset class="opt-in-gdpr">
								<div class="grid-x">
									<div class="cell">
										<div class="opt-in-gdpr-intro">
											<?php echo $give_permissions_description; ?>
										</div>
									</div>
									<div class="cell">
										<div class="opt-in-gdpr-permissions">
											<?php if( have_rows('give_permissions', $optin_id) ): ?>
												<?php while ( have_rows('give_permissions', $optin_id) ) : the_row(); ?>
													<input type="checkbox" name="<?php the_sub_field('checkbox_element_name'); ?>" value="<?php the_sub_field('checkbox_value'); ?>"> <label for="<?php the_sub_field('checkbox_element_name'); ?>"><?php the_sub_field('checkbox_label'); ?></label>
												<?php endwhile; ?>
											<?php endif; ?>
										</div>
									</div>
									<div class="cell">
										<div class="opt-in-gdpr-outro">
											<?php echo $extra_gdpr_notices; ?>
										</div>
									</div>
								</div>
							</fieldset>
							<?php endif; ?>

						</form>
					</div>
				</div>
			</div>
		</section>
<?php 
	else:
?>
		<div class="callout alert" data-closable>
			<p>No ID specified or no longer exists</p>       
			<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
		        <span aria-hidden="true">&times;</span>
		    </button>
		</div>
<?php
	endif; ?>