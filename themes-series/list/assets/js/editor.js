(()=>{"use strict";var e=wp,t=e.components,n=t.Button,i=t.ButtonGroup,o=e.data,s=o.useSelect,r=o.dispatch,a=e.element,u=(a.useEffect,a.useState,e.i18n.__),c=function(e){var t=s((function(e){return e("vimeotheque-series/playlist-options").getOption("columns")})),o=function(e){r("vimeotheque-series/playlist-options").updateOption("columns",e),r("core").editEntityRecord("postType","series",VSE.postId,{columns:e})};return React.createElement(React.Fragment,null,React.createElement("label",null,u("Columns","vimeotheque-series")," : "),React.createElement(i,null,React.createElement(n,{variant:1==t?"primary":"",onClick:function(){return o(1)}},"1"),React.createElement(n,{variant:2==t?"primary":"",onClick:function(){return o(2)}},"2"),React.createElement(n,{variant:3==t?"primary":"",onClick:function(){return o(3)}},"3"),React.createElement(n,{variant:4==t?"primary":"",onClick:function(){return o(4)}},"4"),React.createElement(n,{variant:5==t?"primary":"",onClick:function(){return o(5)}},"5")))};c.defaultProps={};const l=c;var m=wp,p=(m.components.Button,m.data),v=p.dispatch,h=(p.useSelect,m.hooks),d=h.addAction,f=h.addFilter;m.i18n.__,f("vimeotheque-series-theme-settings","vimeotheque-series-theme-list-settings",(function(e,t){return"list"==t&&(e=React.createElement(l,null)),e})),f("vimeotheque-series-playlist-options-defaults","vimeotheque-series-theme-list-options-defaults",(function(e){return e.columns=3,e})),d("vimeotheque-series-items-init","vimeotheque-theme-list-items-init",(function(e){v("vimeotheque-series/playlist-options").updateOption("columns",e.columns)}))})();