const {
    components: {
        Button
    },
	data:{
		useSelect,
	},
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Status = props => {

	const {
		status,
		isLoading,
	} = useSelect(
		select => ({
			status: select('core').getStatus( props.status ),
			isLoading: select('core/data').isResolving('core', 'getStatus', props.status)
		})
	)

    return (
        <>
			{
				isLoading &&
					<Spinner />
			}
			{
				status && status.name
			}
        </>
    )
}

Status.defaultProps = {
	status: 'publish'
}

export default Status