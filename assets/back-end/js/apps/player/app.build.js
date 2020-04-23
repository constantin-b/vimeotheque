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
/******/ 	return __webpack_require__(__webpack_require__.s = "./player/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./player/app.js":
/*!***********************!*\
  !*** ./player/app.js ***!
  \***********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jQuery */ \"jQuery\");\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jQuery__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _inc_VimeoPlayer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./inc/VimeoPlayer */ \"./player/inc/VimeoPlayer.js\");\n/* harmony import */ var _inc_helper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./inc/helper */ \"./player/inc/helper.js\");\n/* harmony import */ var _inc_Playlist__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./inc/Playlist */ \"./player/inc/Playlist.js\");\nwindow.vimeotheque = window.vimeotheque || {};\n\n\n\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default()(document).ready(function () {\n  vimeotheque.players = jQuery__WEBPACK_IMPORTED_MODULE_0___default()('.vimeotheque-player').VimeoPlayer();\n});\n\n//# sourceURL=webpack:///./player/app.js?");

/***/ }),

/***/ "./player/inc/Playlist.js":
/*!********************************!*\
  !*** ./player/inc/Playlist.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jQuery */ \"jQuery\");\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jQuery__WEBPACK_IMPORTED_MODULE_0__);\n\n/**\r\n * Load playlists\r\n */\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlaylist = function (params) {\n  if (0 == this.length) {\n    return false;\n  } // support multiple elements\n\n\n  if (this.length > 1) {\n    return this.each(function (index, item) {\n      jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).VimeoPlaylist(options);\n    });\n  }\n\n  var options = jQuery__WEBPACK_IMPORTED_MODULE_0___default.a.extend({}, jQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlaylist.defaults, params),\n      self = this,\n      player = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(this).find(options.player).VimeoPlayer({\n    onFinish: function onFinish() {\n      loadNext();\n    }\n  }),\n      items = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(this).find(options.items),\n      _$$data = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(player).data(),\n      playlistLoop = _$$data.playlist_loop,\n      volume = _$$data.volume;\n\n  var currentItem = 0;\n  /**\r\n   * Start the plugin\r\n   *\r\n   * @returns {$.fn.VimeoPlaylist}\r\n   */\n\n  var initialize = function initialize() {\n    // prepare the player\n    vimeotheque.resize(player);\n    player.setVolume(volume / 100);\n    jQuery__WEBPACK_IMPORTED_MODULE_0___default.a.each(items, function (i, item) {\n      if (0 == i) {\n        loadItem(item, i);\n      }\n\n      jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).on('click', function (e) {\n        e.preventDefault();\n        player.getVolume().then(function (volume) {\n          loadItem(item, i, volume);\n        });\n      });\n    });\n    return self;\n  },\n\n  /**\r\n   * Load an item from items list\r\n   *\r\n   * @param item - HTML element\r\n   * @param int index - element index\r\n   */\n  loadItem = function loadItem(item, index) {\n    var volume = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;\n\n    var _$$data2 = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).data(),\n        autoplay = _$$data2.autoplay,\n        video_id = _$$data2.video_id,\n        size_ratio = _$$data2.size_ratio,\n        aspect_ratio = _$$data2.aspect_ratio;\n\n    jQuery__WEBPACK_IMPORTED_MODULE_0___default()(items[currentItem]).removeClass('active-video');\n    jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).addClass('active-video');\n    player.loadVideo(video_id).attr({\n      'data-size_ratio': size_ratio,\n      'data-aspect_ratio': aspect_ratio\n    });\n    if (volume) player.setVolume(volume);\n    vimeotheque.resize(player);\n\n    if ((1 == autoplay || 1 == playlistLoop) && !is_apple()) {\n      player.play();\n    }\n\n    currentItem = index;\n    /**\r\n     * Trigger loadVideo event\r\n     */\n\n    options.loadVideo.call(self, item, index, player);\n  },\n\n  /**\r\n   * Load next video in line. Triggered after a video ends\r\n   */\n  loadNext = function loadNext() {\n    if (1 != playlistLoop) {\n      return;\n    }\n\n    if (currentItem < items.length - 1) {\n      currentItem++;\n      var item = items[currentItem];\n      jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).trigger('click');\n    }\n  },\n\n  /**\r\n   * Check browser\r\n   *\r\n   * @returns {boolean}\r\n   */\n  is_apple = function is_apple() {\n    return /webOS|iPhone|iPad|iPod/i.test(navigator.userAgent);\n  };\n\n  return initialize();\n};\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlaylist.defaults = {\n  'player': '.vimeotheque-player',\n  'items': '.cvm-playlist-item a',\n  'loadVideo': function loadVideo() {}\n};\n\n//# sourceURL=webpack:///./player/inc/Playlist.js?");

/***/ }),

/***/ "./player/inc/VimeoPlayer.js":
/*!***********************************!*\
  !*** ./player/inc/VimeoPlayer.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jQuery */ \"jQuery\");\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jQuery__WEBPACK_IMPORTED_MODULE_0__);\n\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlayer = function (params) {\n  if (0 == this.length) {\n    return false;\n  } // support multiple elements\n\n\n  if (this.length > 1) {\n    return this.each(function (index, item) {\n      jQuery__WEBPACK_IMPORTED_MODULE_0___default()(item).VimeoPlayer();\n    });\n  }\n\n  var self = this,\n      options = jQuery__WEBPACK_IMPORTED_MODULE_0___default.a.extend({}, jQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlayer.defaults, params),\n      player = new Vimeo.Player(jQuery__WEBPACK_IMPORTED_MODULE_0___default()(this).find('iframe')),\n      data = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(this).data();\n  player.on('loaded', options.onLoad);\n  player.on('play', options.onPlay);\n  player.on('timeupdate', options.onPlayback);\n  player.on('pause', options.onPause);\n  player.on('ended', options.onFinish);\n  player.on('error', options.onError);\n  /**\r\n   * Load a new video into the player\r\n   * @param id\r\n   * @return {$.fn.VimeoPlayer}\r\n   */\n\n  this.loadVideo = function (id) {\n    player.loadVideo(id).then(function (id) {})[\"catch\"](function (error) {//console.log(error)\n    });\n    return self;\n  };\n  /**\r\n   * Pause video method\r\n   * @return {$.fn.VimeoPlayer}\r\n   */\n\n\n  this.pause = function () {\n    player.pause().then(function () {})[\"catch\"](function (error) {//console.log(error)\n    });\n    return self;\n  };\n  /**\r\n   * Play video method\r\n   * @return {$.fn.VimeoPlayer}\r\n   */\n\n\n  this.play = function () {\n    player.play().then(function () {})[\"catch\"](function (error) {//console.log(error)\n    });\n    return self;\n  };\n  /**\r\n   * This method sets the volume level of the player on a scale from 0 to 1.\r\n   * When you set the volume through the API, the specified value isn't synchronized to other players or\r\n   * stored as the viewer's preference.\r\n   *\r\n   * Set volume (between 0 and 1)\r\n   *\r\n   * @param volume\r\n   * @return {$.fn.VimeoPlayer}\r\n   */\n\n\n  this.setVolume = function (volume) {\n    player.setVolume(volume).then(function (_volume) {})[\"catch\"](function (error) {//console.log(error)\n    });\n    return self;\n  };\n  /**\r\n   * Get current volume of player\r\n   *\r\n   * @return Promise\r\n   */\n\n\n  this.getVolume = function () {\n    return player.getVolume();\n  };\n  /**\r\n   * This method sets the current playback position in seconds.\r\n   * The player attempts to seek to as close to the specified time as possible.\r\n   * The exact time comes back as the fulfilled value of the promise.\r\n   *\r\n   * @param float seconds - where to start playback, in seconds.milliseconds (ie. 3.543)\r\n   * @return {$.fn.VimeoPlayer}\r\n   */\n\n\n  this.setPlaybackPosition = function (seconds) {\n    player.setCurrentTime(seconds).then(function (_seconds) {})[\"catch\"](function (error) {//console.log(error)\n    });\n    return self;\n  };\n  /**\r\n   *\r\n   * @returns {Vimeo.Player}\r\n   */\n\n\n  this.getPlayer = function () {\n    return player;\n  };\n\n  jQuery__WEBPACK_IMPORTED_MODULE_0___default()(this).data('ref', this);\n  return self;\n};\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default.a.fn.VimeoPlayer.defaults = {\n  onLoad: function onLoad(data) {},\n  onPlay: function onPlay(data) {},\n  onPlayback: function onPlayback(data) {},\n  onPause: function onPause(data) {},\n  onFinish: function onFinish(data) {},\n  onError: function onError(data) {}\n};\n\n//# sourceURL=webpack:///./player/inc/VimeoPlayer.js?");

/***/ }),

/***/ "./player/inc/helper.js":
/*!******************************!*\
  !*** ./player/inc/helper.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jQuery */ \"jQuery\");\n/* harmony import */ var jQuery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jQuery__WEBPACK_IMPORTED_MODULE_0__);\n/** @namespace vimeotheque */\nwindow.vimeotheque = window.vimeotheque || {};\n\n\nvimeotheque.resizeAll = function () {\n  jQuery__WEBPACK_IMPORTED_MODULE_0___default()('div.vimeotheque-player').each(function (i, el) {\n    vimeotheque.resize(el);\n  });\n};\n\nvimeotheque.resize = function (element) {\n  var size_ratio = parseFloat(jQuery__WEBPACK_IMPORTED_MODULE_0___default()(element).attr('data-size_ratio') || 0),\n      aspect_ratio = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(element).attr('data-aspect_ratio'),\n      width = jQuery__WEBPACK_IMPORTED_MODULE_0___default()(element).width();\n  var height;\n\n  if (size_ratio > 0) {\n    height = Math.floor(width / size_ratio);\n  } else {\n    switch (aspect_ratio) {\n      case '16x9':\n      default:\n        height = Math.floor(width * 9 / 16);\n        break;\n\n      case '4x3':\n        height = Math.floor(width * 3 / 4);\n        break;\n\n      case '2.35x1':\n        height = Math.floor(width / 2.35);\n        break;\n    }\n  }\n\n  jQuery__WEBPACK_IMPORTED_MODULE_0___default()(element).css({\n    height: height\n  });\n};\n\njQuery__WEBPACK_IMPORTED_MODULE_0___default()(document).ready(vimeotheque.resizeAll);\njQuery__WEBPACK_IMPORTED_MODULE_0___default()(window).resize(vimeotheque.resizeAll);\n\n//# sourceURL=webpack:///./player/inc/helper.js?");

/***/ }),

/***/ "jQuery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = jQuery;\n\n//# sourceURL=webpack:///external_%22jQuery%22?");

/***/ })

/******/ });