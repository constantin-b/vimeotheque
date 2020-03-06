import VimeothequeTreeSelect from "./VimeothequeTreeSelect";

const { __ } = wp.i18n,
    {
        Spinner,
        TextControl,
        Button
    } = wp.components

class SearchForm extends React.Component{
    constructor( props ) {
        super( props )
        this.state = {
            query: props.values.query,
            category: props.values.category
        }
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
                                query: value
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
                                category: value
                            })
                        }
                    }
                />

                <div
                    className="vimeotheque-search-btn"
                >
                    <Button
                        isPrimary
                        disabled={this.props.blocked}
                        onClick={
                            ()=>{
                                this.props.onSubmit( this.state )
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
    values: { query: '', category: false },
    onSubmit: () => {}
}

export default SearchForm