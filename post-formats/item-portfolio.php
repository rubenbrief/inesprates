<?php
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

    <div class="projet-img">
        <?php echo get_the_post_thumbnail($post->ID,'img-400');?>
    </div>
</div>