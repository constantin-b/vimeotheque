/**
 * External dependencies
 */
import { isEqual, debounce } from 'lodash';

/**
 * WordPress dependencies
 */
const { Component, RawHTML } = wp.element,
    { __, sprintf } = wp.i18n,
    { apiFetch } = wp,
    {addQueryArgs} = wp.url,
    { Placeholder, Spinner } = wp.components

export function rendererPath( block, attributes = null, urlQueryArgs = {} ) {
    return addQueryArgs( `/wp/v2/block-renderer/${ block }`, {
        context: 'edit',
        ...( null !== attributes ? { attributes } : {} ),
        ...urlQueryArgs,
    } );
}

export class ServerSideRender extends Component {
    constructor( props ) {
        super( props );
        this.state = {
            response: null,
        };
    }

    componentDidMount() {
        this.isStillMounted = true;
        this.fetch( this.props );
        // Only debounce once the initial fetch occurs to ensure that the first
        // renders show data as soon as possible.
        this.fetch = debounce( this.fetch, 500 );
    }

    componentWillUnmount() {
        this.isStillMounted = false;
    }

    componentDidUpdate( prevProps ) {
        if ( ! isEqual( prevProps.attributes, this.props.attributes ) ) {
            this.fetch( this.props );
        }
    }

    fetch( props ) {
        if ( ! this.isStillMounted ) {
            return;
        }
        if ( null !== this.state.response ) {
            this.setState( { response: null } );
        }
        const { block, attributes = null, urlQueryArgs = {} } = props;

        const path = rendererPath( block, attributes, urlQueryArgs );
        // Store the latest fetch request so that when we process it, we can
        // check if it is the current request, to avoid race conditions on slow networks.
        const fetchRequest = ( this.currentFetchRequest = apiFetch( { path } )
            .then( ( response ) => {
                if (
                    this.isStillMounted &&
                    fetchRequest === this.currentFetchRequest &&
                    response
                ) {
                    this.setState( { response: response.rendered } );
                }
            } )
            .catch( ( error ) => {
                if (
                    this.isStillMounted &&
                    fetchRequest === this.currentFetchRequest
                ) {
                    this.setState( {
                        response: {
                            error: true,
                            errorMsg: error.message,
                        },
                    } );
                }
            } ) );
        return fetchRequest;
    }

    render() {
        const response = this.state.response;
        const {
            className,
            EmptyResponsePlaceholder,
            ErrorResponsePlaceholder,
            LoadingResponsePlaceholder,
        } = this.props;

        if ( response === '' ) {
            return (
                <EmptyResponsePlaceholder
                    response={ response }
                    { ...this.props }
                />
            );
        } else if ( ! response ) {
            return (
                <LoadingResponsePlaceholder
                    response={ response }
                    { ...this.props }
                />
            );
        } else if ( response.error ) {
            return (
                <ErrorResponsePlaceholder
                    response={ response }
                    { ...this.props }
                />
            );
        }

        return (
            <RawHTML key="html" className={ className }>
                { response }
            </RawHTML>
        );
    }
}

ServerSideRender.defaultProps = {
    EmptyResponsePlaceholder: ( { className } ) => (
        <Placeholder className={ className }>
            { __( 'Block rendered as empty.' ) }
        </Placeholder>
    ),
    ErrorResponsePlaceholder: ( { response, className } ) => {
        const errorMessage = sprintf(
            // translators: %s: error message describing the problem
            __( 'Error loading block: %s' ),
            response.errorMsg
        );
        return (
            <Placeholder className={ className }>{ errorMessage }</Placeholder>
        );
    },
    LoadingResponsePlaceholder: ( { className } ) => {
        return (
            <Placeholder className={ className }>
                <Spinner />
            </Placeholder>
        );
    },
};

export default ServerSideRender;