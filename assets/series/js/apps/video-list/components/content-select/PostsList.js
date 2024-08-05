import '../../../_store/paginationStore'
import '../../store/itemsLoaded'
import {omit} from 'lodash'
import VideoItem from "../videos-list/VideoItem";

const {
    components: {
        Button,
		Flex,
		FlexItem,
		Spinner,
    },
	data:{
		dispatch,
		useSelect
	},
    element: {
        useEffect,
        useState
    },
    i18n: {
        __,
		_n,
		sprintf,
    },
	url: {
		addQueryArgs
	}
} = wp

const PostsList = ({
   loadMore = false,
   currentPage = 1,
   onSelect = () => {}
}) => {
	const
	 	page = useSelect( select => select('vimeotheque-series/app-options').getOption('currentPage') ),
		search = useSelect( select => select('vimeotheque-series/app-options').getOption( 'search' ) )

	const queryArgs = {
		page: page,
		per_page: 10,
		search: search,
	}

	const videos = useSelect(
		select => {
			return select('vimeotheque-series/items-loaded').getItems()
		}
	)

	const {
		posts,
		isLoading
	} = useSelect(
		select => ({
			posts: select('core').getEntityRecords( 'postType', 'vimeo-video', queryArgs ),
			isLoading: select('core/data').isResolving( 'core', 'getEntityRecords', ['postType', 'vimeo-video', queryArgs] )
		})
	)

	useEffect(() => {
		if( posts ){
			dispatch('vimeotheque-series/items-loaded').addItems(posts)
		}
	}, [posts]);

	/**
	 * Hack to replace missing functionality in WP that will be implemented
	 * sometime in the future.
	 * Docs specify that getEntityRecordsTotalItems() and getEntityRecordsTotalPages()
	 * can be used, but they are currently unavailable.
	 */
	const {
		pages: totalPages,
		total: totalResults
	} = useSelect(
		select => {
			return select('vimeotheque-series/pagination-store')
				.getPagination( addQueryArgs( 'wp/v2/vimeo-video', omit( queryArgs, ['page'] ) ) )
		}
	)

	useEffect(
		() => {
			if( !isLoading && loadMore ){
				if( page + 1 <= totalPages ){
					dispatch( 'vimeotheque-series/app-options' ).updateOption('currentPage', page+1 )
				}
			}
		}, [loadMore]
	)

	const list = videos.map(
		item => {
			return (
				<VideoItem
					key={item.id}
					selectable={true}
					item={item}
				/>
			)
		}
	)

	const cancelSelection = () => {
		dispatch( 'vimeotheque-series/items-selection' ).updateOption( [] )
	}

	const items = useSelect( select => select('vimeotheque-series/items-selection').getItems() )

	const onSelection = () => {
		// Update the selected items list
		dispatch('vimeotheque-series/items-store').updateOption( items )
		// Close the modal window
		dispatch('vimeotheque-series/app-options').updateOption('openContentModal', false)
		// Trigger action
		onSelect()
	}

	return (
        <div
            className="video-list vimeotheque-series-items-list"
        >
			{list}

			{
				!isLoading && videos.length == 0 &&
					<div
						className='no-results'
					>
						<h2>
							{__('No results')}
						</h2>
						<p>
							{
								search !== ''
									? __('No matching results! Try another search.', 'codeflavors-vimeo-video-post-lite')
									: __( 'Nothing found.', 'codeflavors-vimeo-video-post-lite' )
							}
						</p>
					</div>
			}

			{
				items.length == 0 && isLoading &&
				<>
					<Spinner /> {__('Loading videos, please wait...', 'codeflavors-vimeo-video-post-lite')}
				</>
			}

			{
				items.length > 0 &&
					<div
						className="action-bar"
					>
						<div className='inside'>
							<Flex
								className='actions'
							>
								<FlexItem>
									<Button
										icon='no'
										onClick={ cancelSelection }
									/>
								</FlexItem>

								<FlexItem
									isBlock={true}
								>
									{ sprintf( _n( '%d selected', '%d selected', items.length, 'codeflavors-vimeo-video-post-lite' ), items.length ) }
								</FlexItem>

								{
									isLoading &&
									<FlexItem
										isBlock={true}
									>
										<Spinner /> {__('Loading videos, please wait...', 'codeflavors-vimeo-video-post-lite')}
									</FlexItem>
								}

								<FlexItem>
									<Button
										isPrimary={true}
										onClick={onSelection}
									>
										{__('Add videos', 'codeflavors-vimeo-video-post-lite')}
									</Button>
								</FlexItem>
							</Flex>
						</div>
					</div>
			}

        </div>
    )
}

export default PostsList