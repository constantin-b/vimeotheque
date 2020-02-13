import PostTypeButton from "./PostTypeButton";
import List from "./List";
import ListSelected from "./ListSelected";

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
            // used inly to initialize state; controlled exclusively internally
            postType: this.props.postType,
            page: 1,
            loading: false,
            error: false,
            postsCount:0,
            totalPages: 0
        }
        this.handlePostTypeChange = this.handlePostTypeChange.bind(this)
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
                if( 'selected' != this.state.postType ) {
                    let scrollTop = jQuery('.vimeotheque-posts-list-modal').scrollTop(),
                        innerHeight = jQuery('.vimeotheque-posts-list-modal').innerHeight(),
                        scrollHeight = jQuery('.vimeotheque-posts-list-modal')[0].scrollHeight

                    if (scrollTop + innerHeight >= scrollHeight - 400 ) {
                        this.handleLoadMore()
                    }
                }
            }
        )
    }

    requestFinish( result ){
        this.setState({
            loading:false,
            error:false,
            postsCount: result.postsCount,
            totalPages: result.totalPages
        })

        let scrollHeight = jQuery('.vimeotheque-posts-list-modal')[0].scrollHeight,
            height = jQuery('.vimeotheque-posts-list-modal').height()

        if( height == scrollHeight ){
            this.handleLoadMore()
        }

    }

    handlePostTypeChange( postType ){
        if( postType == this.state.postType || this.isLoading() ){
            return;
        }

        this.setState({
            postType:postType,
            page:1,
            loading:true,
            postsCount:0
        })
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
        let list
        if( this.state.postType == 'selected' ){
            list = <ListSelected
                posts = { this.props.filteredPosts }
                onSelect={ this.props.onRemove }
            />
        }else{
            list = <List
                postType = { this.state.postType }
                page = { this.state.page }
                perPage = { this.props.perPage }
                onSelect = { this.props.onSelect }
                onRemove = { this.props.onRemove }
                selected = {this.props.filteredPosts }
                onRequestFinish = { this.requestFinish }
                onRequestError = {
                    (error) => {
                        this.setState({
                            loading:false,
                            error:error
                        })
                    }
                }
            />
        }

        let selectedTxt = `${__( 'Selected', 'cvm_video' )} (${this.props.filteredPosts.length})`

        return (
            <div
                className="vimeotheque-post-list-container"
                key="vimeotheque-post-list-container"
            >
                <ButtonGroup
                    className="vimeotheque-post-type-filter"
                >
                    <PostTypeButton
                        postType='vimeo-video'
                        text={__( 'Vimeo Videos', 'cvm_video' )}
                        onClick={ this.handlePostTypeChange }
                    />&nbsp;|&nbsp;
                    <PostTypeButton
                        postType='posts'
                        text={__( 'Posts', 'cvm_video' )}
                        onClick={ this.handlePostTypeChange }
                    />
                    {
                        this.props.filteredPosts.length > 0 &&
                        <Button
                            isLink
                            className='selected-posts'
                            onClick={
                                ()=>{
                                    this.setState({postType:'selected'})
                                }
                            }
                        >
                            { selectedTxt }
                        </Button>
                    }
                </ButtonGroup>

                { list }
            </div>
        );
    }
}

/**
 *
 * @type {{perPage: number, filteredPosts: [], postType: string, onSelect: VideoPostsList.defaultProps.onSelect}}
 */
VideoPostsList.defaultProps = {
    postType: 'vimeo-video',
    perPage: 30,
    onSelect: ( post ) => {},
    onRemove: ( post ) => {},
    filteredPosts: []
}

export default VideoPostsList;