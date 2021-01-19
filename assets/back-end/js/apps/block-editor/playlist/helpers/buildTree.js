export const buildTree = ( elements, parentId = 0 )=>{
    let branch = []

    // there's a possibility that no categories are set for the custom post type. In this case, return an empty array.
    if( !elements ){
        return branch
    }

    elements.forEach( element => {
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