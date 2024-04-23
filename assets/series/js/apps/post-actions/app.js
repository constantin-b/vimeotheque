import ButtonSave from "../components/ButtonSave"
import {getEditedPostId} from "../inc/functions";

const {
    components: {
        Button,
        Flex,
        FlexItem,
    },
    data: {
        dispatch,
        useSelect,
    },
    element: {
        createRoot,
        useEffect,
        useState
    },
    hooks: {
        doAction,
    },
    i18n: {
        __
    }
} = wp

const PostActionsApp = props => {

    const {
        post,
        isLoading
    } = useSelect(
        select => ({
            post: select( 'core' ).getEntityRecord( 'postType', 'series', getEditedPostId() ),
            isLoading: select('core/data').isResolving( 'core', 'getEntityRecord', ['postType', 'series', getEditedPostId()] )
        })
    )

    return (
        <div
            className="vimeotheque-series-post-actions"
        >
            <Flex
                justify='flex-end'
            >
                {
                    post &&
                        <>
                            <FlexItem
                                isBlock={true}
                            >
                                <ButtonSave
                                    enabled={ 'draft' != post.status }
                                    text={ 'draft' == post.status ? __('Save draft', 'codeflavors-vimeo-video-post-lite') : __( 'Switch to draft', 'codeflavors-vimeo-video-post-lite' ) }
                                    isPrimary={false}
                                    isSecondary={true}
                                    onClick={
                                        () => {
                                            dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'status': 'draft' })
                                        }
                                    }
                                />
                            </FlexItem>

                            <FlexItem>
                                <ButtonSave
                                    enabled={ 'draft' == post.status }
                                    isPrimary={true}
                                    isSecondary={false}
                                    text={ 'draft' == post.status ? __('Publish', 'codeflavors-vimeo-video-post-lite') : __('Update', 'codeflavors-vimeo-video-post-lite') }
                                    onClick={
                                        () => {
                                            dispatch('core').editEntityRecord( 'postType', 'series', getEditedPostId(), { 'status': 'publish' })
                                        }
                                    }
                                />
                            </FlexItem>
                        </>
                }
            </Flex>

            {
                post && post.status !== 'auto-draft' && post.preview_link !== '' &&
                    <Flex
                        justify='flex-end'
                        className='second-line'
                    >
                        <FlexItem>
                            <Button
                                target='_blank'
                                href={post.preview_link}
                                isLink={true}
                            >
                                {__('View', 'codeflavors-vimeo-video-post-lite')}
                            </Button>
                        </FlexItem>
                    </Flex>
            }

        </div>
    )
}

const root = createRoot( document.getElementById( 'vimeotheque-series-post-actions' ) )
root.render( <PostActionsApp /> )