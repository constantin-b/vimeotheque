!function(e){var t={};function n(o){if(t[o])return t[o].exports;var i=t[o]={i:o,l:!1,exports:{}};return e[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(o,i,function(t){return e[t]}.bind(null,i));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=13)}({1:function(e,t){e.exports=jQuery},13:function(e,t,n){"use strict";n.r(t);var o,i=n(1),r=n.n(i);r.a.fn.VimeoPlayer=function(e){if(0==this.length)return!1;if(this.length>1)return this.each((function(e,t){r()(t).VimeoPlayer()}));var t,n=this,o=r.a.extend({},r.a.fn.VimeoPlayer.defaults,e),i=r()(this).data();this.isError=!1;try{t=new Vimeo.Player(r()(this).find("iframe"))}catch(e){n.isError=e}if(this.isError){try{console.log("%cCould not load Vimeotheque player for video "+i.video_id+" due to Vimeo.Player error.","color: #FF0000")}catch(e){}return r()(this).find("iframe").on("load",o.onIframeReload),n}return t.on("loaded",(function(){n.addClass("loaded"),o.onLoad})),t.on("play",o.onPlay),t.on("timeupdate",o.onPlayback),t.on("pause",o.onPause),t.on("ended",o.onFinish),t.on("error",o.onError),this.loadVideo=function(e){return t.loadVideo(e).then((function(e){})).catch((function(e){})),n},this.pause=function(){return t.pause().then((function(){})).catch((function(e){})),n},this.play=function(){return t.play().then((function(){})).catch((function(e){})),n},this.setVolume=function(e){if(!i.background)return t.setVolume(e).then((function(e){})).catch((function(e){})),n},this.getVolume=function(){return t.getVolume()},this.setPlaybackPosition=function(e){return t.setCurrentTime(e).then((function(e){})).catch((function(e){})),n},this.getPlayer=function(){return t},r()(this).data("ref",this),n},r.a.fn.VimeoPlayer.defaults={onIframeReload:function(){},onLoad:function(e){},onPlay:function(e){},onPlayback:function(e){},onPause:function(e){},onFinish:function(e){},onError:function(e){}},window.vimeotheque=window.vimeotheque||{},(o=vimeotheque).resizeAll=function(){r()("div.vimeotheque-player").each((function(e,t){vimeotheque.resize(t)}))},o.resize=function(e){var t,n=parseFloat(r()(e).attr("data-size_ratio")||0),o=r()(e).attr("data-aspect_ratio"),i=r()(e).width();if(n>0)t=Math.floor(i/n);else switch(o){case"16x9":default:t=Math.floor(9*i/16);break;case"4x3":t=Math.floor(3*i/4);break;case"2.35x1":t=Math.floor(i/2.35)}r()(e).css({height:t})},r()(document).ready(vimeotheque.resizeAll),r()(window).resize(vimeotheque.resizeAll),r.a.fn.VimeoPlaylist=function(e){if(0==this.length)return!1;if(this.length>1)return this.each((function(e,n){r()(n).VimeoPlaylist(t)}));var t=r.a.extend({},r.a.fn.VimeoPlaylist.defaults,e),n=this,o=r()(this).find(t.player).VimeoPlayer({onIframeReload:function(){n.VimeoPlaylist(t)},onFinish:function(){s()}});if(!o.isError){var i=r()(this).find(t.items),a=r()(o).data(),u=a.playlist_loop,l=a.volume,c=0,f=function(e,a){var l=arguments.length>2&&void 0!==arguments[2]&&arguments[2],f=r()(e).data(),s=f.autoplay,h=f.video_id,m=f.size_ratio,y=f.aspect_ratio;r()(i[c]).removeClass("active-video"),r()(e).addClass("active-video"),o.loadVideo(h).attr({"data-size_ratio":m,"data-aspect_ratio":y}),l&&o.setVolume(l),vimeotheque.resize(o),1!=s&&1!=u||d()||o.play(),c=a,t.loadVideo.call(n,e,a,o)},s=function(){if(1==u&&c<i.length-1){c++;var e=i[c];r()(e).trigger("click")}},d=function(){return/webOS|iPhone|iPad|iPod/i.test(navigator.userAgent)};return vimeotheque.resize(o),o.setVolume(l/100),r.a.each(i,(function(e,t){0==e&&f(t,e),r()(t).on("click",(function(n){n.preventDefault(),o.getVolume().then((function(n){f(t,e,n)}))}))})),n}},r.a.fn.VimeoPlaylist.defaults={player:".vimeotheque-player",items:".cvm-playlist-item a[data-video_id]",loadVideo:function(){}},window.vimeotheque=window.vimeotheque||{},r()(document).ready((function(){vimeotheque.players=r()(".vimeotheque-player").VimeoPlayer()}))}});