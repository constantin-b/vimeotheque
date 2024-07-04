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

const Loader = ({
    onComplete = () => {}
}) => {

    useEffect(
        () => {
            // unmount component
            return () => onComplete()
        }
    )

    return (
        <Placeholder>
            <Spinner /> {__('Loading block, please wait...', 'codeflavors-vimeo-video-post-lite')}
        </Placeholder>
    )
}

export default Loader