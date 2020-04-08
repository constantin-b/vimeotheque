import {
    unescape as unescapeString,
    repeat,
    flatMap
} from 'lodash'

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