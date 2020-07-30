<?php
/**
 * @var Vimeotheque\Video_Post[] $videos
 * @var array $embed_options
 */

use Vimeotheque\Themes\Helper;

?>
<div class="cvm-vim-playlist default<?php if( !is_wp_error( parent::get_attr('layout') ) ){ echo ' ' . esc_attr( parent::get_attr('layout') ); } ;?>"<?php Helper::get_width();?>>
    <?php \Vimeotheque\Helper::embed_video( $videos[0], $embed_options, true ); ?>
    <div class="cvm-playlist-wrap">
		<div class="cvm-playlist">
			<?php foreach( $videos as $cvm_video ): ?>
			<div class="cvm-playlist-item">
				<a href="<?php Helper::get_post_permalink();?>"<?php Helper::get_video_data_attributes();?>>
					<?php Helper::get_thumbnail();?>
                    <span class="cvm-title"><?php Helper::get_title();?></span>
				</a>
			</div>
			<?php endforeach;?>
		</div>
		<a href="#" class="playlist-visibility collapse"></a>
	</div>
    <div class="clear"></div>
</div>