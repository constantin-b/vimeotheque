import $ from 'jquery'
import 'flexslider'

$(document).ready(
    () => {
        $('.vimeotheque-series.playlist.carousel.flexslider').flexslider({
            animation: "slide",
            animationLoop: false,
            slideshow: false,
            itemWidth: 300,
            itemMargin: 15,
            video: true,
        });
    }
)