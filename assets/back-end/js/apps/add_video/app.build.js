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
/******/ 	return __webpack_require__(__webpack_require__.s = "./add_video/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./add_video/app.js":
/*!**************************!*\
  !*** ./add_video/app.js ***!
  \**************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _components_SearchForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/SearchForm */ \"./add_video/components/SearchForm.jsx\");\n/* harmony import */ var _components_VideoQuery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/VideoQuery */ \"./add_video/components/VideoQuery.jsx\");\n/* harmony import */ var _components_VideoImporter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/VideoImporter */ \"./add_video/components/VideoImporter.jsx\");\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(n); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\n\n\n\nvar _wp$element = wp.element,\n    render = _wp$element.render,\n    useState = _wp$element.useState,\n    _wp$components = wp.components,\n    Placeholder = _wp$components.Placeholder,\n    Spinner = _wp$components.Spinner;\n\nvar VideoImportApp = function VideoImportApp(props) {\n  var _useState = useState(''),\n      _useState2 = _slicedToArray(_useState, 2),\n      query = _useState2[0],\n      setQuery = _useState2[1];\n\n  var _useState3 = useState(false),\n      _useState4 = _slicedToArray(_useState3, 2),\n      video = _useState4[0],\n      setVideo = _useState4[1];\n\n  return /*#__PURE__*/React.createElement(\"div\", {\n    className: \"vimeotheque-video-import-app\"\n  }, video && /*#__PURE__*/React.createElement(_components_VideoImporter__WEBPACK_IMPORTED_MODULE_2__[\"default\"], {\n    video: video,\n    onMessageClose: function onMessageClose() {\n      setVideo(false);\n      setQuery('');\n    }\n  }), !video && query && /*#__PURE__*/React.createElement(_components_VideoQuery__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n    query: query,\n    onSubmit: function onSubmit(video) {\n      setVideo(video);\n    },\n    onCancel: function onCancel() {\n      setQuery('');\n    }\n  }), !video && !query && /*#__PURE__*/React.createElement(_components_SearchForm__WEBPACK_IMPORTED_MODULE_0__[\"default\"], {\n    onSubmit: function onSubmit(query) {\n      setQuery(query);\n    }\n  }));\n};\n\nrender( /*#__PURE__*/React.createElement(VideoImportApp, null), document.getElementById('vimeotheque-import-video'));\n\n//# sourceURL=webpack:///./add_video/app.js?");

/***/ }),

/***/ "./add_video/components/SearchForm.jsx":
/*!*********************************************!*\
  !*** ./add_video/components/SearchForm.jsx ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(n); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\nvar useState = wp.element.useState,\n    __ = wp.i18n.__,\n    _wp$components = wp.components,\n    TextControl = _wp$components.TextControl,\n    Button = _wp$components.Button,\n    Spinner = _wp$components.Spinner;\n\nvar SearchForm = function SearchForm(props) {\n  var _useState = useState(''),\n      _useState2 = _slicedToArray(_useState, 2),\n      query = _useState2[0],\n      setQuery = _useState2[1];\n\n  return /*#__PURE__*/React.createElement(\"div\", {\n    className: \"vimeotheque-search-form\"\n  }, /*#__PURE__*/React.createElement(TextControl, {\n    label: \"\".concat(__('Insert Vimeo video URL or video ID', 'cvm_video'), \" : \"),\n    help: __('', 'cvm_video'),\n    value: query,\n    onChange: function onChange(value) {\n      setQuery(value);\n    }\n  }), /*#__PURE__*/React.createElement(Button, {\n    isPrimary: true,\n    onClick: function onClick() {\n      props.onSubmit(query);\n    }\n  }, __('Search', 'cvm_video')));\n};\n\nSearchForm.defaultProps = {\n  onSubmit: function onSubmit() {}\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (SearchForm);\n\n//# sourceURL=webpack:///./add_video/components/SearchForm.jsx?");

/***/ }),

/***/ "./add_video/components/Video.jsx":
/*!****************************************!*\
  !*** ./add_video/components/Video.jsx ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nvar _wp$components = wp.components,\n    Icon = _wp$components.Icon,\n    Button = _wp$components.Button,\n    ButtonGroup = _wp$components.ButtonGroup,\n    __ = wp.i18n.__;\n\nvar Video = function Video(props) {\n  return /*#__PURE__*/React.createElement(\"div\", {\n    className: \"vimeotheque-video\"\n  }, /*#__PURE__*/React.createElement(\"div\", {\n    className: \"image\"\n  }, /*#__PURE__*/React.createElement(\"img\", {\n    src: props.data.thumbnails[2],\n    alt: props.data.title\n  }), /*#__PURE__*/React.createElement(\"span\", {\n    className: \"duration\"\n  }, props.data._duration), 'public' != props.data.privacy && /*#__PURE__*/React.createElement(Icon, {\n    icon: \"lock\"\n  })), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"video\"\n  }, /*#__PURE__*/React.createElement(\"h1\", null, props.data.title), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"meta\"\n  }, /*#__PURE__*/React.createElement(\"span\", {\n    className: \"published\"\n  }, props.data._published), /*#__PURE__*/React.createElement(\"span\", {\n    className: \"by\"\n  }, \"by \".concat(props.data.uploader))), /*#__PURE__*/React.createElement(Button, {\n    isPrimary: true,\n    onClick: function onClick() {\n      props.onClick(props.data);\n    }\n  }, __('Import video', 'cvm_video')), /*#__PURE__*/React.createElement(Button, {\n    isSecondary: true,\n    onClick: props.onCancel\n  }, __('Cancel', 'cvm_video'))));\n};\n\nVideo.defaultProps = {\n  data: {},\n  onClick: function onClick() {},\n  onCancel: function onCancel() {}\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (Video);\n\n//# sourceURL=webpack:///./add_video/components/Video.jsx?");

/***/ }),

/***/ "./add_video/components/VideoImporter.jsx":
/*!************************************************!*\
  !*** ./add_video/components/VideoImporter.jsx ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _query_videoQueryApplyWithSelect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../query/videoQueryApplyWithSelect */ \"./add_video/query/videoQueryApplyWithSelect.js\");\n\nvar _wp$components = wp.components,\n    Spinner = _wp$components.Spinner,\n    Notice = _wp$components.Notice,\n    Placeholder = _wp$components.Placeholder,\n    Button = _wp$components.Button,\n    ButtonGroup = _wp$components.ButtonGroup,\n    __ = wp.i18n.__;\n\nvar VideoImporterBase = function VideoImporterBase(props) {\n  return /*#__PURE__*/React.createElement(React.Fragment, null, props.loading ? /*#__PURE__*/React.createElement(Placeholder, {\n    className: \"loading\",\n    label: __('Saving video', 'cvm_video')\n  }, __('Your video post is being created, please wait...', 'cvm_video'), /*#__PURE__*/React.createElement(Spinner, null)) : /*#__PURE__*/React.createElement(\"div\", null, props.error ? /*#__PURE__*/React.createElement(Notice, {\n    status: \"error\",\n    onRemove: function onRemove() {\n      props.onMessageClose();\n    }\n  }, props.error.message) : /*#__PURE__*/React.createElement(Placeholder, {\n    label: props.response.message\n  }, /*#__PURE__*/React.createElement(Button, {\n    isPrimary: true,\n    href: props.response.editLink\n  }, __('Edit post', 'cvm_video')), /*#__PURE__*/React.createElement(Button, {\n    isSecondary: true,\n    href: props.response.viewLink\n  }, __('View post', 'cvm_video')), /*#__PURE__*/React.createElement(Button, {\n    isTertiary: true,\n    onClick: function onClick() {\n      props.onMessageClose();\n    }\n  }, __('Import another video', 'cvm_video')))));\n};\n\nVideoImporterBase.defaultProps = {\n  video: {},\n  onMessageClose: function onMessageClose() {}\n};\nvar VideoImporter = Object(_query_videoQueryApplyWithSelect__WEBPACK_IMPORTED_MODULE_0__[\"postCreateApplyWithSelect\"])(VideoImporterBase);\n/* harmony default export */ __webpack_exports__[\"default\"] = (VideoImporter);\n\n//# sourceURL=webpack:///./add_video/components/VideoImporter.jsx?");

/***/ }),

/***/ "./add_video/components/VideoQuery.jsx":
/*!*********************************************!*\
  !*** ./add_video/components/VideoQuery.jsx ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _query_videoQueryApplyWithSelect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../query/videoQueryApplyWithSelect */ \"./add_video/query/videoQueryApplyWithSelect.js\");\n/* harmony import */ var _Video__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Video */ \"./add_video/components/Video.jsx\");\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(n); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\n\n\nvar _wp$element = wp.element,\n    useState = _wp$element.useState,\n    useEffect = _wp$element.useEffect,\n    _wp$components = wp.components,\n    Spinner = _wp$components.Spinner,\n    Notice = _wp$components.Notice,\n    Placeholder = _wp$components.Placeholder,\n    __ = wp.i18n.__;\n\nvar VideoQueryBase = function VideoQueryBase(props) {\n  var _useState = useState(true),\n      _useState2 = _slicedToArray(_useState, 2),\n      showNotice = _useState2[0],\n      setShowNotice = _useState2[1];\n\n  useEffect(function () {\n    if (!showNotice) {\n      setShowNotice(true);\n    }\n  }, [props.loading]);\n  return /*#__PURE__*/React.createElement(React.Fragment, null, props.loading ? /*#__PURE__*/React.createElement(Placeholder, {\n    className: \"loading\",\n    label: __('Making query to Vimeo', 'cvm_video')\n  }, __('Please wait...', 'cvm_video'), /*#__PURE__*/React.createElement(Spinner, null)) : /*#__PURE__*/React.createElement(\"div\", null, props.error ? showNotice && /*#__PURE__*/React.createElement(Notice, {\n    status: \"error\",\n    onRemove: function onRemove() {\n      props.onCancel();\n      setShowNotice(false);\n    }\n  }, props.error.message) : /*#__PURE__*/React.createElement(_Video__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n    data: props.video,\n    onClick: props.onSubmit,\n    onCancel: props.onCancel\n  })));\n};\n\nVideoQueryBase.defaultProps = {\n  loading: false,\n  video: false,\n  error: false,\n  query: '',\n  onSubmit: function onSubmit() {}\n};\nvar VideoQuery = Object(_query_videoQueryApplyWithSelect__WEBPACK_IMPORTED_MODULE_0__[\"videoQueryApplyWithSelect\"])(VideoQueryBase);\n/* harmony default export */ __webpack_exports__[\"default\"] = (VideoQuery);\n\n//# sourceURL=webpack:///./add_video/components/VideoQuery.jsx?");

/***/ }),

/***/ "./add_video/query/videoQueryApplyWithSelect.js":
/*!******************************************************!*\
  !*** ./add_video/query/videoQueryApplyWithSelect.js ***!
  \******************************************************/
/*! exports provided: videoQueryApplyWithSelect, postCreateApplyWithSelect */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"videoQueryApplyWithSelect\", function() { return videoQueryApplyWithSelect; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"postCreateApplyWithSelect\", function() { return postCreateApplyWithSelect; });\nfunction _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }\n\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(n); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\nvar withSelect = wp.data.withSelect,\n    _wp = wp,\n    apiFetch = _wp.apiFetch,\n    _wp$element = wp.element,\n    useState = _wp$element.useState,\n    useEffect = _wp$element.useEffect;\nvar videoQueryApplyWithSelect = function videoQueryApplyWithSelect(Component, props) {\n  return function (props) {\n    var _useState = useState({\n      loading: true,\n      video: false,\n      error: false\n    }),\n        _useState2 = _slicedToArray(_useState, 2),\n        state = _useState2[0],\n        setState = _useState2[1];\n\n    useEffect(function () {\n      if (!state.loading) {\n        setState({\n          loading: true,\n          video: false,\n          error: false\n        });\n      }\n    }, [props.query]);\n\n    if (state.loading) {\n      apiFetch({\n        path: \"/vimeotheque/v1/api-query/video/?id=\".concat(props.query),\n        method: 'GET'\n      }).then(function (result) {\n        setState({\n          loading: false,\n          video: result,\n          error: false\n        });\n      })[\"catch\"](function (error) {\n        setState({\n          loading: false,\n          video: false,\n          error: error\n        });\n      });\n    }\n\n    return /*#__PURE__*/React.createElement(Component, _extends({}, state, props));\n  };\n};\nvar postCreateApplyWithSelect = function postCreateApplyWithSelect(Component, props) {\n  return function (props) {\n    var _useState3 = useState({\n      loading: true,\n      response: false,\n      error: false\n    }),\n        _useState4 = _slicedToArray(_useState3, 2),\n        state = _useState4[0],\n        setState = _useState4[1];\n\n    useEffect(function () {\n      if (!state.loading) {\n        setState({\n          loading: true,\n          response: false,\n          error: false\n        });\n      }\n    }, [props.video]);\n\n    if (state.loading) {\n      apiFetch({\n        path: '/vimeotheque/v1/wp/post-create/',\n        method: 'POST',\n        data: {\n          video: props.video,\n          postId: wpApiSettings.postId\n        }\n      }).then(function (result) {\n        setState({\n          loading: false,\n          response: result,\n          error: false\n        });\n      })[\"catch\"](function (error) {\n        setState({\n          loading: false,\n          response: false,\n          error: error\n        });\n      });\n    }\n\n    return /*#__PURE__*/React.createElement(Component, _extends({}, state, props));\n  };\n};\n\n//# sourceURL=webpack:///./add_video/query/videoQueryApplyWithSelect.js?");

/***/ })

/******/ });