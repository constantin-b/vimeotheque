import { isEqual } from 'lodash';

const ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender

class ServerSideRenderExtended extends ServerSideRender{

    constructor( props ) {
        super( props )
    }

    componentDidMount(){

        if( 'function' == typeof super.componentDidMount ) {
            super.componentDidMount()
        }

        this.props.onUpdate()
    }

    componentDidUpdate( prevProps, prevState ){
        if ( !isEqual( this.props.attributes, prevProps.attributes ) ) {
            if( 'function' == typeof super.componentDidUpdate ) {
                super.componentDidUpdate(prevProps)
            }
            this.props.onUpdate()
        }
    }

    render(){
        return super.render()
    }

}

ServerSideRenderExtended.defaultProps = {
    onUpdate: false
}

export default ServerSideRenderExtended