import { optionsTree } from "../helpers/optionsTree";
import { termQueryApplyWithSelect } from '../helpers/termQueryApplyWithSelect'
import { buildTree } from '../helpers/buildTree'
import {
    find,
    without,
    compact,
    concat
} from 'lodash'

const {
    i18n : {
        __
    },
    components: {
        CheckboxControl
    }
} = wp

class CategoryListBase extends React.Component{
    constructor( props ) {
        super( props )

        this.handleChange = this.handleChange.bind(this)
    }

    isChecked( item ){
        return find( this.props.categories, {id: item.value} ) !== undefined
    }

    handleChange( item ){
        let items,
            _item = find( this.props.categories, {id: item.value} )

        if( _item !== undefined ){
            // uncheck, remove item
            items = without( this.props.categories, _item )
        }else{
            // checked, add item
            _item = find( this.props.terms, {id: item.value} )
            items = concat( this.props.categories, _item )
        }

        this.props.onChange( items )
    }

    render(){

        let options = []
        if( !this.props.loading ){
            options = this.props.loading ? [] : compact([
                ...optionsTree( buildTree( this.props.terms ) ),
            ])
        }

        return(
            <>
                <h4>{ this.props.title }</h4>
                {
                    this.props.loading ?
                        __( 'Loading, please wait...', 'codeflavors-vimeo-video-post-lite' )
                    :
                        options.length > 0 ?
                            <ul>
                                {
                                    options.map(
                                        element => (
                                            <li key = {element.value} >
                                                <CheckboxControl
                                                    label={element.label}
                                                    checked={ this.isChecked( element ) }
                                                    onChange={ ()=>{
                                                        this.handleChange( element )
                                                    }}
                                                />
                                            </li>
                                        )
                                    )
                                }
                            </ul>
                            :
                            <em>{this.props.messageEmpty}</em>
                }
            </>
        )
    }
}

CategoryListBase.defaultProps = {
    categories: [],
    loading: false,
    title: __( 'Categories', 'codeflavors-vimeo-video-post-lite' ),
    onChange: ()=>{},
    terms: [],
    taxonomy: 'category',
    messageEmpty: ''
}

const CategoryList = termQueryApplyWithSelect( CategoryListBase )

CategoryList.defaultProps = {
    taxonomy: 'category'
}

export default CategoryList