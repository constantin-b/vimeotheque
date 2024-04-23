const {
    components: {
        Placeholder,
        Spinner,
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Loader = props => {

    useEffect(
        () => {
            // unmount component
            return () => props.onComplete()
        }
    )

    return (
        <Placeholder>
            <Spinner /> {__('Loading block, please wait...', 'codeflavors-vimeo-video-post-lite')}
        </Placeholder>
    )
}

Loader.defaultProps = {
    onComplete: () => {}
}

export default Loader