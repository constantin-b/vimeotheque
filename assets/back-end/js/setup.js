!function(e){var t={};function o(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,o),i.l=!0,i.exports}o.m=e,o.c=t,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)o.d(n,i,function(t){return e[t]}.bind(null,i));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="",o(o.s=16)}({0:function(e,t){e.exports=jQuery},16:function(e,t,o){"use strict";o.r(t);var n=o(0),i=o.n(n),r=function(e,t){void 0===window.vimeotheque&&(window.vimeotheque={}),"string"!=typeof e&&void 0!==console.error&&console.error("Parameter must be a string"),window.vimeotheque[e]=t},a=function(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1];return void 0!==window.vimeotheque[e]?window.vimeotheque[e]:(void 0===console.error||t||console.error("Param ".concat(e," does not exist.")),null)},s=function(e){window.vimeotheque.navigation.list.push(e)},c=function(e){i.a.each(window.vimeotheque.navigation.list,(function(t,o){o(e)}))},u=vimeotheque,l=u.restURL,v=u.restNonce,d=function(){var e=i()(".wrap.vimeotheque-setup .container .submit-button"),t=i()(".wrap.vimeotheque-setup .container .back"),o=e.data();s((function(t){t==a("steps")?e.val(o.save):e.val(o.value)})),s((function(e){1==e?t.removeClass("active"):t.addClass("active")})),t.on("click",(function(e){e.preventDefault();var t=a("step");t>1&&(c(t-1),r("step",t-1))})),e.on("click",(function(t){var n=a("step"),s=a("steps");if(c(n+1),n<s)r("step",n+1);else{e.addClass("loading").val(o.loading).attr("disabled","disabled");var u=i()("#setup-form").serializeArray();i.a.each(i()("#setup-form input[type=checkbox]:not(:checked)"),(function(e,t){u.push({name:i()(t).attr("name"),value:0})})),i.a.ajax({type:"POST",url:"".concat(l,"vimeotheque/v1/plugin/settings"),beforeSend:function(e){e.setRequestHeader("X-WP-Nonce",v)},data:u,success:function(t){e.val(o.save).removeClass("loading").removeAttr("disabled");var n=t.success,a=t.message;i()("#step-4 .content");n?(c(999),r("step",999),i()("#step-success").show(),i()(".controls").hide()):i()("#vimeo-oauth-response").show().html("<p>".concat(a,"</p>"))}})}}))};window.vimeotheque=window.vimeotheque||{},window.vimeotheque.navigation={list:[]};i()(document).ready((function(){r("step",1),r("steps",4);var e=i()(".wrap.vimeotheque-setup .navigator .step a"),t=i()(".wrap.vimeotheque-setup .container .step");s((function(e){var t=i()(".wrap.vimeotheque-setup .navigator .step *[data-step='".concat(e,"']"));t.hasClass("active")||t.trigger("click")})),s((function(e){e<=a("steps")&&i()(".controls").show(),999==e&&i()(".wrap.vimeotheque-setup .container .step").hide()})),e.on("click",(function(o){o.preventDefault();var n=i()(i()(o.currentTarget).attr("href")),a=i()(o.currentTarget).data("step");t.hide(),n.show(),e.removeClass("active"),i()(o.currentTarget).addClass("active"),r("step",parseInt(a)),c(a)})),i()("#lazy_load").on("click",(function(e){i()(e.currentTarget).is(":checked")?i()("#play-icon-color-row").show(300):i()("#play-icon-color-row").hide(300)})),i()('[name="enable_templates"]').on("click",(function(e){0==i()(e.currentTarget).val()?i()("#video-position-row, #video-align-row").show(300):i()("#video-position-row, #video-align-row").hide(300)})),0==i()('[name="enable_templates"]:checked').val()?i()("#video-position-row, #video-align-row").show(300):i()("#video-position-row, #video-align-row").hide(300),i()(".toggler").on("click",(function(e){e.preventDefault();var t=i()(e.currentTarget),o=t.data(),n=o.toggle,r=o.show_text,a=o.hide_text;i()(n).toggle(0,(function(){console.log(i()(n).is(":visible")),i()(t).html(i()(n).is(":visible")?a:r)}))})),i()('[data-colorPicker="true"]').wpColorPicker({change:function(){},clear:function(){}}),i()("#skip-setup").on("click",(function(e){return confirm(i()(e.currentTarget).data("message"))})),d()}))}});