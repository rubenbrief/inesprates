<?php get_header();
global $string_domain;?>
	<div class="content uk-container uk-container-center" >
		<ul id="categories-filter" class="categories-filter cl-effect-3">
			<li class="relative" data-uk-filter="">
				<a href=""><?php _e('todos', 'ip'); ?></a></li>
			<?php 
				$categorias = get_terms('category');

				foreach ($categorias as $filter) {
					?>
					<li class="relative" data-uk-filter="<?php echo $filter->slug; ?>"><a href="#"><?php echo strtoloweR($filter->name); ?></a></li>
					<?php } ?>
		</ul>

		<div id="content-grid" class="uk-grid uk-grid-width-1-2 uk-grid-width-small-1-3 uk-grid-width-medium-1-4" data-uk-grid="{gutter: 40, controls: '#categories-filter'}">
			<?php
			if(have_posts()): 
				while( have_posts() ) {
				the_post();
				get_template_part('post-formats/item','portfolio');
				}
			endif;
			?>
		</div>
	</div>


<?php get_footer(); ?>
