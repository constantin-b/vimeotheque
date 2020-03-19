import { unescape as unescapeString, repeat, flatMap, compact } from 'lodash'
const { withSelect } = wp.data,
    { useState } = wp.element

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

export const buildTree = ( elements, parentId = 0 )=>{
    let branch = []

    elements.forEach( ( element ) => {
        if( element.parent == parentId ){
            let _element = {
                id: element.id,
                name: element.name
            }

            let children = buildTree( elements, element.id )

            if( children ){
                _element.children = children
            }

            branch.push( _element )
        }
    } )

    return branch
}

export const optionsTree = ( tree, level = 0 ) => {
    return flatMap( tree, ( treeNode ) => [
        {
            value: treeNode.id,
            label:
                repeat( '\u00A0', level * 3 ) + unescapeString( treeNode.name ),
        },
        ...optionsTree( treeNode.children || [], level + 1 ),
    ] );
}