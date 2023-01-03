import $ from 'jQuery'
import Process
    from "./include/process";
import {
    addStepListener,
    getParam,
    setParam,
    triggerStepListeners
} from "./include/functions";

window.vimeotheque = window.vimeotheque || {}
window.vimeotheque.navigation = {
    list: []
}

const init = () => {

    // Set the first step on first page load
    setParam( 'step', 1 )
    setParam( 'steps', 4 )

    const
        navs = $('.wrap.vimeotheque-setup .navigator .step a'),
        containers = $('.wrap.vimeotheque-setup .container .step')

    /**
     * Hide step
     */
    addStepListener(
        step => {
            const el = $(`.wrap.vimeotheque-setup .navigator .step *[data-step='${step}']`)
            if( !el.hasClass('active') ) {
                el.trigger('click')
            }
        }
    )

    /**
     * Show the controls
     */
    addStepListener(
        step => {
            if( step <= getParam('steps') ){
                $('.controls').show()
            }

            if( step == 999 ){
                $('.wrap.vimeotheque-setup .container .step').hide()
            }
        }
    )

    navs.on('click', e => {
        e.preventDefault()
        const item = $($(e.currentTarget).attr('href')),
            step = $(e.currentTarget).data('step')

        containers.hide()
        item.show()
        navs.removeClass('active')
        $(e.currentTarget).addClass('active')
        setParam( 'step', parseInt( step ) )

        // trigger listeners
        triggerStepListeners( step )
    })

    $('#lazy_load').on(
        'click',
        e => {
            if( $(e.currentTarget).is(':checked') ){
                $('#play-icon-color-row').show(300)
            }else{
                $('#play-icon-color-row').hide(300)
            }
        }
    )

    $('[name="enable_templates"]').on(
        'click',
        e => {
            const val = $(e.currentTarget).val()

            if( 0 == val ){
                $('#video-position-row, #video-align-row').show(300)
            }else{
                $('#video-position-row, #video-align-row').hide(300)
            }
        }
    )

    if( 0 == $('[name="enable_templates"]:checked').val() ){
       $('#video-position-row, #video-align-row').show(300)
    }else{
        $('#video-position-row, #video-align-row').hide(300)
    }

    $('.toggler').on(
        'click',
        e => {
            e.preventDefault()

            const
                item = $(e.currentTarget),
                {
                    toggle,
                    show_text,
                    hide_text
                } = item.data()

            $( toggle )
                .toggle(
                    0,
                    function(){

                        console.log($(toggle).is(':visible'))

                        $(item).html( $(toggle).is(':visible') ? hide_text : show_text )
                    }
                )
        }
    )

    $('[data-colorPicker="true"]')
        .wpColorPicker(
            {
                change: () => {
                },
                clear: () => {
                }
            }
        )

    $('#skip-setup').on(
        'click',
        e => confirm( $(e.currentTarget).data('message') )
    )

    Process()

}

$(document).ready(init)