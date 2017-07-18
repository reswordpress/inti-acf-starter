<?php
/**
 * Display Services
 *
 * This block requires an accompanying post type to be created,
 * which by default would be called "inti-service"
 *
 * "inti-service" also has a custom taxonomy called "inti-service-category"
 *
 * Helper functions have been created and added to child-helpers.php that return a formatted
 * string of the name of the person who gave the testimonail and the business they
 * represent. 
 *
 * @package Inti
 * @subpackage blocks
 * @since 1.0.3
 * @version 1.0,4
 */
		
// get the options
$show = get_inti_option('fpb_services_show', 'inti_customizer_options', 1);

$title = get_inti_option('fpb_services_title', 'inti_customizer_options');
$description = get_inti_option('fpb_services_description', 'inti_customizer_options');

$service_category = get_inti_option('fpb_services_category', 'inti_customizer_options', 0);
$number_posts = get_inti_option('fpb_services_post_number', 'inti_customizer_options', 3);
$post_columns = get_inti_option('fpb_services_post_columns', 'inti_customizer_options', 3);
$order = get_inti_option('fpb_services_order', 'inti_customizer_options', 'ASC');
$default_action_text = get_inti_option('read_more_text', 'inti_general_options', 'Read more &raquo;');

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$args = "";
if ($service_category == 0) {
	$args = array( 
	'post_type'           => 'inti-service',
	'posts_per_page'      => $number_posts,
	'order'               => $order,
	'orderby'             => 'menu_order',
	'ignore_sticky_posts' => 1,
	'paged'               => $paged );
} else {
	$args = array( 
	'post_type'           => 'inti-service',
	'tax_query'           => array(
								array(
									'taxonomy' => 'inti-service-category', 
									'field' => 'id', 
									'terms' => $service_category)
							 ),
	'posts_per_page'      => $number_posts,
	'order'               => $order,
	'orderby'             => 'menu_order',
	'ignore_sticky_posts' => 1,
	'paged'               => $paged );
}

global $services_query;

if ($show) :
	$services_query = new WP_Query( $args );
?>
	<section class="block services">
		<div class="grid-container">
			<div class="grid-x grid-padding-x">
				<div class="cell">
					<?php if ($title || $description) : ?>
					<header class="block-header">
						<?php if ($title) : ?><h3><?php echo $title; ?></h3><?php endif; ?>
						<?php if ($description) : ?><p><?php echo $description; ?></p><?php endif; ?>
					</header>
					<?php endif; ?>
				</div>
			</div>
			  
			<?php if ( $services_query->have_posts() ) : ?>
			
			
				<?php // if more than one column use block-grid
				if ( $post_columns != 1 ) echo '<div class="grid-x grid-padding-x small-up-1 medium-up-1 mlarge-up-' . $post_columns . '">'; ?>
				
					<?php while ( $services_query->have_posts() ) : $services_query->the_post(); global $more; $more = 0; ?>
						
						<?php if ( $post_columns != 1 ) echo '<div class="cell">'; ?>
							<?php 
								// get the service meta values
								$action_text = get_post_meta(get_the_ID(), '_inti_service_action_text', true);
								$action_url = get_post_meta(get_the_ID(), '_inti_service_url', true);
								$action_new = get_post_meta(get_the_ID(), '_inti_service_new', true);

								// set a final text for button (or link, or alt text or whatever)
								$final_action_text = '';
								if ($action_text) {
									$final_action_text = $action_text;
								} else {
									$final_action_text = $default_action_text;
								}

								// set a final url
								$final_url = '';
								if ($action_url) {
									$final_url = $action_url;
								} else {
									$final_url = get_the_permalink();
								}

							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
								<div class="entry-body">
									<?php  if ( has_post_thumbnail() ) : ?>
									<div class="grid-x grid-padding-x">
										<div class="cell">
											<div class="entry-thumbnail">
												<a href="<?php echo $final_url; ?>" 
												    rel="bookmark" 
												    title="<?php the_title_attribute(); ?>"
												    <?php if ($action_new) echo 'target="_blank"' ?>>
													<?php the_post_thumbnail( 'service-thumbnail', array( 'class' => 'service-thumbnail', 'alt' => $final_action_text ) ); ?>
												</a>
											</div>
										</div>
									</div>
									<?php endif; ?>
									<div class="grid-x grid-padding-x">
										<div class="cell"> 

											
											<header class="entry-header">
												<h3 class="entry-title">
													<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __('%s', 'inti'), the_title_attribute('echo=0') ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
												</h3>
											</header><!-- .entry-header -->
											

											<div class="entry-summary">
												<?php the_excerpt(); ?>
												<a href="<?php echo $final_url; ?>" 
												    rel="bookmark" 
												    title="<?php the_title_attribute(); ?>"
												    <?php if ($action_new) echo 'target="_blank"' ?>
												    class="button read-more">
													<?php echo $final_action_text; ?>
												</a>
											</div><!-- .entry-content -->               

											 <footer class="entry-footer">
												
											</footer><!-- .entry-footer -->

										</div>
									</div>

								</div><!-- .entry-body -->
							</article><!-- #post -->
							
						<?php if ( $post_columns != 1 ) echo '</div>'; ?> 
						
					<?php endwhile; // end of the loop 
						wp_reset_query(); ?>
					
				<?php if ( $post_columns != 1 ) echo '</div>'; // close the block-grid ?>
				
			<?php else: ?>
				<div class="callout warning" data-closable>
					<p><?php _e('There are currently no published services in this category', 'inti-child'); ?></p>
					<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif; // end have_posts() check ?>

		</div>
	</section>
<?php endif; ?>