<div class="cvm-playlist-wall">
	<?php foreach( $videos as $cvm_video ): ?>
	<div class="cvm-playlist-item">
		<div class="cvm-thumbnail">
            <a title="<?php cvm_output_title(false);?>" href="<?php cvm_video_post_permalink();?>">
                <?php cvm_image_preload_output('large');?>
            </a>
		</div>
		<a class="cvm-link" title="<?php cvm_output_title(false);?>" href="<?php cvm_video_post_permalink();?>"<?php cvm_output_video_data();?>>
			<strong><?php cvm_output_title(false);?></strong>
				
		</a>
	</div>
	<?php endforeach;?>
</div>
<?php
wp_enqueue_script( 'jquery-masonry' );
?>