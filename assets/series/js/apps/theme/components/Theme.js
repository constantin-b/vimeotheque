import {getEditedPostId} from "../../inc/functions";

const {
    components: {
        Button
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

const Theme = props => {

	const theme = useSelect(
		select => select('vimeotheque-series/playlist-options').getOption('theme')
	)

	const {
		screenshot,
		name,
		folder,
	} = props.theme

    return (
        <div
            className={`theme ${ theme==folder ? 'selected' : '' }`}
        >
			<img
				src={screenshot}
				title={name}
				width="300"
				onClick={
					() => {
						dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'theme': folder })
						dispatch('vimeotheque-series/playlist-options').updateOption( 'theme', folder )
					}
				}
			/>
        </div>
    )
}

Theme.defaultProps = {
	theme: {
		screenshot: '',
		name: '',
		folder: '',
	}
}

export default Theme