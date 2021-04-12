const
	{
		blockEditor: {
			InspectorControls
		},
		blocks: {
			registerBlockType
		},
		components: {
			Panel,
			PanelBody,
			PanelRow,
			ColorIndicator,
			ColorPalette,
			Dropdown,
			TextControl,
			SelectControl,
			ToggleControl
		},
		compose: {
			withState
		},
		element: {
			useCallback,
			useEffect,
			useState
		},
		hooks: {
			applyFilters,
			doAction
		},
		i18n: {
			__,
			sprintf
		}
	} = wp

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
		/**
		 * Extra options that get set up by third party add-ons
		 * and will be used to complete the "embed_options" meta value
		 */
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

		const
			{
				attributes: {
					embed_options,
					extra: extraOptions,
					video_id
				},
				setAttributes,
				className
			} = props,

			[embedOptions, setEmbedOptions] = useState( JSON.parse( embed_options ) )

			onFormToggleChange = varName => {
				setOption( varName, !embedOptions[ varName ] )

			},

			setOption = ( varName, value ) => {
				let _opt = {}
				_opt[ varName ] = value
				setEmbedOptions( { ...embedOptions, ..._opt } )
			}

			getStartTime = () => {
				const 	H = Math.floor( embedOptions.start_time / 3600 ),
						M = Math.floor( embedOptions.start_time / 60 ),
						S = embedOptions.start_time % 60

				return `${H}h${M}m${S}s`
			}

			getEmbedURL = () => {
				const 	url = 'https://player.vimeo.com/video',
						query = {
							title: embedOptions.title,
							byline: embedOptions.byline,
							portrait: embedOptions.portrait,
							loop: embedOptions.loop,
							color: embedOptions.color.replace('#', ''),
							autoplay: embedOptions.autoplay,
							volume: embedOptions.volume,
							dnt: embedOptions.dnt,
							start_time: embedOptions.start_time
						}

				return applyFilters(
					'vimeotheque.video-position.embed-url',
					`${url}/${video_id}?${jQuery.param( query )}#t=${getStartTime()}`,
					url,
					video_id,
					query,
					embedOptions.start_time,
					getStartTime()
				)
			}

		useEffect(
			() => {
				setEmbedOptions( JSON.parse( embed_options ) )
			}, [embed_options]
		)

		useEffect(
			() => {
				/**
				 * Combine embedOptions with extraOptions so that everything gets stored into the post meta
				 * for compatibility with the Classic Editor
				 */
				setAttributes({
					embed_options: JSON.stringify( { ...embedOptions, ...extraOptions } )
				})
			}, [embedOptions, extraOptions]
		)

		return [
			<div key="vimeotheque-video-position-block">
				<div
					className ={ "vimeotheque-player " + embedOptions.video_align }
					data-width = { embedOptions.width }
					data-aspect_ratio = { embedOptions.aspect_ratio }
					style = {
						{
							width: `${embedOptions.width}px`,
							maxWidth: '100%'
						}
					}
					onLoad = {
						event  => vimeotheque.resize( event.currentTarget )
					}
				>
					<iframe
						src={ getEmbedURL() }
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
								checked = {embedOptions.title}
								onChange = {
									() => onFormToggleChange( 'title' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show byline', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {embedOptions.byline}
								onChange = {
									() => onFormToggleChange( 'byline' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Show portrait', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {embedOptions.portrait}
								onChange = {
									() => onFormToggleChange( 'portrait' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Loop video', 'codeflavors-vimeo-video-post-lite' ) }
								checked = {embedOptions.loop}
								onChange =  {
									() => onFormToggleChange( 'loop' )
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label = { __( 'Autoplay video', 'codeflavors-vimeo-video-post-lite' ) }
								help = { __( "This feature won't work on all browsers.", 'codeflavors-vimeo-video-post-lite' ) }
								checked = {embedOptions.autoplay}
								onChange =  {
									() => onFormToggleChange( 'autoplay' )
								}
							/>
						</PanelRow>

						<PanelRow>
							<TextControl
								label = { __( 'Start time', 'codeflavors-vimeo-video-post-lite' ) }
								help = { sprintf( __( `Video playback initial start time in seconds. Must not exceed %s seconds.`, 'codeflavors-vimeo-video-post-lite' ), extraOptions.duration ) }
								type = "number"
								step = "1"
								value = { embedOptions.start_time }
								min = "0"
								max = { extraOptions.duration }
								onChange = {
									value => {
										const sTime = ( value >= 0 && value <= extraOptions.duration ) ? value : embedOptions.start_time
										setOption( 'start_time', sTime )
									}
								}
							/>
						</PanelRow>

						<PanelRow>
							<TextControl
								label = { __( 'Volume', 'codeflavors-vimeo-video-post-lite' ) }
								help = { __( 'Will work only for JS embeds', 'codeflavors-vimeo-video-post-lite' ) }
								type = "number"
								step = "1"
								value = { embedOptions.volume }
								min = "0"
								max = "100"
								onChange = {
									value => {
										const vol = ( value >= 0 && value <= 100 ) ? value : embedOptions.volume
										setOption( 'volume', vol )
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
									value = { embedOptions.width }
									min = "200"
									onChange = {
										value => {
											const width = ( !value || value < 200 ) ? 200 : value
											setOption( 'width', width )
											vimeotheque.resizeAll()
										}
									}
								/>
						</PanelRow>

						<PanelRow>
							<SelectControl
								label = { __( 'Aspect ratio', 'codeflavors-vimeo-video-post-lite' ) }
								value = { embedOptions.aspect_ratio }
								options = {[
									{ label: '4x3', value: '4x3' },
									{ label: '16x9', value: '16x9' },
									{ label: '2.35x1', value: '2.35x1' },
								]}
								onChange = {
									value => {
										setOption( 'aspect_ratio', value )
										setTimeout(  vimeotheque.resizeAll, 100 );
									}
								}
							/>
						</PanelRow>

						<PanelRow>
							<SelectControl
								label = { __( 'Align', 'codeflavors-vimeo-video-post-lite' ) }
								value = { embedOptions.video_align }
								options = {[
									{ label: 'left', value: 'align-left' },
									{ label: 'center', value: 'align-center' },
									{ label: 'right', value: 'align-right' },
								]}
								onChange = {
									value => {
										setOption( 'video_align', value )
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
								{ `${__( 'Player color', 'codeflavors-vimeo-video-post-lite' )} : ` }
								<ColorIndicator
									colorValue = { `#${embedOptions.color.replace( '#', '' )}` }
								/>
								<span>{ embedOptions.color && `#${embedOptions.color.replace( '#', '' )}` }</span>
							</label>
						</PanelRow>

						<PanelRow>
							<ColorPalette
								value = { `#${embedOptions.color.replace( '#', '' )}` }
								onChange = {
									color => {
										const col = color.replace( '#', '' )
										setOption( 'color', col )
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