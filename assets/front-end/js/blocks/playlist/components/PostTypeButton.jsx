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
                onClick={this.handleChange}
            >
                { this.props.text }
            </Button>
        )
    }
}

export default PostTypeButton;