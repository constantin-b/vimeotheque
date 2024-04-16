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

const ButtonSave = props => {

    const hasEdits = useSelect( select => select('core').hasEditsForEntityRecord('postType', 'series', getEditedPostId() ) )

    const save = async () => {

        props.onClick()

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
        if( props.enabled || hasEdits ){
            disabled = false
        }

        return disabled
    }

    const buttonText = () => {
        if( hasEdits ){

            if( isSaving ){
                return (
                    <>
                        <Spinner /> {__('Saving...', 'vimeotheque-series')}
                    </>
                )
            }

            return props.text
        }else{
            return props.text
        }
    }

    return (
        <>
            {
                <Button
                    disabled={ isDisabled() }
                    isPrimary={props.isPrimary}
                    isSecondary={props.isSecondary}
                    onClick={save}
                >
                    {buttonText()}
                </Button>
            }
        </>
    )
}

ButtonSave.defaultProps = {
    enabled: false,
    text: __('Save', 'vimeotheque-series'),
    isPrimary: true,
    isSecondary: false,
    onClick: () => {},
}

export default ButtonSave