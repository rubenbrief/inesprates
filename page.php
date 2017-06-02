<?php get_header(); ?>

	<div id="content">
		<a href="#" class="ver-projetos" data-home="<?php echo home_url();?>"><?php _e('Quero ver projetos', 'ip');?></a>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h1><?php echo $post->post_title; ?></h1>
			<div class="main-content">
				<?php echo $post->post_content; ?>
			</div>
			<?php endwhile; ?>
			<?php endif; ?>
	</div>

<?php get_footer(); ?>