<?php get_header();?>
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
	
	<div id="content-grid" class="uk-grid uk-grid-width-1-2 uk-grid-width-small-1-3 uk-grid-width-medium-1-4" data-uk-grid="{gutter: 40, controls: '#categories-filter'}" data-uk-scrollspy="{cls:'uk-animation-fade', target:'.grid-item'}">
		<?php
		
		$args = array(
			'posts_per_page'   => -1,
			'suppress_filters' => false 
		);
		$posts_array = get_posts( $args ); ?>
			
		<!-- the loop -->
		<?php foreach($posts_array as $article):
		$post = $article;
		
		get_template_part('post-formats/item','portfolio');
    	
		endforeach; 
		wp_reset_postdata();
		?>
	</div>

	<div class="contacts">
		<ul>
			<li class="facebook"><a href="<?php echo get_field('facebook');?>" target="_blank"><i class="uk-icon-facebook"></i><?php echo get_field('nome_facebook');?></a></li>
			<li class="instagram"><a href="<?php echo get_field('instagram');?>" target="_blank"><i class="uk-icon-instagram"></i><?php echo get_field('nome_instagram');?></a></li>
			<li class="email"><a href="mailto:<?php echo get_field('email');?>" target="_blank"><?php echo get_field('email');?></a></li>
			<li class="tel"><a href="tel:<?php echo get_field('numero');?>" target="_blank"><?php echo get_field('numero');?></a></li>
		</ul>
	</div>
</div>


<?php get_footer(); ?>