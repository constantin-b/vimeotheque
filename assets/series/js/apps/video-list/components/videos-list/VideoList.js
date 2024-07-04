import VideoItem from "./VideoItem"
import VideoLoad from "./VideoLoad"
import {findIndex} from 'lodash'

const {
    components: {
        Button,
        Spinner,
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
    }
} = wp

import {
    DndContext,
    closestCenter,
    KeyboardSensor,
    PointerSensor,
    useSensor,
    useSensors,
} from '@dnd-kit/core'

import {
    arrayMove,
    SortableContext,
    sortableKeyboardCoordinates,
    verticalListSortingStrategy,
} from '@dnd-kit/sortable'


const VideoList = ({
   item = false
}) => {

    const items = useSelect( select => select('vimeotheque-series/items-store').getItems() )

    const sensors = useSensors(
        useSensor(PointerSensor),
        useSensor(KeyboardSensor, {
            coordinateGetter: sortableKeyboardCoordinates,
        })
    );

    const list = items.map(
        (item, i) => {
            return (
                <VideoItem
                    key={item.id}
                    id={item.id}
                    draggable={true}
                    selectable={false}
                    removable={true}
                    onRemove={
                        () => {
                            dispatch('vimeotheque-series/items-store').removeItem( item )
                        }
                    }
                    item={item}
                />
            )
        }
    )

    const handleDragEnd = (event) => {
        const {
            active,
            over
        } = event

        if (active.id !== over.id) {
            const
                oldIndex = findIndex( items, item => active.id == item.id ),
                newIndex = findIndex( items, item => over.id == item.id )

            dispatch( 'vimeotheque-series/items-store').updateOption( arrayMove(items, oldIndex, newIndex) )
        }
    }

    return (
        <div
            className="video-list vimeotheque-series-items-list"
        >
            {
                item.items && item.items.length > 0 &&
                    <VideoLoad
                        item={item}
                    />
            }

            {
                items.length == 0 &&
                <>
                    {__('Choose some videos to display into the playlist.', 'codeflavors-vimeo-video-post-lite')}
                </>

            }

            <DndContext
                sensors={sensors}
                collisionDetection={closestCenter}
                onDragEnd={handleDragEnd}
            >
                <SortableContext
                    items={items}
                    strategy={verticalListSortingStrategy}
                >
                    { list }
                </SortableContext>
            </DndContext>
        </div>
    )
}

export default VideoList