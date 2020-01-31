import PostTypeButton from "./PostTypeButton";
import List from "./List";

const   { apiFetch } = wp,
        { __ } = wp.i18n,
        {
            Button,
            ButtonGroup
        } = wp.components;

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
            postsCount:0
        }
        this.handlePostTypeChange = this.handlePostTypeChange.bind(this)
        this.handleLoadMore = this.handleLoadMore.bind(this)
    }

    isLoading(){
        return this.state.loading
    }

    isError(){
        return this.state.error
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
        if( !this.isLoading() && !this.isError() && this.state.postsCount ) {
            this.setState({
                page: this.state.page + 1,
                loading: true
            })
        }
    }

    render() {
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
                </ButtonGroup>

                <Button
                    isPrimary
                    onClick={ this.handleLoadMore }
                >
                    { __('Load more', 'cvm_video') }
                </Button>

                <List
                    postType={this.state.postType}
                    page={this.state.page}
                    perPage={this.props.perPage}
                    onClick={this.props.onClick}
                    onRequestFinish={
                        ( postsCount ) => {
                            this.setState({
                                loading:false,
                                error:false,
                                postsCount: postsCount
                            })
                        }
                    }
                    onRequestError={
                        (error) => {
                            this.setState({
                                loading:false,
                                error:error
                            })
                        }
                    }
                />
            </div>
        );
    }
}

/**
 * Component defaults
 *
 * @type {{onClick: VideoPostsList.defaultProps.onClick, postType: string, page: number}}
 */
VideoPostsList.defaultProps = {
    postType: 'vimeo-video',
    perPage: 20,
    onClick: () => {}
}

export default VideoPostsList;