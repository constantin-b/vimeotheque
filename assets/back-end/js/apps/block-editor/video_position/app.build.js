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
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/back-end/js/apps/block-editor/video_position/block.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/back-end/js/apps/block-editor/video_position/block.js":
/*!**********************************************************************!*\
  !*** ./assets/back-end/js/apps/block-editor/video_position/block.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }\n\nfunction _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }\n\nfunction _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }\n\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(o); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\nvar _wp = wp,\n    InspectorControls = _wp.blockEditor.InspectorControls,\n    registerBlockType = _wp.blocks.registerBlockType,\n    _wp$components = _wp.components,\n    Panel = _wp$components.Panel,\n    PanelBody = _wp$components.PanelBody,\n    PanelRow = _wp$components.PanelRow,\n    ColorIndicator = _wp$components.ColorIndicator,\n    ColorPalette = _wp$components.ColorPalette,\n    Dropdown = _wp$components.Dropdown,\n    TextControl = _wp$components.TextControl,\n    SelectControl = _wp$components.SelectControl,\n    ToggleControl = _wp$components.ToggleControl,\n    withState = _wp.compose.withState,\n    _wp$element = _wp.element,\n    useCallback = _wp$element.useCallback,\n    useEffect = _wp$element.useEffect,\n    useState = _wp$element.useState,\n    _wp$hooks = _wp.hooks,\n    applyFilters = _wp$hooks.applyFilters,\n    doAction = _wp$hooks.doAction,\n    __ = _wp.i18n.__;\nregisterBlockType('vimeotheque/video-position', {\n  title: __('Vimeotheque video position', 'codeflavors-vimeo-video-post-lite'),\n  description: __('Video embed customization options', 'codeflavors-vimeo-video-post-lite'),\n  icon: 'video-alt3',\n  category: 'layout',\n  attributes: {\n    embed_options: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_playback_settings',\n      \"default\": false\n    },\n    video_id: {\n      type: 'string',\n      source: 'meta',\n      meta: '__cvm_video_id',\n      \"default\": false\n    },\n\n    /**\r\n     * Extra options that get set up by third party add-ons\r\n     * and will be used to complete the \"embed_options\" meta value\r\n     */\n    extra: {\n      type: 'object',\n      \"default\": {}\n    }\n  },\n  supports: {\n    align: false,\n    anchor: false,\n    html: false,\n    multiple: false,\n    reusable: false,\n    customClassName: false\n  },\n  example: {\n    attributes: {\n      video_id: '1084537',\n      embed_options: '{\"title\":1,\"byline\":1,\"portrait\":1,\"color\":\"\",\"loop\":0,\"autoplay\":1,\"aspect_ratio\":\"16x9\",\"width\":200,\"video_position\":\"below-content\",\"volume\":70,\"playlist_loop\":0}'\n    }\n  },\n  edit: function edit(props) {\n    var _props$attributes = props.attributes,\n        embed_options = _props$attributes.embed_options,\n        extraOptions = _props$attributes.extra,\n        video_id = _props$attributes.video_id,\n        setAttributes = props.setAttributes,\n        className = props.className,\n        _useState = useState(JSON.parse(embed_options)),\n        _useState2 = _slicedToArray(_useState, 2),\n        embedOptions = _useState2[0],\n        setEmbedOptions = _useState2[1];\n\n    onFormToggleChange = function onFormToggleChange(varName) {\n      setOption(varName, !embedOptions[varName]);\n    }, setOption = function setOption(varName, value) {\n      var _opt = {};\n      _opt[varName] = value;\n      setEmbedOptions(_objectSpread(_objectSpread({}, embedOptions), _opt));\n    };\n\n    getEmbedURL = function getEmbedURL() {\n      var url = 'https://player.vimeo.com/video',\n          query = {\n        title: embedOptions.title,\n        byline: embedOptions.byline,\n        portrait: embedOptions.portrait,\n        loop: embedOptions.loop,\n        color: embedOptions.color,\n        autoplay: embedOptions.autoplay,\n        volume: embedOptions.volume,\n        dnt: embedOptions.dnt\n      };\n      return applyFilters('vimeotheque.video-position.embed-url', \"\".concat(url, \"/\").concat(video_id, \"?\").concat(jQuery.param(query)), url, video_id, query);\n    };\n\n    useEffect(function () {\n      setEmbedOptions(JSON.parse(embed_options));\n    }, [embed_options]);\n    useEffect(function () {\n      /**\r\n       * Combine embedOptions with extraOptions so that everything gets stored into the post meta\r\n       * for compatibility with the Classic Editor\r\n       */\n      setAttributes({\n        embed_options: JSON.stringify(_objectSpread(_objectSpread({}, embedOptions), extraOptions))\n      });\n    }, [embedOptions, extraOptions]);\n    return [/*#__PURE__*/React.createElement(\"div\", {\n      key: \"vimeotheque-video-position-block\"\n    }, /*#__PURE__*/React.createElement(\"div\", {\n      className: \"vimeotheque-player \" + embedOptions.video_align,\n      \"data-width\": embedOptions.width,\n      \"data-aspect_ratio\": embedOptions.aspect_ratio,\n      style: {\n        width: \"\".concat(embedOptions.width, \"px\"),\n        maxWidth: '100%'\n      },\n      onLoad: function onLoad(event) {\n        return vimeotheque.resize(event.currentTarget);\n      }\n    }, /*#__PURE__*/React.createElement(\"iframe\", {\n      src: getEmbedURL(),\n      width: \"100%\",\n      height: \"100%\",\n      frameBorder: \"0\",\n      webkitallowfullscreen: \"true\",\n      mozallowfullscreen: \"true\",\n      allowFullScreen: true\n    })), !props.isSelected && /*#__PURE__*/React.createElement(\"div\", {\n      style: {\n        position: 'absolute',\n        top: 0,\n        left: 0,\n        width: '100%',\n        height: '100%'\n      }\n    })),\n    /*#__PURE__*/\n\n    /*\r\n     * InspectorControls\r\n     */\n    React.createElement(InspectorControls, {\n      key: \"vimeotheque-video-position-controls\"\n    }, /*#__PURE__*/React.createElement(PanelBody, {\n      title: __('Embed options', 'codeflavors-vimeo-video-post-lite'),\n      initialOpen: true\n    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ToggleControl, {\n      label: __('Show title', 'codeflavors-vimeo-video-post-lite'),\n      checked: embedOptions.title,\n      onChange: function onChange() {\n        return onFormToggleChange('title');\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ToggleControl, {\n      label: __('Show byline', 'codeflavors-vimeo-video-post-lite'),\n      checked: embedOptions.byline,\n      onChange: function onChange() {\n        return onFormToggleChange('byline');\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ToggleControl, {\n      label: __('Show portrait', 'codeflavors-vimeo-video-post-lite'),\n      checked: embedOptions.portrait,\n      onChange: function onChange() {\n        return onFormToggleChange('portrait');\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ToggleControl, {\n      label: __('Loop video', 'codeflavors-vimeo-video-post-lite'),\n      checked: embedOptions.loop,\n      onChange: function onChange() {\n        return onFormToggleChange('loop');\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ToggleControl, {\n      label: __('Autoplay video', 'codeflavors-vimeo-video-post-lite'),\n      help: __(\"This feature won't work on all browsers.\", 'codeflavors-vimeo-video-post-lite'),\n      checked: embedOptions.autoplay,\n      onChange: function onChange() {\n        return onFormToggleChange('autoplay');\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(TextControl, {\n      label: __('Volume', 'codeflavors-vimeo-video-post-lite'),\n      help: __('Will work only for JS embeds', 'codeflavors-vimeo-video-post-lite'),\n      type: \"number\",\n      step: \"1\",\n      value: embedOptions.volume,\n      min: \"0\",\n      max: \"100\",\n      onChange: function onChange(value) {\n        var vol = value >= 0 && value <= 100 ? value : embedOptions.volume;\n        setOption('volume', vol);\n      }\n    }))), /*#__PURE__*/React.createElement(PanelBody, {\n      title: __('Embed size', 'codeflavors-vimeo-video-post-lite'),\n      initialOpen: false\n    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(TextControl, {\n      label: __('Width', 'codeflavors-vimeo-video-post-lite'),\n      type: \"number\",\n      step: \"5\",\n      value: embedOptions.width,\n      min: \"200\",\n      onChange: function onChange(value) {\n        var width = !value || value < 200 ? 200 : value;\n        setOption('width', width);\n        vimeotheque.resizeAll();\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {\n      label: __('Aspect ratio', 'codeflavors-vimeo-video-post-lite'),\n      value: embedOptions.aspect_ratio,\n      options: [{\n        label: '4x3',\n        value: '4x3'\n      }, {\n        label: '16x9',\n        value: '16x9'\n      }, {\n        label: '2.35x1',\n        value: '2.35x1'\n      }],\n      onChange: function onChange(value) {\n        setOption('aspect_ratio', value);\n        setTimeout(vimeotheque.resizeAll, 100);\n      }\n    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {\n      label: __('Align', 'codeflavors-vimeo-video-post-lite'),\n      value: embedOptions.video_align,\n      options: [{\n        label: 'left',\n        value: 'align-left'\n      }, {\n        label: 'center',\n        value: 'align-center'\n      }, {\n        label: 'right',\n        value: 'align-right'\n      }],\n      onChange: function onChange(value) {\n        setOption('video_align', value);\n      }\n    }))), /*#__PURE__*/React.createElement(PanelBody, {\n      title: __('Color options', 'codeflavors-vimeo-video-post-lite'),\n      initialOpen: false\n    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(\"label\", null, \"\".concat(__('Player color', 'codeflavors-vimeo-video-post-lite'), \" : \"), /*#__PURE__*/React.createElement(ColorIndicator, {\n      colorValue: \"#\".concat(embedOptions.color.replace('#', ''))\n    }), /*#__PURE__*/React.createElement(\"span\", null, embedOptions.color && \"#\".concat(embedOptions.color.replace('#', ''))))), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ColorPalette, {\n      value: \"#\".concat(embedOptions.color.replace('#', '')),\n      onChange: function onChange(color) {\n        var col = color.replace('#', '');\n        setOption('color', col);\n      }\n    }))))];\n  },\n  save: function save(props) {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./assets/back-end/js/apps/block-editor/video_position/block.js?");

/***/ })

/******/ });