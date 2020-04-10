import PostTypeButton from "./PostTypeButton";
import {map} from 'lodash'

const { __ } = wp.i18n,
    {
        postTypes
    } = vmtq,
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
                {
                    map( postTypes, postType => {
                        return (
                            <PostTypeButton
                                key={postType.post_type.name}
                                className={ this.selectedClassName( postType.post_type.name ) }
                                postType={ postType.post_type.name }
                                text={ postType.post_type.labels.name }
                                onClick={ this.handlePostTypeChange }
                                disabled={ this.props.disabled }
                            />
                        )
                    } )
                }
                {
                    !this.props.hideSelected &&
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
                }
            </ButtonGroup>
        )
    }
}

ListMenu.defaultProps = {
    postType: 'vimeo-video',
    disabled: false,
    textSelected: __('Selected', 'cvm_video'),
    hideSelected: false,
    onPostTypeChange: () => {}
}

export default ListMenu