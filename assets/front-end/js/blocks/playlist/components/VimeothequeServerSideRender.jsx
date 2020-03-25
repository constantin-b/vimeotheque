import { isEqual } from 'lodash';
const { ServerSideRender } = wp.components

class VimeothequeServerSideRender extends ServerSideRender{

    constructor( props ) {
        super( props )
    }

    componentDidMount(){
        super.componentDidMount()
        this.props.onUpdate()
    }

    componentDidUpdate( prevProps, prevState ){
        if ( !isEqual( this.props.attributes, prevProps.attributes ) ) {
            super.componentDidUpdate( prevProps )
            this.props.onUpdate()

        }
    }

    render(){
        return super.render()
    }

}

VimeothequeServerSideRender.defaultProps = {
    onUpdate: false
}

export default VimeothequeServerSideRender