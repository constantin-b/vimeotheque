import $ from 'jquery'
import 'flexslider'

/** @namespace vimeotheque */
window.vimeotheque = window.vimeotheque || {};
vimeotheque.series = vimeotheque.series || {};

vimeotheque.series.themeCarousel = () => {
    $('.vimeotheque-series.playlist.carousel.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        slideshow: false,
        itemWidth: 300,
        itemMargin: 15,
        video: true,
    });
}

$(document).ready( () => vimeotheque.series.themeCarousel() )