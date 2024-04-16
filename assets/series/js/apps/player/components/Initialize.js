import {getEditedPostId} from "../../inc/functions";

const {
    components: {
		Flex,
		FlexItem,
		RangeControl,
		TextControl,
    },
	data: {
		dispatch,
		useSelect,
	},
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Initialize = props => {

	const
		volume = useSelect( select => select('vimeotheque-series/playlist-options').getOption('volume') ),
		width = useSelect( select => select('vimeotheque-series/playlist-options').getOption('width') )

	const updateOption = (option, value) => {

		let options = {}
		options[ option ] = value

		dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), options )
		dispatch('vimeotheque-series/playlist-options').updateOption( option, value )
	}

    return (
        <>
			<Flex
				align='flex-start'
				className='row'
			>
				<FlexItem
					isBlock={false}
				>
					<TextControl
						label={ __( 'Maximum width', 'vimeotheque-series' ) }
						type="number"
						step={5}
						value={width}
						min={300}
						onChange={
							value => {
								updateOption('width', value )
							}
					}
					/>
				</FlexItem>
			</Flex>

			<Flex
				align='flex-start'
				className='row'
			>
				<FlexItem
					isBlock={true}
				>
					<RangeControl
						label={__('Volume', 'vimeotheque-series')}
						currentInput='35'
						value={volume}
						marks={[
							{
								value:0,
								label:'0'
							},
							{
								value:50,
								label:'50'
							},
							{
								value:100,
								label:'100'
							}
						]}
						min={0}
						max={100}
						step={1}
						isShiftStepEnabled = { true }
						withInputField = {true}
						onChange={ value => updateOption( 'volume', value ) }
					/>
				</FlexItem>
			</Flex>
        </>
    )
}

Initialize.defaultProps = {
	item: false
}

export default Initialize