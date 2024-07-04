import {getEditedPostId} from "../inc/functions";

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

const ButtonSave = ( {
    enabled = false,
    text = __('Save', 'codeflavors-vimeo-video-post-lite'),
    isPrimary = true,
    isSecondary = false,
    onClick = () => {}
} ) => {

    const hasEdits = useSelect( select => select('core').hasEditsForEntityRecord('postType', 'series', getEditedPostId() ) )

    const save = async () => {

        onClick()

        await dispatch('core').saveEditedEntityRecord( 'postType', 'series', getEditedPostId() )
    }

    const {lastError, isSaving} = useSelect(
        select => ({
            lastError:  select('core').getLastEntitySaveError( 'postType', 'series', getEditedPostId() ),
            isSaving:   select('core').isSavingEntityRecord( 'postType', 'series', getEditedPostId() )
        })
    )

    const isDisabled = () => {
        let disabled = true
        if( enabled || hasEdits ){
            disabled = false
        }

        return disabled
    }

    const buttonText = () => {
        if( hasEdits ){

            if( isSaving ){
                return (
                    <>
                        <Spinner /> {__('Saving...', 'codeflavors-vimeo-video-post-lite')}
                    </>
                )
            }

            return text
        }else{
            return text
        }
    }

    return (
        <>
            {
                <Button
                    disabled={ isDisabled() }
                    isPrimary={isPrimary}
                    isSecondary={isSecondary}
                    onClick={save}
                >
                    {buttonText()}
                </Button>
            }
        </>
    )
}

export default ButtonSave