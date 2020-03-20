const {
        Button
    } = wp.components;

class PostTypeButton extends React.Component{

    constructor( props ) {
        super( props );
        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(e) {
        this.props.onClick( this.props.postType );
    }

    render(){
        return (
            <Button
                className={this.props.className}
                onClick={this.handleChange}
                disabled={this.props.disabled}
            >
                { this.props.text }
            </Button>
        )
    }
}

PostTypeButton.defaultProps = {
    className: ''
}

export default PostTypeButton;