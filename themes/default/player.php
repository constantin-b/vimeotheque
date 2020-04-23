<?php
/**
 * @var Vimeotheque\Video_Post[] $videos
 * @var array $embed_options
 */
?>
<div class="cvm-vim-playlist default<?php if( !is_wp_error( parent::get_attr('layout') ) ){ echo ' ' . esc_attr( parent::get_attr('layout') ); } ;?>"<?php cvm_output_width();?>>
    <?php \Vimeotheque\Helper::embed_video( $videos[0], $embed_options, true ); ?>
    <div class="cvm-playlist-wrap">
		<div class="cvm-playlist">
			<?php foreach( $videos as $cvm_video ): ?>
			<div class="cvm-playlist-item">
				<a href="<?php cvm_video_post_permalink();?>"<?php cvm_output_video_data();?>>
					<?php cvm_output_thumbnail();?>
                    <span class="cvm-title"><?php cvm_output_title();?></span>
				</a>
			</div>
			<?php endforeach;?>
		</div>
		<a href="#" class="playlist-visibility collapse"></a>
	</div>
    <div class="clear"></div>
</div>