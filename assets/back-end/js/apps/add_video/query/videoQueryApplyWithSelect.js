const {withSelect} = wp.data,
    { apiFetch } = wp,
    {useState, useEffect} = wp.element

export const videoQueryApplyWithSelect = ( Component, props ) => {

    return ( props ) => {

        const [state, setState] = useState( {
            loading: true,
            response: false,
            error: false
        } )

        useEffect( () => {
            if( !state.loading ){
                setState({
                    loading: true,
                    response: false,
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
                        response: result,
                        error: false
                    })
                }
            ).catch(
                error => {
                    setState({
                        loading: false,
                        response: false,
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

export const postCreateApplyWithSelect = ( Component, props ) => {

    return ( props ) => {

        const [state, setState] = useState( {
            loading: true,
            response: false,
            error: false
        } )

        useEffect( () => {
            if( !state.loading ){
                setState({
                    loading: true,
                    response: false,
                    error: false
                })
            }
        }, [props.video] )

        if( state.loading ) {
            apiFetch({
                path: '/vimeotheque/v1/wp/post-create/',
                method: 'POST',
                data: {
                    video: props.video,
                    post_id: vmtqVideoSettings.postId,
                    ...props.params
                }
            }).then(
                result => {
                    setState({
                        loading: false,
                        response: result,
                        error: false
                    })
                }
            ).catch(
                error => {
                    setState({
                        loading: false,
                        response: false,
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