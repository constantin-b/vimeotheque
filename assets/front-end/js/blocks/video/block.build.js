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

eval("var registerBlockType = wp.blocks.registerBlockType;\nvar __ = wp.i18n.__;\nregisterBlockType('vimeotheque/video-position', {\n  title: __('Vimeotheque video position', 'cvm_video'),\n  icon: 'video-alt3',\n  category: 'layout',\n  attributes: {\n    embed_options: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_playback_settings',\n      \"default\": false\n    },\n    video_id: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_video_id',\n      \"default\": false\n    },\n    cvm_css: {\n      type: 'string',\n      \"default\": false\n    }\n  },\n  edit: function edit(props) {\n    var _props$attributes = props.attributes,\n        embed_options = _props$attributes.embed_options,\n        video_id = _props$attributes.video_id,\n        setAttributes = props.setAttributes,\n        className = props.className;\n\n    if (!embed_options && !video_id) {\n      return '';\n    }\n\n    var _embed_options = JSON.parse(embed_options);\n\n    console.log(_embed_options);\n\n    var onChangeContent = function onChangeContent(newContent) {\n      setAttributes({\n        content: newContent\n      });\n    };\n\n    console.log(props);\n    var cls = \"cvm_single_video_player\";\n    var elem = React.createElement(\"div\", {\n      className: cls,\n      \"data-video_id\": video_id,\n      \"data-title\": _embed_options.title,\n      \"data-byline\": _embed_options.byline,\n      \"data-portrait\": _embed_options.portrait,\n      \"data-color\": _embed_options.color,\n      \"data-loop\": _embed_options.loop,\n      \"data-autoplay\": _embed_options.autoplay,\n      \"data-aspect_ratio\": _embed_options.aspect_ratio,\n      \"data-width\": _embed_options.width,\n      \"data-video_position\": _embed_options.video_position,\n      \"data-volume\": _embed_options.volume\n    }, \"'Some content here'\");\n    return elem;\n  },\n  save: function save(props) {\n    return '';\n  }\n});\n\n//# sourceURL=webpack:///./block.js?");

/***/ })

/******/ });