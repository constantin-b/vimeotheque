import ListItem from "./ListItem";

const   { apiFetch } = wp,
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
        let self = this
        self.setState( {loading: true} )
        apiFetch( {
            path: '/wp/v2/' +
                self.props.postType +
                '?page=' + self.props.page +
                '&per_page=' + self.props.perPage +
                '&orderby=date&order=desc' +
                '&vimeothequeMetaKey=true',
            parse: false
        } ).then( ( response ) => {
            self.setState({
                totalEntries: response.headers.get( 'X-WP-Total' ),
                totalPages: response.headers.get( 'X-WP-TotalPages' )
            })

            return response.json()
        } ).then( posts => {
            let _posts = [...self.state.posts, ...posts]
            self.setState( {
                posts: _posts,
                loading: false
            } )
            self.props.onRequestFinish({
                postsCount:  _posts.length,
                totalEntries: self.state.totalEntries,
                totalPages: self.state.totalPages
            })
        }).catch( ( error )=>{
            self.setState( {
                error: error,
                loading: false
            } )
            self.props.onRequestError( error )
        } );
    }

    componentDidMount(){
       this.makeRequest()
    }

    componentDidUpdate( prevProps ){
        if( this.props.postType != prevProps.postType && 'selected' != prevProps.postType ){
            this.setState({
                posts:[],
                loading:false,
                error: false,
                totalEntries: 0,
                totalPages: 0
            })
            this.makeRequest();
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
                        { __( 'Please wait, your video posts are loading...', 'cvm_video' ) }
                        </div>
                }
            </div>
        )
    }
}

List.defaultProps = {
    postType: 'vimeo-video',
    page: 1,
    perPage: 10,
    selected: [],
    onSelect: ( post ) => {},
    onRemove: ( post ) => {},
    onRequestFinish: () => {},
    onRequestError: () => {}
}

export default List