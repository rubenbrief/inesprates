<?php get_header();
global $string_domain;?>
	<div class="uk-container uk-container-center" >

		<ul id="categories-filter" class="categories-filter cl-effect-12">
			<li class="relative" data-uk-filter="">
				<a href=""><?php _e('todos', 'ip'); ?></a></li>
			<?php 
				$categorias = get_terms('category');

				foreach ($categorias as $filter) {
					?>
					<li class="relative" data-uk-filter="<?php echo $filter->slug; ?>"><a href="#"><?php echo strtoloweR($filter->name); ?></a></li>
					<?php } ?>
		</ul>

		<div id="content-grid" class="uk-grid uk-grid-width-1-2 uk-grid-width-small-1-4 uk-grid-width-medium-1-5" data-uk-grid="{gutter: 40, controls: '#categories-filter'}">

			<?php if (have_posts()) : while (have_posts()) : the_post(); 

				$terms  = wp_get_post_terms( $post->ID, 'category');
					$categorias_trabalho = array();
					foreach ($terms as $categoria) {
						$categorias_trabalho[] = $categoria->slug;
					} 
				?>

				<div class="grid-item block relative" data-uk-filter="<?php echo implode(',' , $categorias_trabalho); ?>">
					<a href="#project-<?php echo $post->ID; ?>" class="open-project" data-anchor="<?php echo $post->guid ?>"></a>

					<!-- This is the modal -->
					<div id="project-<?php echo $post->ID; ?>" class="uk-modal">
						<div class="uk-modal-dialog uk-modal-dialog-large uk-overflow-container">
							<a href="#" class="close-modal"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/close.svg"></a>
							<div class="content-modal">

							</div>
						</div>
					</div>

					<a href="#" class="eye" title="<?php _e('Já viste este?','ip');?>">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/eye.png">
						</a>
					<?php echo get_the_post_thumbnail($post->ID,'img-400');?>

					<div class="visto hide"></div>
				</div>

			
			
			<?php endwhile; ?>

					<?php bones_page_navi(); ?>

			

			<?php endif; ?>
			

		</div>
	</div>


<?php get_footer(); ?>
