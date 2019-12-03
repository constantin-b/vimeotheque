/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./block.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./block.js":
/*!******************!*\
  !*** ./block.js ***!
  \******************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var registerBlockType = wp.blocks.registerBlockType,\n    __ = wp.i18n.__,\n    _wp$components = wp.components,\n    Panel = _wp$components.Panel,\n    PanelBody = _wp$components.PanelBody,\n    PanelRow = _wp$components.PanelRow,\n    ColorIndicator = _wp$components.ColorIndicator,\n    ColorPalette = _wp$components.ColorPalette,\n    Dropdown = _wp$components.Dropdown,\n    TextControl = _wp$components.TextControl,\n    SelectControl = _wp$components.SelectControl,\n    ToggleControl = _wp$components.ToggleControl,\n    InspectorControls = wp.blockEditor.InspectorControls,\n    useCallback = wp.element.useCallback,\n    withState = wp.compose.withState;\nregisterBlockType('vimeotheque/video-position', {\n  title: __('Vimeotheque video position', 'cvm_video'),\n  description: __('Video embed customization options', 'cvm_video'),\n  icon: 'video-alt3',\n  category: 'layout',\n  attributes: {\n    embed_options: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_playback_settings',\n      \"default\": false\n    },\n    video_id: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_video_id',\n      \"default\": false\n    }\n  },\n  example: {\n    attributes: {\n      video_id: '1084537',\n      embed_options: '{\"title\":1,\"byline\":1,\"portrait\":1,\"color\":\"\",\"loop\":0,\"autoplay\":1,\"aspect_ratio\":\"16x9\",\"width\":200,\"video_position\":\"below-content\",\"volume\":70,\"playlist_loop\":0,\"js_embed\":false}'\n    }\n  },\n  edit: function edit(props) {\n    var _props$attributes = props.attributes,\n        embed_options = _props$attributes.embed_options,\n        video_id = _props$attributes.video_id,\n        setAttributes = props.setAttributes,\n        className = props.className;\n    var opt = JSON.parse(embed_options);\n    var sep = ' : '; //console.log( opt );\n    //console.log( props );\n\n    var renderCustomColorPicker = function renderCustomColorPicker(value) {\n      React.createElement(ColorPicker, {\n        color: value,\n        onChangeComplete: function onChangeComplete(color) {\n          return onChange(color.hex);\n        },\n        disableAlpha: true\n      });\n    };\n\n    var onFormToggleChange = function onFormToggleChange(varName) {\n      opt[varName] = !opt[varName];\n      setAttributes({\n        embed_options: JSON.stringify(opt)\n      });\n    };\n\n    var embedStyle = {\n      width: opt.width + 'px',\n      maxWidth: '100%'\n    };\n    return [React.createElement(\"div\", {\n      className: \"cvm_single_video_player cvm_simple_embed\",\n      \"data-width\": opt.width,\n      \"data-aspect_ratio\": opt.aspect_ratio,\n      key: \"1\",\n      style: embedStyle,\n      onLoad: function onLoad(event) {\n        console.log('loaded');\n        cvm_resize_player(event.currentTarget);\n      }\n    }, React.createElement(\"iframe\", {\n      src: \"https://player.vimeo.com/video/\" + video_id + \"?title=\" + opt.title + '&byline=' + opt.byline + '&portrait=' + opt.portrait + '&loop=' + opt.loop + '&color=' + opt.color + '&autoplay=' + opt.autoplay + '&volume=' + opt.volume,\n      width: \"100%\",\n      height: \"100%\",\n      frameBorder: \"0\",\n      webkitallowfullscreen: \"true\",\n      mozallowfullscreen: \"true\",\n      allowFullScreen: true\n    })),\n    /*\r\n     * InspectorControls\r\n     */\n    React.createElement(InspectorControls, {\n      key: \"2\"\n    }, React.createElement(PanelBody, {\n      title: __('Embed options', 'cvm_video'),\n      initialOpen: true\n    }, React.createElement(PanelRow, null, React.createElement(ToggleControl, {\n      label: __('Show title', 'cvm_video'),\n      checked: opt.title,\n      onChange: function onChange() {\n        onFormToggleChange('title');\n      }\n    })), React.createElement(PanelRow, null, React.createElement(ToggleControl, {\n      label: __('Show byline', 'cvm_video'),\n      checked: opt.byline,\n      onChange: function onChange() {\n        onFormToggleChange('byline');\n      }\n    })), React.createElement(PanelRow, null, React.createElement(ToggleControl, {\n      label: __('Show portrait', 'cvm_video'),\n      checked: opt.portrait,\n      onChange: function onChange() {\n        onFormToggleChange('portrait');\n      }\n    })), React.createElement(PanelRow, null, React.createElement(ToggleControl, {\n      label: __('Loop video', 'cvm_video'),\n      checked: opt.loop,\n      onChange: function onChange() {\n        onFormToggleChange('loop');\n      }\n    })), React.createElement(PanelRow, null, React.createElement(ToggleControl, {\n      label: __('Autoplay video', 'cvm_video'),\n      help: __(\"This feature won't work on all browsers.\", 'cvm_video'),\n      checked: opt.autoplay,\n      onChange: function onChange() {\n        onFormToggleChange('autoplay');\n      }\n    })), React.createElement(PanelRow, null, React.createElement(TextControl, {\n      label: __('Volume', 'cvm_video'),\n      help: __('Will work only for JS embeds', 'cvm_video'),\n      type: \"number\",\n      step: \"1\",\n      value: opt.volume,\n      min: \"0\",\n      max: \"100\",\n      onChange: function onChange(value) {\n        opt.volume = value >= 0 && value <= 100 ? value : opt.volume;\n        setAttributes({\n          embed_options: JSON.stringify(opt)\n        });\n      }\n    }))), React.createElement(PanelBody, {\n      title: __('Embed size', 'cvm_video'),\n      initialOpen: false\n    }, React.createElement(PanelRow, null, React.createElement(TextControl, {\n      label: __('Width', 'cvm_video'),\n      type: \"number\",\n      step: \"5\",\n      value: opt.width,\n      min: \"200\",\n      onChange: function onChange(value) {\n        opt.width = !value || value < 200 ? 200 : value;\n        setAttributes({\n          embed_options: JSON.stringify(opt)\n        });\n        cvm_resize_players();\n      }\n    })), React.createElement(PanelRow, null, React.createElement(SelectControl, {\n      label: __('Aspect ratio', 'cvm_video'),\n      value: opt.aspect_ratio,\n      options: [{\n        label: '4x3',\n        value: '4x3'\n      }, {\n        label: '16x9',\n        value: '16x9'\n      }, {\n        label: '2.35x1',\n        value: '2.35x1'\n      }],\n      onChange: function onChange(value) {\n        opt.aspect_ratio = value;\n        setAttributes({\n          embed_options: JSON.stringify(opt)\n        });\n        setTimeout(cvm_resize_players, 500);\n      }\n    }))), React.createElement(PanelBody, {\n      title: __('Color options', 'cvm_video'),\n      initialOpen: false\n    }, React.createElement(PanelRow, null, React.createElement(\"label\", null, __('Player color', 'cvm_video') + sep, React.createElement(ColorIndicator, {\n      colorValue: '#' + opt.color\n    }), React.createElement(\"span\", null, opt.color ? ' #' + opt.color : ''))), React.createElement(PanelRow, null, React.createElement(ColorPalette, {\n      value: '#' + opt.color,\n      onChange: function onChange(color) {\n        opt.color = color.replace('#', '');\n        setAttributes({\n          embed_options: JSON.stringify(opt)\n        });\n      }\n    }))))];\n  },\n  save: function save(props) {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./block.js?");

/***/ })

/******/ });