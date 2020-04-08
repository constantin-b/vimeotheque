const { withSelect } = wp.data

export const termQueryApplyWithSelect = withSelect( ( select, props ) => {
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
        loading: isResolving( 'core', 'getEntityRecords', [ 'taxonomy', props.taxonomy, query ] ),
        ...props
    }
})