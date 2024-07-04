import ServerSideRender from "./ServerSideRender";

const /*ServerSideRender = wp.serverSideRender || wp.components.ServerSideRender,*/
    {
        useState,
        useEffect
    } = wp.element,
    { Placeholder, Spinner } = wp.components,
    { __ } = wp.i18n

const ServerSideEmbed = ({
    block = '',
    attributes = {},
    onComplete = () => {},
    isSelected = false,
}) => {

    return (
        <>
            <ServerSideRender
                block={block}
                attributes={attributes}
                LoadingResponsePlaceholder={
                    () => {
                        return (
                            <Placeholder>
                                {__('Loading, please wait...', 'codeflavors-vimeo-video-post-lite')}
                                <Spinner/>
                            </Placeholder>
                        )
                    }
                }
            />
            {
                !isSelected &&
                (
                    <div
                        style={
                            {
                                position:'absolute',
                                top:0,
                                left:0,
                                width:'100%',
                                height:'100%'
                            }
                        }
                    />
                )
            }
        </>
    )
}

export default ServerSideEmbed