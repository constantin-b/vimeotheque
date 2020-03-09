import VimeothequeTreeSelect from "./VimeothequeTreeSelect"
import { findIndex, pull, has } from 'lodash'

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
            category: props.values.category,
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

        // all categories returned by VimeothequeTreeSelect
        this.categories = {}
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
    }

    getCategories(){
        let items = []
        this.state.selected.forEach( item => {
            items.push( this.categories[ item ] )
        } )

        return items
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
                                category: value,
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
                                    let items = this.state.selected
                                    if( this.isChecked() ){
                                        this.setState({
                                            selected: pull( items, this.state.category ),
                                            optionSelected: false
                                        })
                                    }else{
                                        items.push( this.state.category )
                                        this.setState({
                                            selected: items,
                                            optionSelected: true
                                        })
                                    }

                                    this.props.onCategorySelect( this.getCategories() )
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
    onCategorySelect: () => {}
}

export default SearchForm