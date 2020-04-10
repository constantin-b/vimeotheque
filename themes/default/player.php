<?php
/**
 * @var \Vimeotheque\Video_Post $videos
 */
?>
<div class="cvm-vim-playlist default<?php if( !is_wp_error( parent::get_attr('layout') ) ){ echo ' ' . esc_attr( parent::get_attr('layout') ); } ;?>"<?php cvm_output_width();?>>
	<div class="cvm-player"<?php cvm_output_player_size();?> <?php cvm_output_player_data();?>><!-- player container --></div>
	<div class="cvm-playlist-wrap">
		<div class="cvm-playlist">
			<?php foreach( $videos as $cvm_video ): ?>
			<div class="cvm-playlist-item">
				<a href="<?php cvm_video_post_permalink();?>"<?php cvm_output_video_data();?>>
					<?php cvm_output_thumbnail();?>
					<?php cvm_output_title();?>
				</a>
			</div>
			<?php endforeach;?>
		</div>
		<a href="#" class="playlist-visibility collapse"></a>
	</div>	
</div>