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
            <Spinner /> {__('Loading block, please wait...', 'vimeotheque-series')}
        </Placeholder>
    )
}

Loader.defaultProps = {
    onComplete: () => {}
}

export default Loader