import '../../../_store/paginationStore'
import {omit} from 'lodash'

const {
	data: {
		useSelect
	},
	url: {
		addQueryArgs
	}
} = wp

const Pagination = ({
	path = '',
	queryArgs = {
		page: 1,
		per_page: 20
	},
	onPageChange = () => {}
}) => {

	/**
	 * Hack to replace missing functionality in WP that will be implemented
	 * sometime in the future.
	 * Docs currently specify that getEntityRecordsTotalItems() and getEntityRecordsTotalPages()
	 * can be used, but they are currently unavailable.
	 */
	const {
		pages: totalPages,
		total: totalResults
	} = useSelect(
		select => {
			return select('vimeotheque-series/pagination-store')
				.getPagination( addQueryArgs( path, omit( queryArgs, ['page'] ) ) )
		}
	)

    return (
        <div
            className=""
        >

        </div>
    )
}

export default Pagination