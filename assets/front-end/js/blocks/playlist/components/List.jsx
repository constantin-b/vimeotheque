import { map, keys } from 'lodash'
import ListItem from "./ListItem";

const   { apiFetch } = wp,
    {
        Spinner
    } = wp.components,
    { __ } = wp.i18n;

class List extends React.Component{
    constructor(props) {
        super(props)
        this.state = {
            posts: [],
            loading: false,
            error: false,
            totalEntries: 0,
            totalPages: 0
        }
    }

    makeRequest(){
        this.setState( {loading: true} )

        this.props.onRequestBegin();

        let params = {
            page: this.props.page,
            search: this.props.search.query,
            per_page: this.props.perPage,
            orderby: 'date',
            order: 'desc',
            vimeothequeMetaKey: 'true'
        }

        if( this.props.search.category ){
            let taxonomy = 'category' == this.props.taxonomy ? 'categories' : this.props.taxonomy
            params[ taxonomy ] = this.props.search.category
        }

        let path = `/wp/v2/${this.props.postType}?` + map( keys( params ), key => { return `${key}=${params[ key ]}` } ).join('&'),
            posts = wp.data.select( 'vimeotheque-post-store' ).getPosts( path )

        if( posts != undefined ){
            let _posts = [...this.state.posts, ...posts.posts]
            this.setState({
                totalEntries: posts.total,
                totalPages: posts.pages,
                loading: false,
                posts: _posts
            })

            this.props.onRequestFinish({
                postsCount:  _posts.length,
                totalEntries: posts.total,
                totalPages: posts.pages
            })
        }else {

            apiFetch({
                path: path,
                method: 'GET',
                parse: false
            }).then(response => {
                this.setState({
                    totalEntries: response.headers.get('X-WP-Total'),
                    totalPages: response.headers.get('X-WP-TotalPages')
                })

                return response.json()
            }).then(posts => {
                let _posts = [...this.state.posts, ...posts]
                this.setState({
                    posts: _posts,
                    loading: false
                })
                this.props.onRequestFinish({
                    postsCount: _posts.length,
                    totalEntries: this.state.totalEntries,
                    totalPages: this.state.totalPages
                })

                wp
                    .data
                    .dispatch( 'vimeotheque-post-store' )
                    .addPosts( path, posts, this.state.totalEntries, this.state.totalPages )

            }).catch(error => {
                this.setState({
                    error: error,
                    loading: false
                })
                this.props.onRequestError(error)
            });
        }
    }

    componentDidMount(){
       this.makeRequest()
    }

    componentDidUpdate( prevProps ){
        if(
            ( this.props.postType != prevProps.postType && 'selected' != prevProps.postType ) ||
            this.props.search != prevProps.search
        ){
            this.setState({
                posts:[],
                loading:false,
                error: false,
                totalEntries: 0,
                totalPages: 0
            }, this.makeRequest )
            return;
        }

        if( this.props.page != prevProps.page && !this.state.error && this.props.page <= this.state.totalPages ) {
            this.makeRequest()
        }
    }

    hasPost( post ){
        for( let _post of this.props.selected ){
            if( _post.id == post.id ){
                return true
            }
        }
    }

    selectText( post ){
        return this.hasPost( post ) ?
            __( 'Remove video', 'cvm_video' ) :
            __( 'Select video', 'cvm_video' )
    }

    render(){
        if( !this.state.loading && this.state.posts.length == 0 ){
            return(
                <div className="vimeotheque-error">
                    {
                        this.state.error ?
                            this.state.error.message :
                            __( 'We couldn\'t find any video posts, sorry.', 'cvm_video' )
                    }
                </div>
            )
        }

        return(
            <div className="vimeotheque-entries-container">
                <div className="vimeotheque-entries row">
                    {
                        this.state.posts.map(
                            (post, index) => (
                                <ListItem
                                    className={
                                        this.hasPost(post) &&
                                            'vimeotheque-selected-post'
                                    }
                                    post={post}
                                    onSelect={
                                        post => {
                                            if( this.hasPost( post ) ){
                                                this.props.onRemove( post )
                                            }else{
                                                this.props.onSelect( post )
                                            }
                                        }
                                    }
                                    selectText={ this.selectText( post ) }
                                    key={post.id}
                                />
                            )
                        )
                    }
                </div>
                {
                    this.state.loading &&
                        <div className="vimeotheque-loading">
                            <Spinner />
                            { __( 'Please wait, your video posts are loading...', 'cvm_video' ) }
                        </div>
                }
            </div>
        )
    }
}

List.defaultProps = {
    postType: 'vimeo-video',
    search: { query: '', category: false },
    taxonomy: false,
    page: 1,
    perPage: 10,
    selected: [],
    onSelect: ( post ) => {},
    onRemove: ( post ) => {},
    onRequestBegin: () => {},
    onRequestFinish: () => {},
    onRequestError: () => {}
}

export default List

// Redux store begin
import {find} from 'lodash'

// reducer
const addPostsResource = ( state = [], action ) => {
    if( action.type == 'ADD_POSTS' ) {
        return state.concat( [action.payload] )
    }

    return state
}

// selector
const getPosts = ( state, url ) => {
    let obj = find( state, { url: url } )
    return obj
}

// action for adding posts
const addPosts = ( url, posts, total, pages ) => {
    return {
        type: 'ADD_POSTS',
        payload: {
            url: url,
            posts: posts,
            total: total,
            pages: pages
        }
    }
}

wp.data.registerStore( 'vimeotheque-post-store', {
    reducer: addPostsResource,
    selectors: { getPosts: getPosts },
    actions: { addPosts: addPosts }
})