import { optionsTree } from "../helpers/optionsTree";
import { termQueryApplyWithSelect } from '../helpers/termQueryApplyWithSelect'
import { buildTree } from '../helpers/buildTree'
import {
    unescape as unescapeString,
    repeat,
    flatMap,
    compact
} from 'lodash'

const {
          SelectControl,
          Spinner,
          BaseControl
      } = wp.components,
      { __ } = wp.i18n

class TreeSelectBase extends React.Component{
    constructor(props) {
        super( props )

        this.state = {
            selectedId: props.selectedId
        }
    }

    shouldComponentUpdate( nextProps, nextState ){
        // finished loading
        if( this.props.loading && !nextProps.loading ){
            this.props.onLoad( nextProps.terms )
        }

        return true
    }

    render(){
        let options = []

        if( this.props.loading ){
            options = [
                { value: '', label: __('Loading categories, please wait...', 'cvm_video') }
            ]
        }else {
            options = compact([
                this.props.noOptionLabel && {value: '', label: this.props.noOptionLabel},
                ...optionsTree( buildTree( this.props.terms ) ),
            ])
        }

        return (
            <SelectControl
                disabled = { this.props.loading }
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

TreeSelectBase.defaultProps = {
    label: false,
    noOptionLabel: false,
    onChange: ()=>{},
    // triggers after ajax request finished and sends categories
    onLoad: ()=>{},
    selectedId: false,
}

const TreeSelect = termQueryApplyWithSelect( TreeSelectBase )

TreeSelect.defaultProps = {
    taxonomy: 'vimeo-videos'
}

export default TreeSelect
