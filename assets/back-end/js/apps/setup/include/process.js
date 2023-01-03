import $ from 'jQuery'
import {
    addStepListener,
    getParam,
    setParam,
    triggerStepListeners
} from "./functions";

const {
    restURL,
    restNonce
} = vimeotheque

const Process = () => {

    const
        submitButton = $( '.wrap.vimeotheque-setup .container .submit-button' ),
        backButton = $('.wrap.vimeotheque-setup .container .back'),
        buttonData = submitButton.data(),
        disableButton = () => {
            submitButton
                .addClass('loading')
                .val(buttonData.loading)
                .attr('disabled', 'disabled')
        },
        enableButton = () => {
            submitButton
                .val(buttonData.save)
                .removeClass('loading')
                .removeAttr('disabled')
        }

    addStepListener(
        step => {
            if( step == getParam('steps') ){
                submitButton.val( buttonData.save )
            }else{
                submitButton.val( buttonData.value )
            }
        }
    )

    addStepListener(
        step => {
            if( 1 == step ){
                backButton.removeClass('active')
            }else{
                backButton.addClass('active')
            }
        }
    )

    backButton.on(
        'click',
        e => {
            e.preventDefault()
            const step = getParam('step')
            if( step > 1 ){
                triggerStepListeners( step - 1 )
                setParam('step', step - 1 )
            }
        }
    )

    submitButton.on(
        'click',
        e => {

            // get the current step
            const step = getParam('step'),
                steps = getParam('steps')

            // trigger listeners
            triggerStepListeners( step + 1 )

            if( step < steps ){
                setParam('step', step + 1 )
                return
            }

            disableButton()

            const formData = $('#setup-form').serializeArray()

            $.each(
                $('#setup-form input[type=checkbox]:not(:checked)'),
                (i, el) => {
                    formData.push(
                        {
                            name: $(el).attr('name'),
                            value: 0
                        }
                    )
                }
            )

            $.ajax({
                type: 'POST',
                url: `${restURL}vimeotheque/v1/plugin/settings`,
                beforeSend: xhr => {
                    xhr.setRequestHeader( 'X-WP-Nonce', restNonce )
                },
                data: formData,
                success: response => {

                    enableButton()

                    const
                        {
                            success,
                            message
                        } = response,
                        elem = $('#step-4 .content')

                    if( !success ){
                        $('#vimeo-oauth-response')
                            .show()
                            .html( `<p>${message}</p>` )
                    }else{
                        triggerStepListeners( 999 )
                        setParam('step', 999 )

                        $('#step-success').show()
                        $('.controls').hide()

                    }
                }
            })
        }
    )
}

export default Process