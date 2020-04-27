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
	title: __( 'Vimeotheque video position', 'cvm_video' ),
	description: __( 'Video embed customization options', 'cvm_video' ),
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
			embed_options: '{"title":1,"byline":1,"portrait":1,"color":"","loop":0,"autoplay":1,"aspect_ratio":"16x9","width":200,"video_position":"below-content","volume":70,"playlist_loop":0,"js_embed":false}',
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
					className="vimeotheque-player"
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
							'&volume=' + opt.volume
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
						title = { __('Embed options', 'cvm_video') }
						initialOpen = {true}
					>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show title', 'cvm_video' ) }
								checked = {opt.title}
								onChange = {
									() => onFormToggleChange( 'title' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show byline', 'cvm_video' ) }
								checked = {opt.byline}
								onChange = {
									() => onFormToggleChange( 'byline' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show portrait', 'cvm_video' ) }
								checked = {opt.portrait}
								onChange = {
									() => onFormToggleChange( 'portrait' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Loop video', 'cvm_video' ) }
								checked = {opt.loop}
								onChange =  {
									() => onFormToggleChange( 'loop' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Autoplay video', 'cvm_video' ) }
								help = { __( "This feature won't work on all browsers.", 'cvm_video' ) }
								checked = {opt.autoplay}
								onChange =  {
									() => onFormToggleChange( 'autoplay' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = { __( 'Volume', 'cvm_video' ) }
								help = { __( 'Will work only for JS embeds', 'cvm_video' ) }
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
						title = { __('Embed size', 'cvm_video') }
						initialOpen = {false}
					>
						<PanelRow>
								<TextControl
									label = { __( 'Width', 'cvm_video' ) }
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
								label = { __( 'Aspect ratio', 'cvm_video' ) }
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
					</PanelBody>

					<PanelBody
						title = { __( 'Color options', 'cvm_video' ) }
						initialOpen={false}
					>
						<PanelRow>
							<label>
								{ __( 'Player color', 'cvm_video' ) + sep }
								<ColorIndicator
									colorValue = { `#${opt.color}` }
								/>
								<span>{ opt.color && `#${opt.color}` }</span>
							</label>
						</PanelRow>

						<PanelRow>
							<ColorPalette
								value = { `#${opt.color}` }
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