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
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _components_VideoPostsList__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/VideoPostsList */ \"./components/VideoPostsList.jsx\");\nfunction _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance\"); }\n\nfunction _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === \"[object Arguments]\")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\n\nvar registerBlockType = wp.blocks.registerBlockType,\n    __ = wp.i18n.__,\n    _wp$components = wp.components,\n    Placeholder = _wp$components.Placeholder,\n    Button = _wp$components.Button,\n    ButtonGroup = _wp$components.ButtonGroup,\n    Modal = _wp$components.Modal,\n    useState = wp.element.useState;\nregisterBlockType('vimeotheque/video-playlist', {\n  title: __('Vimeotheque playlist', 'cvm_video'),\n  description: __('Video playlist block', 'cvm_video'),\n  icon: 'playlist-video',\n  category: 'widgets',\n  attributes: {},\n  example: {},\n  edit: function edit(props) {\n    var _useState = useState(false),\n        _useState2 = _slicedToArray(_useState, 2),\n        isOpen = _useState2[0],\n        setOpen = _useState2[1],\n        openModal = function openModal(e) {\n      e.stopPropagation();\n      setOpen(true);\n    },\n        closeModal = function closeModal() {\n      setOpen(false);\n    };\n\n    return [React.createElement(Placeholder, {\n      icon: \"playlist-video\",\n      label: __('Video playlist', 'cvm_video')\n    }, React.createElement(Button, {\n      isPrimary: true,\n      onClick: openModal\n    }, __(' Choose posts', 'cvm_video')), isOpen && React.createElement(Modal, {\n      title: __('Choose posts', 'cvm_video'),\n      onRequestClose: closeModal,\n      className: \"vimeotheque-posts-list-modal\"\n    }, React.createElement(_components_VideoPostsList__WEBPACK_IMPORTED_MODULE_0__[\"default\"], {\n      onClick: function onClick(postId) {\n        alert('clicked ' + postId);\n      }\n    })))];\n  },\n  save: function save(props) {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./block.js?");

/***/ }),

/***/ "./components/List.jsx":
/*!*****************************!*\
  !*** ./components/List.jsx ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }\n\nfunction _nonIterableSpread() { throw new TypeError(\"Invalid attempt to spread non-iterable instance\"); }\n\nfunction _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === \"[object Arguments]\") return Array.from(iter); }\n\nfunction _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\nvar _wp = wp,\n    apiFetch = _wp.apiFetch,\n    __ = wp.i18n.__,\n    dateI18n = wp.date.dateI18n,\n    _wp$components = wp.components,\n    Button = _wp$components.Button,\n    ButtonGroup = _wp$components.ButtonGroup;\n\nvar List =\n/*#__PURE__*/\nfunction (_React$Component) {\n  _inherits(List, _React$Component);\n\n  function List(props) {\n    var _this;\n\n    _classCallCheck(this, List);\n\n    _this = _possibleConstructorReturn(this, _getPrototypeOf(List).call(this, props));\n    _this.state = {\n      posts: [],\n      loading: false,\n      error: false\n    };\n    return _this;\n  }\n\n  _createClass(List, [{\n    key: \"makeRequest\",\n    value: function makeRequest() {\n      var self = this;\n      self.setState({\n        loading: true\n      });\n      apiFetch({\n        path: '/wp/v2/' + self.props.postType + '?page=' + self.props.page + '&per_page=' + self.props.perPage + '&orderby=date&order=desc' + '&vimeothequeMetaKey=true'\n      }).then(function (posts) {\n        var _posts = [].concat(_toConsumableArray(self.state.posts), _toConsumableArray(posts));\n\n        self.setState({\n          posts: _posts,\n          loading: false\n        });\n        self.props.onRequestFinish(_posts.length);\n      })[\"catch\"](function (error) {\n        self.setState({\n          error: error,\n          loading: false\n        });\n        self.props.onRequestError(error);\n      });\n    }\n  }, {\n    key: \"componentDidMount\",\n    value: function componentDidMount() {\n      this.makeRequest();\n    }\n  }, {\n    key: \"componentDidUpdate\",\n    value: function componentDidUpdate(prevProps) {\n      if (this.props.postType != prevProps.postType) {\n        this.setState({\n          posts: [],\n          loading: false,\n          error: false\n        });\n        this.makeRequest();\n        return;\n      }\n\n      if ((this.props.page != prevProps.page || this.props.postType != prevProps.postType) && !this.state.error) {\n        this.makeRequest();\n      }\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var messages;\n\n      if (this.state.loading) {\n        messages = React.createElement(\"div\", {\n          className: \"vimeotheque-loading vimeotheque-post-list-container\"\n        }, __('Please wait, your video posts are loading...', 'cvm_video'));\n      } else if (this.state.posts.length == 0) {\n        messages = React.createElement(\"div\", {\n          className: \"vimeotheque-error vimeotheque-post-list-container\"\n        }, this.state.error ? this.state.error.message : __('We couldn\\'t find any video posts, sorry.', 'cvm_video'));\n      }\n\n      console.log(this.state.posts);\n      return React.createElement(\"div\", null, React.createElement(\"div\", {\n        className: \"vimeotheque-entries row\"\n      }, this.state.posts.map(function (post) {\n        return React.createElement(\"div\", {\n          key: post.id,\n          className: \"col-xs-6  col-sm-4 col-md-3 col-lg-2 grid-element\"\n        }, React.createElement(\"div\", {\n          className: \"cvm-video\"\n        }, React.createElement(\"div\", {\n          className: \"cvm-thumbnail\"\n        }, post.vimeo_video != null && React.createElement(\"div\", null, React.createElement(\"img\", {\n          src: post.vimeo_video.thumbnails[2]\n        }), React.createElement(\"span\", {\n          className: \"duration\"\n        }, post.vimeo_video._duration))), React.createElement(\"div\", {\n          className: \"details\"\n        }, React.createElement(\"h4\", null, post.title.rendered), React.createElement(\"div\", {\n          className: \"meta\"\n        }, React.createElement(\"span\", {\n          className: \"publish-date\"\n        }, dateI18n('M d Y', post.date))), React.createElement(\"div\", {\n          className: \"actions\"\n        }, React.createElement(Button, {\n          isTertiary: true,\n          onClick: function onClick() {\n            _this2.props.onClick(post.id);\n          }\n        }, __('Add to list', 'cvm_video'))))));\n      })), messages);\n    }\n  }]);\n\n  return List;\n}(React.Component);\n\nList.defaultProps = {\n  postType: 'vimeo-video',\n  page: 1,\n  perPage: 10,\n  onClick: function onClick() {},\n  onRequestFinish: function onRequestFinish() {},\n  onRequestError: function onRequestError() {}\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (List);\n\n//# sourceURL=webpack:///./components/List.jsx?");

/***/ }),

/***/ "./components/PostTypeButton.jsx":
/*!***************************************!*\
  !*** ./components/PostTypeButton.jsx ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\nvar Button = wp.components.Button;\n\nvar PostTypeButton =\n/*#__PURE__*/\nfunction (_React$Component) {\n  _inherits(PostTypeButton, _React$Component);\n\n  function PostTypeButton(props) {\n    var _this;\n\n    _classCallCheck(this, PostTypeButton);\n\n    _this = _possibleConstructorReturn(this, _getPrototypeOf(PostTypeButton).call(this, props));\n    _this.handleChange = _this.handleChange.bind(_assertThisInitialized(_this));\n    return _this;\n  }\n\n  _createClass(PostTypeButton, [{\n    key: \"handleChange\",\n    value: function handleChange(e) {\n      this.props.onClick(this.props.postType);\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      return React.createElement(Button, {\n        onClick: this.handleChange\n      }, this.props.text);\n    }\n  }]);\n\n  return PostTypeButton;\n}(React.Component);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (PostTypeButton);\n\n//# sourceURL=webpack:///./components/PostTypeButton.jsx?");

/***/ }),

/***/ "./components/VideoPostsList.jsx":
/*!***************************************!*\
  !*** ./components/VideoPostsList.jsx ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _PostTypeButton__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PostTypeButton */ \"./components/PostTypeButton.jsx\");\n/* harmony import */ var _List__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List */ \"./components/List.jsx\");\nfunction _typeof(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof(obj); }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } return _assertThisInitialized(self); }\n\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\n\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }\n\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\n\n\n\nvar _wp = wp,\n    apiFetch = _wp.apiFetch,\n    __ = wp.i18n.__,\n    _wp$components = wp.components,\n    Button = _wp$components.Button,\n    ButtonGroup = _wp$components.ButtonGroup;\n/**\r\n * Video posts list component\r\n */\n\nvar VideoPostsList =\n/*#__PURE__*/\nfunction (_React$Component) {\n  _inherits(VideoPostsList, _React$Component);\n\n  function VideoPostsList(props) {\n    var _this;\n\n    _classCallCheck(this, VideoPostsList);\n\n    _this = _possibleConstructorReturn(this, _getPrototypeOf(VideoPostsList).call(this, props));\n    _this.state = {\n      // used inly to initialize state; controlled exclusively internally\n      postType: _this.props.postType,\n      page: 1,\n      loading: false,\n      error: false,\n      postsCount: 0\n    };\n    _this.handlePostTypeChange = _this.handlePostTypeChange.bind(_assertThisInitialized(_this));\n    _this.handleLoadMore = _this.handleLoadMore.bind(_assertThisInitialized(_this));\n    return _this;\n  }\n\n  _createClass(VideoPostsList, [{\n    key: \"isLoading\",\n    value: function isLoading() {\n      return this.state.loading;\n    }\n  }, {\n    key: \"isError\",\n    value: function isError() {\n      return this.state.error;\n    }\n  }, {\n    key: \"handlePostTypeChange\",\n    value: function handlePostTypeChange(postType) {\n      if (postType == this.state.postType || this.isLoading()) {\n        return;\n      }\n\n      this.setState({\n        postType: postType,\n        page: 1,\n        loading: true,\n        postsCount: 0\n      });\n    }\n  }, {\n    key: \"handleLoadMore\",\n    value: function handleLoadMore() {\n      if (!this.isLoading() && !this.isError() && this.state.postsCount) {\n        this.setState({\n          page: this.state.page + 1,\n          loading: true\n        });\n      }\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      return React.createElement(\"div\", {\n        className: \"vimeotheque-post-list-container\",\n        key: \"vimeotheque-post-list-container\"\n      }, React.createElement(ButtonGroup, {\n        className: \"vimeotheque-post-type-filter\"\n      }, React.createElement(_PostTypeButton__WEBPACK_IMPORTED_MODULE_0__[\"default\"], {\n        postType: \"vimeo-video\",\n        text: __('Vimeo Videos', 'cvm_video'),\n        onClick: this.handlePostTypeChange\n      }), \"\\xA0|\\xA0\", React.createElement(_PostTypeButton__WEBPACK_IMPORTED_MODULE_0__[\"default\"], {\n        postType: \"posts\",\n        text: __('Posts', 'cvm_video'),\n        onClick: this.handlePostTypeChange\n      })), React.createElement(Button, {\n        isPrimary: true,\n        onClick: this.handleLoadMore\n      }, __('Load more', 'cvm_video')), React.createElement(_List__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n        postType: this.state.postType,\n        page: this.state.page,\n        perPage: this.props.perPage,\n        onClick: this.props.onClick,\n        onRequestFinish: function onRequestFinish(postsCount) {\n          _this2.setState({\n            loading: false,\n            error: false,\n            postsCount: postsCount\n          });\n        },\n        onRequestError: function onRequestError(error) {\n          _this2.setState({\n            loading: false,\n            error: error\n          });\n        }\n      }));\n    }\n  }]);\n\n  return VideoPostsList;\n}(React.Component);\n/**\r\n * Component defaults\r\n *\r\n * @type {{onClick: VideoPostsList.defaultProps.onClick, postType: string, page: number}}\r\n */\n\n\nVideoPostsList.defaultProps = {\n  postType: 'vimeo-video',\n  perPage: 20,\n  onClick: function onClick() {}\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (VideoPostsList);\n\n//# sourceURL=webpack:///./components/VideoPostsList.jsx?");

/***/ })

/******/ });