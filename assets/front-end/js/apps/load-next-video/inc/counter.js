import $ from 'jQuery'

$.fn.VimeothequeCounter = function( params ){

    const
        defaults = {
            seconds: 5,
            textBefore: '',
            textAfter: ''
        },
        self = this,
        options = $.extend( {}, defaults, params ),
        wrap = $('<div />',{
            class: 'vimeotheque-timer'
        })

    let secondsLeft = options.seconds,
        interval = null

    const init = () => {

        const
            timer = $('<span />', {
                class: 'timer',
                text: ` ${secondsLeft}`
            })

        self.append(
            wrap.append( options.textBefore )
                .append( timer )
                .append( options.textAfter )
        )

        interval = setInterval( () => {
            secondsLeft--

            if( secondsLeft > 0 ) {
                timer.text(secondsLeft)
            }else{
                clearInterval( interval )
                /**
                 * Fire a timer out event.
                 */
                self.trigger(
                    'timerExpired'
                )
            }


        }, 1000)

        return self
    }

    this.cancelTimer = () => {
        clearInterval( interval )
    }

    return init()
}