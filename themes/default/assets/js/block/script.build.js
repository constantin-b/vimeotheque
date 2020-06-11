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
/******/ 	return __webpack_require__(__webpack_require__.s = "./script.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./script.js":
/*!*******************!*\
  !*** ./script.js ***!
  \*******************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);\n\nvar addFilter = wp.hooks.addFilter,\n    __ = wp.i18n.__,\n    createHigherOrderComponent = wp.compose.createHigherOrderComponent,\n    InspectorControls = wp.editor.InspectorControls,\n    _wp$components = wp.components,\n    PanelBody = _wp$components.PanelBody,\n    SelectControl = _wp$components.SelectControl;\nvar enableOnBlocks = ['vimeotheque/video-playlist'];\nvar layoutOptions = [{\n  label: __('Default', 'codeflavors-vimeo-video-post-lite'),\n  value: ''\n}, {\n  label: __('Right side', 'codeflavors-vimeo-video-post-lite'),\n  value: 'right'\n}, {\n  label: __('Left side', 'codeflavors-vimeo-video-post-lite'),\n  value: 'left'\n}];\n\nvar addLayoutAttribute = function addLayoutAttribute(settings, name) {\n  // Do nothing if it's another block than our defined ones.\n  if (!enableOnBlocks.includes(name)) {\n    return settings;\n  }\n\n  settings.attributes = Object(lodash__WEBPACK_IMPORTED_MODULE_0__[\"assign\"])(settings.attributes, {\n    layout: {\n      type: 'string',\n      \"default\": layoutOptions[0].value\n    }\n  });\n  return settings;\n};\n/**\r\n * @param string - hook name\r\n * @param string - namespace\r\n * @param string - callback\r\n */\n\n\naddFilter('blocks.registerBlockType', 'playlist-theme-default/attribute/layout', addLayoutAttribute);\n/**\r\n * Create HOC to add spacing control to inspector controls of block.\r\n */\n\nvar withLayoutControl = createHigherOrderComponent(function (BlockEdit) {\n  return function (props) {\n    // Do nothing if it's another block than our defined ones.\n    if (!enableOnBlocks.includes(props.name)) {\n      return /*#__PURE__*/React.createElement(BlockEdit, props);\n    }\n\n    var layout = props.attributes.layout;\n    return /*#__PURE__*/React.createElement(React.Fragment, null, 'default' == props.attributes.theme && /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {\n      title: __('Layout', 'codeflavors-vimeo-video-post-lite'),\n      initialOpen: true\n    }, /*#__PURE__*/React.createElement(SelectControl, {\n      label: __('Navigation position', 'codeflavors-vimeo-video-post-lite'),\n      value: layout,\n      options: layoutOptions,\n      onChange: function onChange(value) {\n        props.setAttributes({\n          layout: value\n        });\n      }\n    }))), /*#__PURE__*/React.createElement(BlockEdit, props));\n  };\n}, 'withLayoutControl');\naddFilter('editor.BlockEdit', 'playlist-theme-default/with-layout-control', withLayoutControl);\n\n//# sourceURL=webpack:///./script.js?");

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = lodash;\n\n//# sourceURL=webpack:///external_%22lodash%22?");

/***/ })

/******/ });