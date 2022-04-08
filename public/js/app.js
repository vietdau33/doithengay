/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

window.App = {
  init: function init() {
    this.setSeePasswordInput();
    this.setErrorsUIView();
    this.setPositionCopyright();
    this.setClickCheckboxButton();
    this.setAutoSize();
    this.setEventClickMenu();
    this.preventEnterSubmit();
  },
  setPositionCopyright: function setPositionCopyright() {
    var cprh = $('#copyright').height();
    var hh = $('#header').height();
    $('#main-contents').css({
      'min-height': 'calc(100vh - ' + cprh + 'px)'
    });
    $('#main-contents').css({
      'padding-top': hh + 10 + 'px'
    });
  },
  setSeePasswordInput: function setSeePasswordInput() {
    var clsEyeShow = 'fa-eye';
    var clsEyeHide = 'fa-eye-slash';
    var elEye = $('<i />').addClass('fas show-hide-pwd');
    elEye.on('click', function () {
      var parent = $(this).parent();
      var elPwd = parent.find('input');

      if ($(this).hasClass(clsEyeShow)) {
        $(this).removeClass(clsEyeShow).addClass(clsEyeHide);
        elPwd.attr('type', 'password');
      } else {
        $(this).removeClass(clsEyeHide).addClass(clsEyeShow);
        elPwd.attr('type', 'text');
      }
    });
    $('input[type="password"]:not(.setted-see-password)').each(function () {
      var elEyeCopy = elEye.clone(true, true);
      elEyeCopy.addClass('fa-eye-slash');
      $(this).addClass('setted-see-password');
      $(this).parent().addClass('position-relative').append(elEyeCopy);
    });
  },
  setErrorsUIView: function setErrorsUIView() {
    if (_typeof(window.errors) != 'object') {
      return;
    }

    if (Object.keys(window.errors).length <= 0) {
      return;
    }

    var errEl = $('<p />').addClass('error');

    var _loop = function _loop(key) {
      var error = window.errors[key];
      var el = $('[name="' + key + '"]');

      if (el.length <= 0) {
        return "continue";
      }

      if (el.attr('type') == 'radio' || el.attr('type') == 'checkbox') {
        return "continue";
      }

      el.off('input.remove_error');
      el.on('input.remove_error', function () {
        el.parent().find('.error').fadeOut(300, function () {
          $(this).remove();
        });
      });
      el.parent().find('.error').remove();

      for (var _i in error) {
        el.before(errEl.clone().text(error[_i]));
      }

      delete window.errors[key];
    };

    for (var key in window.errors) {
      var _ret = _loop(key);

      if (_ret === "continue") continue;
    }

    $('form').find('#area-error-ui-show').remove();

    if (Object.keys(window.errors).length <= 0) {
      return;
    }

    var errArea = $('<div />').addClass('alert alert-warning').attr('id', 'area-error-ui-show');
    var pText = $('<p />').addClass('m-0');

    for (var _key in window.errors) {
      var error = window.errors[_key];

      for (var i in error) {
        errArea.append(pText.clone().text(error));
      }

      delete window.errors[_key];
    }

    $('form').prepend(errArea);
  },
  setClickCheckboxButton: function setClickCheckboxButton() {
    var triggerChecked = function triggerChecked() {
      $('form input[type="checkbox"]:checked, form input[type="radio"]:checked').closest('label').addClass('checked');
      $('form input[type="checkbox"]:not(:checked), form input[type="radio"]:not(:checked)').closest('label').removeClass('checked');
    };

    setTimeout(triggerChecked, 0);
    $('input[type="checkbox"], input[type="radio"]').on('click', function () {
      setTimeout(triggerChecked, 50);
    });
  },
  setAutoSize: function setAutoSize() {
    if (typeof autosize == 'function') {
      autosize($('textarea.autosize'));
    }
  },
  setEventClickMenu: function setEventClickMenu() {
    $(document).on('click', function (event) {
      if ($(event.target).closest('.bar-user-menu').length <= 0) {
        $('.bar-user-icon').removeClass('show');
        $('.bar-user-icon .menu-user').hide(300);
      }
    });
    $('.bar-user-icon').on('click', function (event) {
      if (!$(event.target).is('.bar-user-icon')) {
        return;
      }

      if ($(this).hasClass('show')) {
        $('.bar-user-icon').removeClass('show');
        $('.bar-user-icon .menu-user').hide(300);
      } else {
        $('.bar-user-icon').addClass('show');
        $('.bar-user-icon .menu-user').show(300);
      }
    });
  },
  preventEnterSubmit: function preventEnterSubmit() {
    $(document).on("keydown", ":input:not(textarea)", function (event) {
      if (event.key == "Enter") {
        event.preventDefault();
      }
    });
  }
};
window.addEventListener('DOMContentLoaded', function () {
  window.App.init();
});

/***/ }),

/***/ "./resources/css/app.scss":
/*!********************************!*\
  !*** ./resources/css/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/css/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;