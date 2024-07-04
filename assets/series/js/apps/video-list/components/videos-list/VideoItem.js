import FeaturedImage from "./FeaturedImage";
import '../../store/itemsSelection'

import {includes} from 'lodash'

import {useSortable} from '@dnd-kit/sortable'
import {CSS} from '@dnd-kit/utilities'

const {
    components: {
        Button,
        Flex,
        FlexItem,
        Icon,
    },
    data: {
        dispatch,
        useSelect,
    },
    element: {
        useEffect,
        useState
    },
    i18n: {
        __
    },
    url: {
        addQueryArgs
    }
} = wp

const VideoItem = ({
    // Post item
    item = {
       id: '',
       title: {
           rendered: ''
       },
       featured_media: '',
       vimeo_video: {
           '_duration': ''
       }

    },
    /**
    * Item can be dragged and reordered.
    */
    draggable = false,
    /**
    * Item can be removed from a list.
    */
    removable = false,
    /**
    * Item can be selected into a list.
    */
    selectable = false,

    onRemove = () => {},
    onMouseDown = () => {},
    onMouseUp = () => {},
}) => {

    const {
        id,
        title: {
            rendered: postTitle
        },
        featured_media: featuredImage,
        vimeo_video: {
            _duration: videoDuration
        }
    } = item

    const [mouseOver, setMouseOver] = useState( false )

    const
        items = useSelect( select => select('vimeotheque-series/items-selection').getItems() ),
        selected = includes( items, item )

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
    } = useSortable({id: id});

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
    }

    return (
        <div
            className={`video-item vimeotheque-series-list-item ${selectable ? 'selectable' : ''} ${selected ? 'selected' : ''}`}
            onClick={
                () => {
                    if( selectable ) {
                        dispatch( 'vimeotheque-series/items-selection' ).manageItem( item )
                    }
                }
            }
            ref={setNodeRef}
            style={style}
        >
			<Flex>
                {
                    // Draggable Item
                    draggable &&
						<FlexItem>
                            <Button
                                className='action action-drag'
                                href='#'
                                icon='menu'
                                {...attributes}
                                {...listeners}
                            />
						</FlexItem>
                }

                {
                    // Selectable Item
                    selectable && selected &&
						<FlexItem>
                            <Button
                                className='action action-selected'
                                href='#'
                                icon='yes-alt'
                            />
						</FlexItem>
                }

                <FlexItem
                    className='column column-image'
                >
                    {/* The Thumbnail */}
                    <div
                        className={`video-thumbnail thumbnail ${mouseOver ? 'video-preloaded' : ''}`}
                        onMouseEnter={ () => { setMouseOver( true ) } }
                        onMouseOut={ () => { setMouseOver( false ) } }
                    >
                        {
                            !mouseOver && featuredImage != 0 &&
                                <FeaturedImage
                                    id={featuredImage}
                                />
                        }

                        {
                            !mouseOver && !featuredImage &&
                                <div
                                    className='image-preload'
                                >
                                    <Icon
                                        icon='camera'
                                        size='70'
                                    />

                                </div>
                        }

                        {
                        mouseOver &&
                            <>
                                <iframe
                                    src={addQueryArgs(item.vimeo_video.embed_url, {muted: 1, background: 1})}
                                    width="100%"
                                    height="100%"
                                    frameBorder="0"
                                    allow="autoplay"
                                    allowFullScreen={false}
                                ></iframe>
                            </>

                        }
                        <div
                            className='duration'
                        >
                            {videoDuration}
                        </div>
                    </div>

                </FlexItem>

                <FlexItem
                    className='column column-title'
                    isBlock={true}
                >
                    {
                        /* The Title */
                        postTitle
                    }
                </FlexItem>
                {
                    // Delete button
                    removable &&
						<FlexItem>
                            <Button
                                className='action action-remove'
                                href='#'
                                icon='no'
                                onClick={ onRemove }
                            />
						</FlexItem>
                }
            </Flex>
        </div>
    )
}

export default VideoItem