import PostTypeButton from "./PostTypeButton";

const { __ } = wp.i18n,
    {
        ButtonGroup,
        Button
    } = wp.components


class ListMenu extends React.Component{
    constructor(props) {
        super(props)
        this.handlePostTypeChange = this.handlePostTypeChange.bind(this)
    }

    selectedClassName( postType, className = '' ){
        let _className = this.props.postType == postType ? 'active' : 'inactive'
        return `${_className} ${className}`
    }

    handlePostTypeChange( postType ){
        if( postType == this.props.postType || this.props.disabled ){
            return;
        }

        this.props.onPostTypeChange( postType )
    }

    render(){

        return (
            <ButtonGroup
                className="vimeotheque-post-type-filter"
            >
                <PostTypeButton
                    className={ this.selectedClassName( 'vimeo-video' ) }
                    postType='vimeo-video'
                    text={__( 'Vimeo Videos', 'cvm_video' )}
                    onClick={ this.handlePostTypeChange }
                    disabled={this.props.disabled}
                />
                <PostTypeButton
                    className={ this.selectedClassName( 'posts' ) }
                    postType='posts'
                    text={__( 'Posts', 'cvm_video' )}
                    onClick={ this.handlePostTypeChange }
                    disabled={this.props.disabled}
                />
                <Button
                    isLink
                    className={ this.selectedClassName( 'selected', 'selected-posts' ) }
                    disabled={this.props.disabled}
                    onClick={
                        ()=>{
                            this.handlePostTypeChange( 'selected' )
                        }
                    }
                >
                    { this.props.textSelected }
                </Button>
            </ButtonGroup>
        )
    }
}

ListMenu.defaultProps = {
    postType: 'vimeo-video',
    disabled: false,
    textSelected: __('Selected', 'cvm_video'),
    onPostTypeChange: () => {}
}

export default ListMenu