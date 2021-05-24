!function(e){var t={};function o(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,o),i.l=!0,i.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)o.d(n,i,function(t){return e[t]}.bind(null,i));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=13)}({1:function(e,t){e.exports=jQuery},13:function(e,t,o){"use strict";o.r(t);var n,i=o(1),a=o.n(i);a.a.fn.VimeoPlayer=function(e){if(0==this.length)return!1;if(this.length>1)return this.each((function(e,t){a()(t).VimeoPlayer()}));var t,o=this,n=a.a.extend({},a.a.fn.VimeoPlayer.defaults,e),i=a()(this).data(),r=!1;this.isError=!1;try{t=new Vimeo.Player(a()(this).find("iframe"))}catch(e){o.isError=e}if(this.isError){try{console.log("%cCould not load Vimeotheque player for video "+i.video_id+" due to Vimeo.Player error.","color: #FF0000")}catch(e){}return a()(this).find("iframe").on("load",n.onIframeReload),o}return t.on("loaded",(function(){o.addClass("loaded"),n.onLoad})),t.on("play",(function(e){r||(o.setVolume(parseInt(i.volume)/100),r=!0),n.onPlay(e,o)})),t.on("timeupdate",(function(e){n.onPlayback(e,o)})),t.on("pause",(function(e){n.onPause(e,o)})),t.on("ended",(function(e){n.onFinish(e,o)})),t.on("error",(function(e){n.onError(e,o)})),t.on("volumechange",(function(e){r=!0})),this.loadVideo=function(e){return t.loadVideo(e).then((function(e){})).catch((function(e){})),o},this.pause=function(){return t.pause().then((function(){})).catch((function(e){})),o},this.play=function(){return t.play().then((function(){})).catch((function(e){})),o},this.setVolume=function(e){if(!i.background&&!i.muted)return t.setVolume(e).then((function(e){})).catch((function(e){})),o},this.getVolume=function(){return t.getVolume()},this.setPlaybackPosition=function(e){return t.setCurrentTime(e).then((function(e){})).catch((function(e){})),o},this.getPlayer=function(){return t},a()(this).data("ref",this),o},a.a.fn.VimeoPlayer.defaults={onIframeReload:function(){},onLoad:function(e){},onPlay:function(e){},onPlayback:function(e){},onPause:function(e){},onFinish:function(e){},onError:function(e){}},window.vimeotheque=window.vimeotheque||{},(n=vimeotheque).resizeAll=function(){a()("div.vimeotheque-player").each((function(e,t){vimeotheque.resize(t)}))},n.resize=function(e){var t,o=parseFloat(a()(e).attr("data-size_ratio")||0),n=a()(e).attr("data-aspect_ratio"),i=a()(e).width();if(o>0)t=Math.floor(i/o);else switch(n){case"16x9":default:t=Math.floor(9*i/16);break;case"4x3":t=Math.floor(3*i/4);break;case"2.35x1":t=Math.floor(i/2.35)}a()(e).css({height:t})},a()(document).ready(vimeotheque.resizeAll),a()(window).resize(vimeotheque.resizeAll),a.a.fn.VimeoPlaylist=function(e){if(0==this.length)return!1;if(this.length>1)return this.each((function(e,o){a()(o).VimeoPlaylist(t)}));var t=a.a.extend({},a.a.fn.VimeoPlaylist.defaults,e),o=this,n=a()(this).find(t.player).VimeoPlayer({onIframeReload:function(){o.VimeoPlaylist(t)},onFinish:function(){s()}});if(!n.isError){var i=a()(this).find(t.items),r=a()(n).data(),u=r.playlist_loop,l=r.volume,c=0,f=function(e,r){var l=arguments.length>2&&void 0!==arguments[2]&&arguments[2],f=a()(e).data(),s=f.autoplay,h=f.video_id,m=f.size_ratio,y=f.aspect_ratio;a()(i[c]).removeClass("active-video"),a()(e).addClass("active-video"),n.loadVideo(h).attr({"data-size_ratio":m,"data-aspect_ratio":y}),l&&n.setVolume(l),vimeotheque.resize(n),1!=s&&1!=u||d()||n.play(),c=r,t.loadVideo.call(o,e,r,n)},s=function(){1==u&&c<i.length-1&&a()(i[c+1]).trigger("click")},d=function(){return/webOS|iPhone|iPad|iPod/i.test(navigator.userAgent)};return vimeotheque.resize(n),n.setVolume(l/100),a.a.each(i,(function(e,t){0==e&&f(t,e),a()(t).on("click",(function(o){o.preventDefault(),n.getVolume().then((function(o){f(t,e,o)}))}))})),o}},a.a.fn.VimeoPlaylist.defaults={player:".vimeotheque-player",items:".cvm-playlist-item a[data-video_id]",loadVideo:function(){}},window.vimeotheque=window.vimeotheque||{},a()(document).ready((function(){var e={};a()(".vimeotheque-player.lazy-load .vimeotheque-load-video").on("click",(function(t){t.preventDefault(),a()(this).closest(".vimeotheque-player.lazy-load").html(a()("<iframe />",{src:a()(this).data("url"),width:"100%",height:"100%",frameborder:0})).removeClass("lazy-load").VimeoPlayer(e)})),vimeotheque.players=a()(".vimeotheque-player:not(.lazy-load)").VimeoPlayer(e)}))}});