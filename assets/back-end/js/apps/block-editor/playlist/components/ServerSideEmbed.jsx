import ServerSideRender from "./ServerSideRender";

const /*ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender,*/
    {
        useState,
        useEffect
    } = wp.element,
    { Placeholder, Spinner } = wp.components,
    { __ } = wp.i18n

const ServerSideEmbed = props => {

    const [loading, setLoading] = useState( true )

    return (
        <>
            <ServerSideRender
                {...props}
                LoadingResponsePlaceholder={
                    () => {
                        return (
                            <Loader
                                onComplete={
                                    () => {
                                        props.onComplete()
                                        setLoading( false )
                                    }
                                }
                            />
                        )
                    }
                }
            />
            {
                !props.isSelected &&
                (
                    <div
                        style={
                            {
                                position:'absolute',
                                top:0,
                                left:0,
                                width:'100%',
                                height:'100%'
                            }
                        }
                    />
                )
            }
        </>
    )
}

const Loader = props => {
    useEffect( () => {
        // unmount component, loading over
        return () => {
            props.onComplete()
        }
    })

    return (
        <Placeholder>
            {__('Loading, please wait...', 'codeflavors-vimeo-video-post-lite')}
            <Spinner/>
        </Placeholder>
    )
}

ServerSideEmbed.defaultProps = {
    onComplete: false,
    isSelected: false
}

export default ServerSideEmbed