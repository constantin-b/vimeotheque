const { withSelect } = wp.data,
        {compose} = wp.compose;

class TestComponentBase extends React.Component{
    constructor(props) {
        super(props)

        console.log( props )

    }

    render(){
        if ( this.props.isRequesting ) {
            return (
                <div className="loading">
                    Loading...
                </div>
            );
        }
        return (
            <>
                <h3>This is TestComponent.jsx</h3>
                <ul>
                    {
                        this.props.terms.map( term => (
                            <li key={ term.id }>{ term.name }</li>
                        ))
                    }
                </ul>
            </>
        )
    }
}

/*
const TestComponentBase = props => {

    console.log(props)

    if ( props.isRequesting ) {
        return (
            <div className="loading">
                Loading...
            </div>
        );
    }
    return (
        <>
            <h3>This is TestComponent.jsx</h3>
            <ul>
                {
                    props.terms.map( term => (
                        <li key={ term.id }>{ term.name }</li>
                    ))
                }
            </ul>
        </>
    );
}
*/

const applyWithSelect = withSelect( (select, props) => {
    const { isResolving } = select( 'core/data' );
    const query = {
        per_page: 100,
        orderby : 'name',
        order : 'asc',
        _fields : 'id,name,parent,taxonomy',
        _locale : 'user'
    }
    return {
        terms: select('core').getEntityRecords( "taxonomy", props.taxonomy, query ),
        isRequesting: isResolving( 'core', 'getEntityRecords', [ 'taxonomy', props.taxonomy, query ] ),
        ...props
    }
})

const TestComponent = applyWithSelect( TestComponentBase )

TestComponent.defaultProps={
    taxonomy: 'vimeo-videos'
}


export default TestComponent