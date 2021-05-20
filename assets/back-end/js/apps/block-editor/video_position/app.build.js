!function(e){var t={};function o(a){if(t[a])return t[a].exports;var l=t[a]={i:a,l:!1,exports:{}};return e[a].call(l.exports,l,l.exports,o),l.l=!0,l.exports}o.m=e,o.c=t,o.d=function(e,t,a){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(o.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var l in e)o.d(a,l,function(t){return e[t]}.bind(null,l));return a},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=9)}({9:function(e,t){function o(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,a)}return o}function a(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?o(Object(a),!0).forEach((function(t){l(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):o(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function l(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}function n(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var o=[],a=!0,l=!1,n=void 0;try{for(var r,i=e[Symbol.iterator]();!(a=(r=i.next()).done)&&(o.push(r.value),!t||o.length!==t);a=!0);}catch(e){l=!0,n=e}finally{try{a||null==i.return||i.return()}finally{if(l)throw n}}return o}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return r(e,t);var o=Object.prototype.toString.call(e).slice(8,-1);"Object"===o&&e.constructor&&(o=e.constructor.name);if("Map"===o||"Set"===o)return Array.from(e);if("Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o))return r(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function r(e,t){(null==t||t>e.length)&&(t=e.length);for(var o=0,a=new Array(t);o<t;o++)a[o]=e[o];return a}var i=wp,c=i.blockEditor.InspectorControls,u=i.blocks.registerBlockType,d=i.components,s=(d.Panel,d.PanelBody),p=d.PanelRow,m=d.ColorIndicator,v=d.ColorPalette,f=(d.Dropdown,d.TextControl),b=d.SelectControl,h=d.ToggleControl,y=d.RangeControl,g=(i.compose.withState,i.element),E=(g.useCallback,g.useEffect),_=g.useState,R=i.hooks,w=R.applyFilters,k=(R.doAction,i.i18n),O=k.__,j=k.sprintf;u("vimeotheque/video-position",{title:O("Vimeotheque video position","codeflavors-vimeo-video-post-lite"),description:O("Video embed customization options","codeflavors-vimeo-video-post-lite"),icon:"video-alt3",category:"layout",attributes:{embed_options:{type:"object",source:"meta",meta:"__cvm_playback_settings",default:!1},video_id:{type:"string",source:"meta",meta:"__cvm_video_id",default:!1},extra:{type:"object",default:{}}},supports:{align:!1,anchor:!1,html:!1,multiple:!1,reusable:!1,customClassName:!1},example:{attributes:{video_id:"1084537",embed_options:{title:1,byline:1,portrait:1,color:"",loop:0,autoplay:1,aspect_ratio:"16x9",width:200,video_position:"below-content",volume:70,playlist_loop:0}}},edit:function(e){var t,o,l=e.attributes,r=l.embed_options,i=l.extra,u=l.video_id,d=e.setAttributes,g=(e.className,n(_(r),2)),R=g[0],k=g[1],C=n(_(""),2),S=C[0],x=C[1],P="replace-featured-image"===r.video_position?"above-content":r.video_position,A=function(e){q(e,1==R[e]?0:1)},q=function(e,t){var o={};o[e]=t,k(a(a({},R),o))},T=function(){var e=Math.floor(R.start_time/3600),t=Math.floor(R.start_time/60),o=R.start_time%60;return"".concat(e,"h").concat(t,"m").concat(o,"s")};return E((function(){d({embed_options:a(a({},R),i)})}),[R,i]),[React.createElement("div",{key:"vimeotheque-video-position-block"},React.createElement("div",{className:"vimeotheque-player ".concat(R.video_align," ").concat(S),"data-width":R.width,"data-aspect_ratio":R.aspect_ratio,style:{width:"".concat(R.width,"px"),maxWidth:"100%"},onLoad:function(e){vimeotheque.resize(e.currentTarget),x("loaded")}},React.createElement("iframe",{src:(t="https://player.vimeo.com/video",o={dnt:R.dnt,start_time:R.start_time,transparent:R.transparent},R.background?o.background=R.background:(o.title=R.title,o.byline=R.byline,o.portrait=R.portrait,o.loop=R.loop,o.color=R.color.replace("#",""),o.autoplay=R.autoplay,R.muted||(o.volume=R.volume),o.muted=R.muted),w("vimeotheque.video-position.embed-url","".concat(t,"/").concat(u,"?").concat(jQuery.param(o),"#t=").concat(T()),t,u,o,R.start_time,T())),width:"100%",height:"100%",frameBorder:"0",webkitallowfullscreen:"true",mozallowfullscreen:"true",allowFullScreen:!0})),!e.isSelected&&React.createElement("div",{style:{position:"absolute",top:0,left:0,width:"100%",height:"100%"}})),React.createElement(c,{key:"vimeotheque-video-position-controls"},React.createElement(s,{title:O("Player options","codeflavors-vimeo-video-post-lite"),initialOpen:!0},React.createElement(p,null,React.createElement(h,{label:O("Background mode","codeflavors-vimeo-video-post-lite"),help:!R.background&&O("Whether the player is in background mode, which hides the playback controls, enables autoplay, and loops the video.","codeflavors-vimeo-video-post-lite"),checked:R.background,onChange:function(){return A("background")}})),!R.background&&React.createElement(React.Fragment,null,React.createElement(p,null,React.createElement(h,{label:O("Show title","codeflavors-vimeo-video-post-lite"),checked:R.title,onChange:function(){return A("title")}})),React.createElement(p,null,React.createElement(h,{label:O("Show byline","codeflavors-vimeo-video-post-lite"),checked:R.byline,onChange:function(){return A("byline")}})),React.createElement(p,null,React.createElement(h,{label:O("Show portrait","codeflavors-vimeo-video-post-lite"),checked:R.portrait,onChange:function(){return A("portrait")}})),React.createElement(p,null,React.createElement(h,{label:O("Loop video","codeflavors-vimeo-video-post-lite"),checked:R.loop,onChange:function(){return A("loop")}})),React.createElement(p,null,React.createElement(h,{label:O("Autoplay video","codeflavors-vimeo-video-post-lite"),help:O("This feature might not work on all devices.","codeflavors-vimeo-video-post-lite"),checked:R.autoplay,onChange:function(){return A("autoplay")}})),React.createElement(p,null,React.createElement(h,{label:O("Load muted","codeflavors-vimeo-video-post-lite"),help:!R.muted&&O("Will load the video muted which is required for the autoplay behavior in some browsers.","codeflavors-vimeo-video-post-lite"),checked:R.muted,onChange:function(){return A("muted")}}))),React.createElement(p,null,React.createElement(y,{label:O("Start time","codeflavors-vimeo-video-post-lite"),help:j(O("Video playback initial start time in seconds. Must not exceed %s seconds.","codeflavors-vimeo-video-post-lite"),i.duration),initialPosition:R.start_time,value:R.start_time,isShiftStepEnabled:!0,marks:!1,min:"0",max:i.duration,step:"1",withInputField:!0,onChange:function(e){var t=parseInt(e),o=parseInt(i.duration),a=t>=0&&t<=o?e:R.start_time;q("start_time",a)}})),React.createElement(p,null,React.createElement(h,{label:O("Transparent background","codeflavors-vimeo-video-post-lite"),help:!R.transparent&&O("Video will be embedded without a background.","codeflavors-vimeo-video-post-lite"),checked:R.transparent,onChange:function(){return A("transparent")}})),!R.background&&!R.muted&&React.createElement(p,null,React.createElement(y,{label:O("Volume","codeflavors-vimeo-video-post-lite"),help:O("Will be applied in front-end after the user initializes playback.","codeflavors-vimeo-video-post-lite"),step:"1",initialPosition:R.volume,min:"0",max:"100",isShiftStepEnabled:!0,marks:[{value:0,label:"0"},{value:25,label:"25"},{value:50,label:"50"},{value:75,label:"75"},{value:100,label:"100"}],withInputField:!0,onChange:function(e){var t=e>=0&&e<=100?e:R.volume;q("volume",t)}}))),React.createElement(s,{title:O("Embedding options","codeflavors-vimeo-video-post-lite"),initialOpen:!1},React.createElement(p,null,React.createElement(h,{label:O("Replace featured image","codeflavors-vimeo-video-post-lite"),help:"replace-featured-image"!==R.video_position&&O("Video embed will replace the featured image.","codeflavors-vimeo-video-post-lite"),checked:"replace-featured-image"==R.video_position,onChange:function(){q("video_position","replace-featured-image"==r.video_position?P:"replace-featured-image")}})),React.createElement(p,null,React.createElement(f,{label:O("Width","codeflavors-vimeo-video-post-lite"),type:"number",step:"5",value:R.width,min:"200",onChange:function(e){q("width",!e||e<200?200:e),vimeotheque.resizeAll()}})),React.createElement(p,null,React.createElement(b,{label:O("Aspect ratio","codeflavors-vimeo-video-post-lite"),value:R.aspect_ratio,options:[{label:"4x3",value:"4x3"},{label:"16x9",value:"16x9"},{label:"2.35x1",value:"2.35x1"}],onChange:function(e){q("aspect_ratio",e),setTimeout(vimeotheque.resizeAll,100)}})),React.createElement(p,null,React.createElement(b,{label:O("Align","codeflavors-vimeo-video-post-lite"),value:R.video_align,options:[{label:"left",value:"align-left"},{label:"center",value:"align-center"},{label:"right",value:"align-right"}],onChange:function(e){q("video_align",e)}}))),!R.background&&React.createElement(s,{title:O("Color options","codeflavors-vimeo-video-post-lite"),initialOpen:!1},React.createElement(p,null,React.createElement("label",null,"".concat(O("Player color","codeflavors-vimeo-video-post-lite")," : "),React.createElement(m,{colorValue:"#".concat(R.color.replace("#",""))}),React.createElement("span",null,R.color&&"#".concat(R.color.replace("#",""))))),React.createElement(p,null,React.createElement(v,{value:"#".concat(R.color.replace("#","")),onChange:function(e){var t=e.replace("#","");q("color",t)}}))))]},save:function(e){return null}})}});