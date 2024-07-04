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

const Status = ({
    status = 'publish'
}) => {

	const {
		currentStatus,
		isLoading,
	} = useSelect(
		select => ({
            currentStatus: select('core').getStatus( status ),
			isLoading: select('core/data').isResolving('core', 'getStatus', status)
		})
	)

    return (
        <>
			{
				isLoading &&
					<Spinner />
			}
			{
                currentStatus && currentStatus.name
			}
        </>
    )
}

export default Status