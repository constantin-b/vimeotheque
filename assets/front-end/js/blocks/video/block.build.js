!function(e){var t={};function o(l){if(t[l])return t[l].exports;var n=t[l]={i:l,l:!1,exports:{}};return e[l].call(n.exports,n,n.exports,o),n.l=!0,n.exports}o.m=e,o.c=t,o.d=function(e,t,l){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:l})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var l=Object.create(null);if(o.r(l),Object.defineProperty(l,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)o.d(l,n,function(t){return e[t]}.bind(null,n));return l},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=0)}([function(e,t){var o=wp.blocks.registerBlockType,l=wp.i18n.__,n=wp.components,a=(n.Panel,n.PanelBody),i=n.PanelRow,r=n.ColorIndicator,c=n.ColorPalette,u=(n.Dropdown,n.TextControl),m=n.SelectControl,p=n.ToggleControl,d=wp.blockEditor.InspectorControls;wp.element.useCallback,wp.compose.withState;o("vimeotheque/video-position",{title:l("Vimeotheque video position","cvm_video"),description:l("Video embed customization options","cvm_video"),icon:"video-alt3",category:"layout",attributes:{embed_options:{type:"string",source:"meta",meta:"__cvm_playback_settings",default:!1},video_id:{type:"string",source:"meta",meta:"__cvm_video_id",default:!1}},example:{attributes:{video_id:"1084537",embed_options:'{"title":1,"byline":1,"portrait":1,"color":"","loop":0,"autoplay":1,"aspect_ratio":"16x9","width":200,"video_position":"below-content","volume":70,"playlist_loop":0,"js_embed":false}'}},edit:function(e){var t=e.attributes,o=t.embed_options,n=t.video_id,s=e.setAttributes,v=(e.className,JSON.parse(o)),_=function(e){v[e]=!v[e],s({embed_options:JSON.stringify(v)})},b={width:v.width+"px",maxWidth:"100%"};return[React.createElement("div",{className:"cvm_single_video_player cvm_simple_embed","data-width":v.width,"data-aspect_ratio":v.aspect_ratio,key:"1",style:b,onLoad:function(e){cvm_resize_player(e.currentTarget)}},React.createElement("iframe",{src:"https://player.vimeo.com/video/"+n+"?title="+v.title+"&byline="+v.byline+"&portrait="+v.portrait+"&loop="+v.loop+"&color="+v.color+"&autoplay="+v.autoplay+"&volume="+v.volume,width:"100%",height:"100%",frameBorder:"0",webkitallowfullscreen:"true",mozallowfullscreen:"true",allowFullScreen:!0})),React.createElement(d,{key:"2"},React.createElement(a,{title:l("Embed options","cvm_video"),initialOpen:!0},React.createElement(i,null,React.createElement(p,{label:l("Show title","cvm_video"),checked:v.title,onChange:function(){_("title")}})),React.createElement(i,null,React.createElement(p,{label:l("Show byline","cvm_video"),checked:v.byline,onChange:function(){_("byline")}})),React.createElement(i,null,React.createElement(p,{label:l("Show portrait","cvm_video"),checked:v.portrait,onChange:function(){_("portrait")}})),React.createElement(i,null,React.createElement(p,{label:l("Loop video","cvm_video"),checked:v.loop,onChange:function(){_("loop")}})),React.createElement(i,null,React.createElement(p,{label:l("Autoplay video","cvm_video"),help:l("This feature won't work on all browsers.","cvm_video"),checked:v.autoplay,onChange:function(){_("autoplay")}})),React.createElement(i,null,React.createElement(u,{label:l("Volume","cvm_video"),help:l("Will work only for JS embeds","cvm_video"),type:"number",step:"1",value:v.volume,min:"0",max:"100",onChange:function(e){v.volume=e>=0&&e<=100?e:v.volume,s({embed_options:JSON.stringify(v)})}}))),React.createElement(a,{title:l("Embed size","cvm_video"),initialOpen:!1},React.createElement(i,null,React.createElement(u,{label:l("Width","cvm_video"),type:"number",step:"5",value:v.width,min:"200",onChange:function(e){v.width=!e||e<200?200:e,s({embed_options:JSON.stringify(v)}),cvm_resize_players()}})),React.createElement(i,null,React.createElement(m,{label:l("Aspect ratio","cvm_video"),value:v.aspect_ratio,options:[{label:"4x3",value:"4x3"},{label:"16x9",value:"16x9"},{label:"2.35x1",value:"2.35x1"}],onChange:function(e){v.aspect_ratio=e,s({embed_options:JSON.stringify(v)}),setTimeout(cvm_resize_players,500)}}))),React.createElement(a,{title:l("Color options","cvm_video"),initialOpen:!1},React.createElement(i,null,React.createElement("label",null,l("Player color","cvm_video")+" : ",React.createElement(r,{colorValue:"#"+v.color}),React.createElement("span",null,v.color?" #"+v.color:""))),React.createElement(i,null,React.createElement(c,{value:"#"+v.color,onChange:function(e){v.color=e.replace("#",""),s({embed_options:JSON.stringify(v)})}}))))]},save:function(e){return null}})}]);