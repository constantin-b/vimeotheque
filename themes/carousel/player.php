<div class="cvm-video-playlist carousel"<?php cvm_output_width();?>>
	<div class="cvm-player"<?php cvm_output_player_size();?> <?php cvm_output_player_data();?>><!-- player container --></div>
	<!-- player navigation -->
	<div class="cvm-nav-container-bottom">        
	        <div class="cfvim_carousel">
				<div class="cfvim_carousel_inside">
					<ul class="cfvim_carousel_items cfvim_navigation">
						<?php foreach( $videos as $cvm_video ): ?>
						
						<li class="item">
							<a href="<?php cvm_video_post_permalink();?>"<?php cvm_output_video_data();?>>
								<?php cvm_output_thumbnail();?>
							</a>
						</li>
						
						<?php endforeach;?>
					</ul>
				</div>
				<div class="cvm-vertical-center">
					<a href="#" class="nav-back sidenavs"><i class="dashicons dashicons-arrow-left-alt2"></i></a>
					<a href="#" class="nav-fwd sidenavs"><i class="dashicons dashicons-arrow-right-alt2"></i></a>
				</div>
			</div>		
		</div>
	<!-- /player navigation -->
</div>
<?php
	// font awesome 
	wp_enqueue_style('dashicons' );
?>