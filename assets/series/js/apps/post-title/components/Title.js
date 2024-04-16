import {getEditedPostId} from "../../inc/functions";

const {
    components: {
        Button,
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

const Title = props => {

	const title = useSelect( select => select( 'vimeotheque-series/playlist-options' ).getOption('postTitle') )

    return (
        <div
            className=""
        >
			<>
				<TextControl
					value={title||''}
					placeholder={__( 'Enter a title for this collection', 'vimeotheque-series' )}
					onChange={
						value => {
							dispatch( 'vimeotheque-series/playlist-options' ).updateOption('postTitle', value)
							dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'title': value })
						}
					}
				/>
			</>
        </div>
    )
}

Title.defaultProps = {}

export default Title