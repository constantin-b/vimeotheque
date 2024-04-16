import '../_store/paginationStore'
import '../video-list/store/itemsLoaded'
import {omit} from 'lodash'
import Item from "./Item";

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

const PostsList = props => {
	const
	 	page = useSelect( select => select('vimeotheque-series/app-options').getOption('currentPage') ),
		search = useSelect( select => select('vimeotheque-series/app-options').getOption( 'search' ) ),
		[selected, setSelected] = useState( false )

	const queryArgs = {
		page: page,
		per_page: 25,
		search: search,
		status: 'any',
		orderby: 'id',
		order: 'desc'
	}

	const {
		posts,
		isLoading
	} = useSelect(
		select => ({
			posts: select('core').getEntityRecords( 'postType', 'series', queryArgs ),
			isLoading: select('core/data').isResolving( 'core', 'getEntityRecords', ['postType', 'series', queryArgs] )
		})
	)

	useEffect(() => {
		if( posts ){
			dispatch('vimeotheque-series/items-loaded').addItems(posts)
		}
	}, [posts]);

	const series = useSelect(
		select => {
			return select('vimeotheque-series/items-loaded').getItems()
		}
	)

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
				.getPagination( addQueryArgs( 'wp/v2/series', omit( queryArgs, ['page'] ) ) )
		}
	)

	useEffect(
		() => {
			if( props.loadMore ){
				if( page + 1 <= totalPages ){
					dispatch( 'vimeotheque-series/app-options' ).updateOption('currentPage', page+1 )
				}
			}
		}, [props.loadMore]
	)

	const list = series.map(
		(item, i) => {
			return (
				<Item
					key={item.id}
					item={item}
					onClick={
						() => {
							setSelected(item)
						}
					}
				/>
			)
		}
	)

	const cancelSelection = () => {
		setSelected(false)
	}

	const onSelect = () => {
		props.onSelect(selected)
		// Close the modal window
		dispatch('vimeotheque-series/app-options').updateOption('openContentModal', false)
	}

	return (
        <div
            className="video-list vimeotheque-series-items-list"
        >
			<Flex
				justify='space-between'
				className='vimeotheque-item header'
			>
				<FlexItem>
					<strong>{__('ID', 'vimeotheque-series')}</strong>
				</FlexItem>
				<FlexItem
					isBlock={true}
				>
					<strong>{__('Name', 'vimeotheque-series')}</strong>
				</FlexItem>
				<FlexItem
					isBlock={true}
				>
					<strong>{__('Status', 'vimeotheque-series')}</strong>
				</FlexItem>
				<FlexItem
					isBlock={true}
				>
					<strong>{__('Type', 'vimeotheque-series')}</strong>
				</FlexItem>
				<FlexItem
					isBlock={true}
				>
					<strong>{__('Theme', 'vimeotheque-series')}</strong>
				</FlexItem>
				<FlexItem
					isBlock={true}
				>
					<strong>{__('Actions', 'vimeotheque-series')}</strong>
				</FlexItem>
			</Flex>

			{list}

			{
				!isLoading && series.length == 0 &&
					<div
						className='no-results'
					>
						<h2>
							{__('No results')}
						</h2>
						<p>
							{
								search !== ''
									? __('No matching results! Try another search.', 'vimeotheque-series')
									: __( 'Nothing found.', 'vimeotheque-series' )
							}
						</p>
					</div>
			}

			{
				isLoading &&
				<>
					<Spinner /> {__('Loading, please wait...', 'vimeotheque-series')}
				</>
			}

			{
				selected &&
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
									{ sprintf( __( 'Selected playlist ID %d: "%s"', 'vimeotheque-series' ), selected.id, selected.title.rendered || __('(no title)', 'vimeotheque-series') ) }
								</FlexItem>

								<FlexItem>
									<Button
										isPrimary={true}
										onClick={onSelect}
									>
										{__('Set playlist', 'vimeotheque-series')}
									</Button>
								</FlexItem>
							</Flex>
						</div>
					</div>
			}

        </div>
    )
}

PostsList.defaultProps = {
	loadMore: false,
	currentPage: 1,
	onSelect: () => {}
}

export default PostsList