import {forEach} from 'lodash'

const {
    blocks: {
        registerBlockType
    },
} = wp

import metadata from '../../../block/block.json'
import Edit from "./Edit";

registerBlockType(
    metadata,
    {
        edit: Edit,
        save: () => {}
    }
)