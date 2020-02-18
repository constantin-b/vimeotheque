import ListItem from "./ListItem";

const { __ } = wp.i18n;

class ListSelected extends React.Component{

    constructor( props ) {
        super( props );
    }

    render(){
        let messages

        if( this.props.posts.length == 0 ){
            messages =  <div className="vimeotheque-error vimeotheque-post-list-container">
                { __( 'Sorry, nothing yet! Your selected videos will appear here.', 'cvm_video' ) }
            </div>
        }

        return (
            <div>
                <div className="vimeotheque-entries row">
                    {
                        this.props.posts.map(
                            post => (
                                <ListItem
                                    post={post}
                                    onSelect={ (post) => { this.props.onSelect(post) } }
                                    selectText={ __('Remove', 'cvm_video') }
                                    key={post.id}
                                />
                            )
                        )
                    }
                </div>
                {messages}
            </div>
        )
    }
}

ListSelected.defaultProps = {
    posts: [],
    onSelect: (post)=>{}
}

export default ListSelected