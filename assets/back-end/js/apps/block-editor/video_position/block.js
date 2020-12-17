const 	{ registerBlockType } = wp.blocks,
	{ __ } = wp.i18n,
	{
		Panel,
		PanelBody,
		PanelRow,
		ColorIndicator,
		ColorPalette,
		Dropdown,
		TextControl,
		SelectControl,
		ToggleControl
	} = wp.components,
	{ InspectorControls } = wp.blockEditor,
	{ useCallback } = wp.element,
	{ withState } = wp.compose;

registerBlockType( 'vimeotheque/video-position', {
	title: __( 'Vimeotheque video position', 'codeflavors-vimeo-video-post-lite' ),
	description: __( 'Video embed customization options', 'codeflavors-vimeo-video-post-lite' ),
	icon: 'video-alt3',
	category: 'layout',

	attributes: {
		embed_options: {
			type: 'string',
			source: 'meta',
			meta: '__cvm_playback_settings',
			default: false
		},
		video_id: {
			type: 'string',
			source: 'meta',
			meta: '__cvm_video_id',
			default: false
		},
		extra: {
			type: 'object',
			default: {}
		}
	},

	supports: {
		align: false,
		anchor: false,
		html: false,
		multiple: false,
		reusable:false,
		customClassName: false
	},

	example: {
		attributes: {
			video_id: '1084537',
			embed_options: '{"title":1,"byline":1,"portrait":1,"color":"","loop":0,"autoplay":1,"aspect_ratio":"16x9","width":200,"video_position":"below-content","volume":70,"playlist_loop":0}',
		}
	},

	edit: props => {
		const {
			attributes: {
				embed_options,
				video_id
			},
			setAttributes,
			className
		} = props;

		let opt = JSON.parse( embed_options );
		const sep = ' : ';

		const onFormToggleChange = ( varName ) => {
			opt[ varName ] = !opt[ varName ]
			setAttributes({
				embed_options: JSON.stringify( opt )
			})
		}

		return [
			<div key="vimeotheque-video-position-block">
				<div
					className ={ "vimeotheque-player " + opt.video_align }
					data-width = { opt.width }
					data-aspect_ratio = { opt.aspect_ratio }
					style = {
						{
							width: `${opt.width}px`,
							maxWidth: '100%'
						}
					}
					onLoad = {
						event  => vimeotheque.resize( event.currentTarget )
					}
				>
					<iframe
						src={
							"https://player.vimeo.com/video/" +
							video_id +
							"?title=" + opt.title +
							'&byline=' + opt.byline +
							'&portrait=' + opt.portrait +
							'&loop=' + opt.loop +
							'&color=' + opt.color +
							'&autoplay=' + opt.autoplay +
							'&volume=' + opt.volume +
							'&dnt=' + opt.dnt
						}
						width = "100%"
						height = "100%"
						frameBorder = "0"
						webkitallowfullscreen = "true"
						mozallowfullscreen = "true"
						allowFullScreen = { true }
					/>
				</div>
				{
					!props.isSelected &&
					(
						<div
							style={
								{
									position:'absolute',
									top:0,
									left:0,
									width:'100%',
									height:'100%'
								}
							}
						/>
					)
				}
			</div>

			,

			/*
			 * InspectorControls
			 */
			<InspectorControls key="vimeotheque-video-position-controls">

					<PanelBody
						title = { __('Embed options', 'codeflavors-vimeo-video-post-lite') }
						initialOpen = {true}
					>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show title', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {opt.title}
								onChange = {
									() => onFormToggleChange( 'title' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show byline', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {opt.byline}
								onChange = {
									() => onFormToggleChange( 'byline' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show portrait', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {opt.portrait}
								onChange = {
									() => onFormToggleChange( 'portrait' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Loop video', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {opt.loop}
								onChange =  {
									() => onFormToggleChange( 'loop' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Autoplay video', 'codeflavors-vimeo-video-post-lite' ) }
								help = { __( "This feature won't work on all browsers.", 'codeflavors-vimeo-video-post-lite' ) }
								checked = {opt.autoplay}
								onChange =  {
									() => onFormToggleChange( 'autoplay' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = { __( 'Volume', 'codeflavors-vimeo-video-post-lite' ) }
								help = { __( 'Will work only for JS embeds', 'codeflavors-vimeo-video-post-lite' ) }
								type = "number"
								step = "1"
								value = { opt.volume }
								min = "0"
								max = "100"
								onChange = {
									value => {
										opt.volume = ( value >= 0 && value <= 100 ) ? value : opt.volume;
										setAttributes({
											embed_options: JSON.stringify( opt )
										})
									}
								}
							/>
						</PanelRow>
					</PanelBody>

					<PanelBody
						title = { __('Embed size', 'codeflavors-vimeo-video-post-lite') }
						initialOpen = {false}
					>
						<PanelRow>
								<TextControl
									label = { __( 'Width', 'codeflavors-vimeo-video-post-lite' ) }
									type = "number"
									step = "5"
									value = { opt.width }
									min = "200"
									onChange = {
										value => {
											opt.width = ( !value || value < 200 ) ? 200 : value;
											setAttributes({
												embed_options: JSON.stringify( opt )
											})
											vimeotheque.resizeAll()
										}
									}
								/>
						</PanelRow>

						<PanelRow>
							<SelectControl
								label = { __( 'Aspect ratio', 'codeflavors-vimeo-video-post-lite' ) }
								value = { opt.aspect_ratio }
								options = {[
									{ label: '4x3', value: '4x3' },
									{ label: '16x9', value: '16x9' },
									{ label: '2.35x1', value: '2.35x1' },
								]}
								onChange = {
									value => {
										opt.aspect_ratio = value;
										setAttributes({
											embed_options: JSON.stringify( opt )
										})
										setTimeout(  vimeotheque.resizeAll, 100 );
									}
								}
							/>
						</PanelRow>

						<PanelRow>
							<SelectControl
								label = { __( 'Align', 'codeflavors-vimeo-video-post-lite' ) }
								value = { opt.video_align }
								options = {[
									{ label: 'left', value: 'align-left' },
									{ label: 'center', value: 'align-center' },
									{ label: 'right', value: 'align-right' },
								]}
								onChange = {
									value => {
										opt.video_align = value;
										setAttributes({
											embed_options: JSON.stringify( opt )
										})
									}
								}
							/>
						</PanelRow>
					</PanelBody>

					<PanelBody
						title = { __( 'Color options', 'codeflavors-vimeo-video-post-lite' ) }
						initialOpen={false}
					>
						<PanelRow>
							<label>
								{ __( 'Player color', 'codeflavors-vimeo-video-post-lite' ) + sep }
								<ColorIndicator
									colorValue = { `#${opt.color.replace( '#', '' )}` }
								/>
								<span>{ opt.color && `#${opt.color.replace( '#', '' )}` }</span>
							</label>
						</PanelRow>

						<PanelRow>
							<ColorPalette
								value = { `#${opt.color.replace( '#', '' )}` }
								onChange = {
									color => {
										opt.color = color.replace( '#', '' );
										setAttributes({
											embed_options: JSON.stringify( opt )
										})
									}
								}
							/>
						</PanelRow>
					</PanelBody>

			</InspectorControls>
		];
	},

	save: props => null

} );