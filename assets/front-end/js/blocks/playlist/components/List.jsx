const   { apiFetch } = wp,
    { __ } = wp.i18n,
    { dateI18n } = wp.date,
    {
        Button,
        ButtonGroup
    } = wp.components;

class List extends React.Component{
    constructor(props) {
        super(props)
        this.state = {
            posts: [],
            loading: false,
            error: false
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
                '&vimeothequeMetaKey=true'
        } ).then( posts => {
            let _posts = [...self.state.posts, ...posts]
            self.setState( {
                posts: _posts,
                loading: false
            } )
            self.props.onRequestFinish( _posts.length )
        } ).catch( ( error )=>{
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
        if( this.props.postType != prevProps.postType ){
            this.setState({
                posts:[],
                loading:false,
                error: false
            })
            this.makeRequest();
            return;
        }

        if( ( this.props.page != prevProps.page || this.props.postType != prevProps.postType ) && !this.state.error ) {
            this.makeRequest()
        }
    }

    render(){
        let messages
        if( this.state.loading ){
            messages =  <div className="vimeotheque-loading vimeotheque-post-list-container">
                            { __( 'Please wait, your video posts are loading...', 'cvm_video' ) }
                        </div>

        }else if( this.state.posts.length == 0 ){
            messages =  <div className="vimeotheque-error vimeotheque-post-list-container">
                            {
                                this.state.error ?
                                    this.state.error.message :
                                    __( 'We couldn\'t find any video posts, sorry.', 'cvm_video' )
                            }
                        </div>
        }

        console.log( this.state.posts );

        return(
            <div>
                <div className="vimeotheque-entries row">
                    {
                        this.state.posts.map(
                            post => (
                                <div
                                    key={ post.id }
                                    className='col-xs-6  col-sm-4 col-md-3 col-lg-2 grid-element'
                                >
                                    <div
                                        className='cvm-video'
                                    >
                                        <div className='cvm-thumbnail'>
                                            {
                                                post.vimeo_video != null &&
                                                    <div>
                                                        <img src={post.vimeo_video.thumbnails[2]} />
                                                        <span className='duration'>{post.vimeo_video._duration}</span>
                                                    </div>
                                            }
                                        </div>
                                        <div className='details'>
                                            <h4>{ post.title.rendered }</h4>

                                            <div className="meta">
                                                <span className="publish-date">{ dateI18n( 'M d Y', post.date ) }</span>
                                            </div>

                                            <div className='actions'>
                                                <Button
                                                    isTertiary
                                                    onClick={
                                                        () => {
                                                            this.props.onClick( post.id )
                                                        }
                                                    }
                                                >
                                                    { __( 'Add to list', 'cvm_video' ) }
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )
                        )
                    }
                </div>
                {messages}
            </div>
        )
    }
}

List.defaultProps = {
    postType: 'vimeo-video',
    page: 1,
    perPage: 10,
    onClick: () => {},
    onRequestFinish: () => {},
    onRequestError: () => {}
}

export default List