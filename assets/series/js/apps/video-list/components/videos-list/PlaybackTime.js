import {sumBy} from 'lodash'

const {
    components: {
        Button
    },
    data: {
        useSelect
    },
    date: {
    	gmdate
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const PlaybackTime = props => {

    const
        displayContent = useSelect( select => select('vimeotheque-series/playlist-options').getOption( 'contentType' ) ),
        items = useSelect( select => select('vimeotheque-series/items-store').getItems() ),
        totalTime = sumBy( items, item => parseInt( item.vimeo_video.duration ) )

    return (
        <>
            {
                'posts' == displayContent && totalTime > 0 &&
					<div>
						{__('Active playback time: ', 'codeflavors-vimeo-video-post-lite')}
						<strong>
                            {new Date(totalTime * 1000).toISOString().slice(11, 19)}
						</strong>
					</div>
            }

        </>

    )
}

export default PlaybackTime