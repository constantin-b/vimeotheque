import List from "./List"
import ListSelected from "./ListSelected"
import {isEqual} from 'lodash'

const   { apiFetch } = wp,
        { __ } = wp.i18n,
        {
            Button,
            ButtonGroup
        } = wp.components,
        { withState } = wp.compose;

/**
 * Video posts list component
 */
class VideoPostsList extends React.Component {
    constructor( props ) {
        super( props )
        this.state = {
            page: 1,
            loading: false,
            error: false,
            postsCount:0,
            totalPages: 0
        }
        this.handleLoadMore = this.handleLoadMore.bind(this)
        this.requestFinish = this.requestFinish.bind(this)
    }

    isLoading(){
        return this.state.loading
    }

    isError(){
        return this.state.error
    }

    componentDidMount(){
        jQuery( '.vimeotheque-posts-list-modal' ).scroll(
            ()=>{
                if( 'selected' != this.props.postType ) {
                    let scrollTop = jQuery('.vimeotheque-posts-list-modal').scrollTop(),
                        innerHeight = jQuery('.vimeotheque-posts-list-modal').innerHeight(),
                        scrollHeight = jQuery('.vimeotheque-posts-list-modal')[0].scrollHeight

                    if ( scrollTop + innerHeight >= scrollHeight - 400 ) {
                        if( !this.isLoading() ) {
                            this.handleLoadMore()
                        }
                    }
                }
            }
        )
    }

    shouldComponentUpdate( nextProps, nextState ){
        if( !isEqual( this.props.search, nextProps.search ) ){
            this.setState({
                page:1,
                loading:true,
                postsCount:0
            })
            return false
        }

        if( nextProps.postType != this.props.postType && !this.isLoading() ){
            this.setState({
                page: 1,
                loading:( 'selected' != nextProps.postType ),
                postsCount: 0
            })
            return false
        }

        return true
    }

    requestFinish( result ){
        this.setState({
            loading:false,
            error:false,
            postsCount: result.postsCount,
            totalPages: result.totalPages
        })

        this.props.onRequestFinish()

        let scrollHeight = jQuery('.vimeotheque-posts-list-modal')[0].scrollHeight,
            height = jQuery('.vimeotheque-posts-list-modal').height()

        if( height == scrollHeight ){
            this.handleLoadMore()
        }


    }

    handleLoadMore(){
        if( !this.isLoading() && !this.isError() && this.state.postsCount && this.state.totalPages > this.state.page ) {
            this.setState({
                page: this.state.page + 1,
                loading: true
            })
        }
    }

    render() {
        return (
             'selected' == this.props.postType
                 ?
                 <ListSelected
                     posts = { this.props.filteredPosts }
                     onSelect={ this.props.onRemove }
                 />
                 :
                 <List
                     postType = { this.props.postType }
                     search = { this.props.search }
                     taxonomy={this.props.taxonomy}
                     page = { this.state.page }
                     perPage = { this.props.perPage }
                     onSelect = { this.props.onSelect }
                     onRemove = { this.props.onRemove }
                     selected = {this.props.filteredPosts }
                     onRequestBegin = {()=>{
                         this.setState({loading:true})
                         this.props.onRequestBegin()
                     }}
                     onRequestFinish = { this.requestFinish }
                     onRequestError = {
                         (error) => {
                             this.setState({
                                 loading:false,
                                 error:error
                             })
                             this.props.onRequestError
                         }
                     }
                 />
        )
    }
}

/**
 *
 * @type {{perPage: number, filteredPosts: [], postType: string, onSelect: VideoPostsList.defaultProps.onSelect}}
 */
VideoPostsList.defaultProps = {
    postType: 'vimeo-video',
    search: { query: '', category: false },
    taxonomy: false,
    perPage: 30,
    onSelect: ( post ) => {},
    onRemove: ( post ) => {},
    onRequestFinish: () => {},
    onRequestBegin: () => {},
    onRequestError: () => {},
    filteredPosts: [],
    filteredCategories: []
}

export default VideoPostsList;