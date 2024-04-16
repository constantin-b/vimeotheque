import Theme from "./Theme";
import ButtonSave from "../../components/ButtonSave";

const {
    components: {
        Button,
        Flex,
        FlexItem,
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

const ThemesList = props => {

	const {
		items,
		isLoading
	} = useSelect(
		select => ({
			items: select('core').getEntityRecords('vimeotheque-series', 'themes'),
			isLoading: select('core/data').isResolving( 'core', 'getEntityRecords', ['vimeotheque-series', 'themes'] )
		})
	)

    return (
        <>
            <div
                className="themes-list"
            >
                {
                    isLoading &&
                    <>
                        <Spinner/> {__('Loading themes, please wait...', 'codeflavors-vimeo-video-post-lite')}
                    </>
                }

                {
                    items && items.map(
                        item => {
                            return (
                                <Theme
                                    key={item.folder}
                                    theme={item}
                                />
                            )
                        }
                    )
                }
            </div>
        </>


    )
}

ThemesList.defaultProps = {}

export default ThemesList