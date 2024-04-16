const {
    components: {
        Button,
		Flex,
		FlexItem,
		Modal,
    },
	data: {
		useSelect
	},
    element: {
        useEffect,
        useState
    },
	hooks: {
		applyFilters
	},
    i18n: {
        __
    }
} = wp

const Settings = props => {

	const
		theme = useSelect( select => select('vimeotheque-series/playlist-options').getOption('theme') ),
		[open, setOpen] = useState( false )

	const themeSettings = applyFilters('vimeotheque-series-theme-settings', '', theme)

    return (
        <div
            className="vimeotheque-theme settings"
        >
			<Flex
				justify='flex-end'
			>
				<FlexItem>
					<Button
						icon='admin-settings'
						onClick={ () => setOpen(!open) }
					>
						{__('Settings', 'vimeotheque-series')}
					</Button>
				</FlexItem>
			</Flex>

			{
				open &&
					<Modal
						size='medium'
						isDismissible={true}
						onRequestClose={ () => setOpen( !open ) }
						shouldCloseOnClickOutside={true}
						shouldCloseOnEsc={true}
						title={__('Theme Settings', 'vimeotheque-series')}
					>
						{themeSettings}
					</Modal>
			}

        </div>
    )
}

Settings.defaultProps = {}

export default Settings