import '../rest/routes'

const {withSelect} = wp.data,
    { apiFetch } = wp,
    {useState, useEffect} = wp.element

export const videoQueryApplyWithSelect = ( Component, props ) => {

    return ( props ) => {

        const [state, setState] = useState( {
            loading: true,
            video: false,
            error: false
        } )

        useEffect( () => {
            if( !state.loading ){
                setState({
                    loading: true,
                    video: false,
                    error: false
                })
            }
        }, [props.query] )

        if( state.loading ) {
            apiFetch({
                path: `/vimeotheque/v1/api-query/video/?id=${props.query}`,
                method: 'GET'
            }).then(
                result => {
                    setState({
                        loading: false,
                        video: result,
                        error: false
                    })
                }
            ).catch(
                error => {
                    setState({
                        loading: false,
                        video: false,
                        error: error
                    })
                }
            )
        }

        return (
            <Component
                {...state}
                {...props}
            />
        )
    }
}