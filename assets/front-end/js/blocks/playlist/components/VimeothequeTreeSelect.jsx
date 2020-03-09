import { unescape as unescapeString, repeat, flatMap, compact } from 'lodash'
const { apiFetch } = wp,
      {
          SelectControl,
          Spinner,
          BaseControl
      } = wp.components,
      { __ } = wp.i18n

class VimeothequeTreeSelect extends React.Component{
    constructor(props) {
        super( props )

        this.state = {
            loading: false,
            tree: [],
            selectedId: props.selectedId
        }

        this.getSelectOptions = this.getSelectOptions.bind(this);
        this.buildTree = this.buildTree.bind(this);
    }

    makeRequest(){
        this.setState({
            loading: true
        })

        apiFetch({
            path: '/wp/v2/' +
                this.props.taxonomy +
                '?per_page=100&orderby=name&order=asc&_fields=id%2Cname%2Cparent&_locale=user',
            parse: false
        }).then( response => {

            return response.json()
        } ).then( categories => {
            this.setState({
                loading:false,
                tree: this.buildTree( categories )
            })

            this.props.onLoad( categories )

        } ).catch( error => {

        } )

    }

    componentDidMount(){
        this.makeRequest()
    }

    componentDidUpdate( prevProps ){
        if( this.props.taxonomy != prevProps.taxonomy ){
            this.setState({
                loading:false,
                tree: []
            })
            this.makeRequest()
        }
    }

    // build a tree of categories for TreeSelect
    buildTree( elements, parentId ){
        let branch = []

        if( typeof parentId == 'undefined' ){
            parentId = 0
        }

        elements.forEach( ( element ) => {
            if( element.parent == parentId ){
                let _element = {
                    id: element.id,
                    name: element.name
                }

                let children = this.buildTree( elements, element.id )

                if( children ){
                    _element.children = children
                }

                branch.push( _element )
            }
        } )

        return branch
    }

    getSelectOptions( tree, level = 0 ) {
        return flatMap( tree, ( treeNode ) => [
            {
                value: treeNode.id,
                label:
                    repeat( '\u00A0', level * 3 ) + unescapeString( treeNode.name ),
            },
            ...this.getSelectOptions( treeNode.children || [], level + 1 ),
        ] );
    }

    render(){
        let options = []

        if( this.state.loading ){
            options = [
                { value: '', label: __('Loading categories, please wait...', 'cvm_video') }
            ]
        }else {
            options = compact([
                this.props.noOptionLabel && {value: '', label: this.props.noOptionLabel},
                ...this.getSelectOptions(this.state.tree),
            ])
        }

        return (
            <SelectControl
                disabled = { this.state.loading }
                label={this.props.label}
                options={options}
                value={this.state.selectedId}
                onChange={
                    value => {
                        this.setState({
                            selectedId: value
                        })

                        this.props.onChange( value )
                    }
                }
            />
        );
    }
}

VimeothequeTreeSelect.defaultProps = {
    taxonomy: 'vimeo-videos',

    label: false,
    noOptionLabel: false,
    onChange: ()=>{},
    // triggers after ajax request finished and sends categories
    onLoad: ()=>{},
    selectedId: false,
}

export default VimeothequeTreeSelect
