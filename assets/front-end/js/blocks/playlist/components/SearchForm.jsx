import VimeothequeTreeSelect from "./VimeothequeTreeSelect"
import { findIndex, pull, has, concat } from 'lodash'

const { __ } = wp.i18n,
    {
        Spinner,
        TextControl,
        Button,
        ToggleControl
    } = wp.components

class SearchForm extends React.Component{
    constructor( props ) {
        super( props )
        this.state = {
            // the search query entered by user
            query: props.values.query,
            // the search category selected by user
            category: this.sanitizeCategory( props.values.category ),
            // array of selected category options
            selected: this.props.selectedCategories,
            // current selected taxonomy option is checked to be used in playlist?
            optionSelected: false,
            // was search form submitted
            submitted: false
        }

        this.isChecked = this.isChecked.bind(this)
        this.setCategories = this.setCategories.bind(this)
        this.getCategories = this.getCategories.bind(this)
        this.sanitizeCategory = this.sanitizeCategory.bind(this)

        // all categories returned by VimeothequeTreeSelect
        this.categories = {}
    }

    // category ID needs to be cast to integer in order for lodash pull to work on it
    sanitizeCategory( category ){
        let result = parseInt( category )
        return isNaN( result ) ? false : result
    }

    // verify if category is selected for display in playlist
    isChecked( value ){
        let _value = 'undefined' != typeof value ? value : this.state.category,
            isIn = -1 != findIndex( this.state.selected, o => { return o == _value } )
        return isIn
    }

    // store all categories returned by VimeothequeTreeSelect
    setCategories( categories ){
        categories.forEach( element => {
            if( !has( this.categories, element.id ) ){
                this.categories[ element.id ] = element
            }
        })

        this.props.onCategoriesUpdate( this.categories )

    }

    getCategories( items ){
        let _items = []
        items.forEach( item => {
            _items.push( this.categories[ item ] )
        } )

        return _items
    }

    render(){
        return (
            <>
                <TextControl
                    label={ __('Search', 'cvm_video') }
                    placeholder={ __('Enter your search query', 'cvm_video') }
                    value={ this.state.query }
                    onChange={
                        value =>{
                            this.setState({
                                query: value,
                                submitted: false
                            })
                        }
                    }
                />

                <VimeothequeTreeSelect
                    label={ __( 'Category', 'cvm_video' ) }
                    noOptionLabel={ __('Choose category', 'cvm_video') }
                    taxonomy={this.props.taxonomy}
                    selectedId={this.state.category}
                    onChange={
                        value => {
                            this.setState({
                                category: this.sanitizeCategory( value ),
                                optionSelected: this.isChecked( value ),
                                submitted: false
                            })
                        }
                    }
                    onLoad={
                        categories => {
                            this.setCategories( categories )
                        }
                    }
                />

                {
                    ( this.state.category && this.state.submitted ) &&
                        <ToggleControl
                            label={ __( 'Select all videos from this category?', 'cvm_video' ) }
                            help={ __( 'All videos belonging to this category will be used in playlist' ) }
                            checked={ this.state.optionSelected }
                            onChange={
                                ()=>{
                                    let items
                                    if( this.isChecked() ){
                                        items = pull( this.state.selected, this.state.category )
                                        this.setState({
                                            selected: items,
                                            optionSelected: false
                                        })
                                    }else{
                                        items = concat( this.state.selected, this.state.category )
                                        this.setState({
                                            selected: items,
                                            optionSelected: true
                                        })
                                    }

                                    this.props.onCategorySelect( this.getCategories( items ) )
                                }
                            }
                        />
                }

                <div
                    className="vimeotheque-search-btn"
                >
                    <Button
                        isPrimary
                        disabled={this.props.blocked}
                        onClick={
                            ()=>{
                                this.setState({
                                    submitted: true
                                })

                                this.props.onSubmit({
                                    query: this.state.query,
                                    category: this.state.category
                                })
                            }
                        }
                    >
                        { __('Search', 'cvm_video') }
                    </Button>
                    {
                        this.props.blocked &&
                        <Spinner />
                    }
                </div>
            </>
        )
    }
}

SearchForm.defaultProps = {
    // when true will disable fields
    blocked: false,
    taxonomy: 'vimeo-videos',
    selectedCategories: [],
    values: { query: '', category: false },
    // triggers when user submits search form
    onSubmit: () => {},
    // triggers when user selectes category to include in playlist
    onCategorySelect: () => {},
    onCategoriesUpdate: ()=>{}
}

export default SearchForm