import {preloadImage} from "../../../inc/imagePreloader";

const {
    components: {
        Spinner,
    },
	data: {
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

const FeaturedImage = props => {

	const {
		image,
		isLoading
	} = useSelect(
		select => ({
			image: select('core').getEntityRecord( 'postType', 'attachment', props.id ),
			isLoading: select('core/data').isResolving( 'core', 'getEntityRecord', ['postType', 'attachment', props.id] )
		})
	)

    const [preloaded, setPreloaded] = useState( false )

    const loadImage = async () => {
        const loaded = await preloadImage( image.media_details.sizes['medium'].source_url )
        setPreloaded(loaded)
    }

    useEffect(
        () => {
            if( image ){
                loadImage()
            }

        }, [image]
    )

    return (
        <>
            {
                ( isLoading || !preloaded ) &&
                    <div
                        className='image-preload'
                    >
                        <Spinner />
                    </div>
            }

			{
				image && preloaded &&
					<img
						src={ preloaded.src }
					/>
			}
        </>
    )
}

FeaturedImage.defaultProps = {
	id: ''
}

export default FeaturedImage