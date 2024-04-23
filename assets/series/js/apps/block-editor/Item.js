import Status from "../components/Status";

const {
    components: {
        Button,
		Flex,
		FlexItem,
		Icon,
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    }
} = wp

const Item = props => {

	const {
		id,
		status,
		link,
		title: {
			raw: rawTitle,
			rendered: renderedTitle,
		},
		content_type: contentType,
		items,
		theme,
		theme_details: {
			name: themeName
		},
		edit_link: editLink

	} = props.item

    return (
		<Flex
			justify='space-between'
			className='vimeotheque-item line'
        >
			<FlexItem>
				{id}
			</FlexItem>
			<FlexItem
				isBlock={true}
			>
				<Button
					isLink={true}
					onClick={ props.onClick }
				>
					{renderedTitle || __('(no title)', 'codeflavors-vimeo-video-post-lite')}
				</Button>
			</FlexItem>
			<FlexItem
				isBlock={true}
			>
				<Status
					status={status}
				/>
			</FlexItem>
			<FlexItem
				isBlock={true}
			>
				{contentType}
				{
					'posts' == contentType &&
						` (${items.length})`
				}
			</FlexItem>
			<FlexItem
				isBlock={true}
			>
				{themeName || '-'}
			</FlexItem>
			<FlexItem
				isBlock={true}
			>
				<Button
					href={link}
					icon='visibility'
					target='_blank'
				/>
				{
					editLink &&
						<Button
							href={editLink}
							icon='edit'
							target='_blank'
						/>
				}
			</FlexItem>
        </Flex>
    )
}

Item.defaultProps = {
	item: {},
	onClick: () => {}
}

export default Item