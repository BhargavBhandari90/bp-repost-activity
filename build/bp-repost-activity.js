/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/css/style.css":
/*!******************************!*\
  !*** ./assets/css/style.css ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/js/custom.js":
/*!*****************************!*\
  !*** ./assets/js/custom.js ***!
  \*****************************/
/***/ (() => {

"use strict";


/* global RE_Post_Activity */
var currentRequest = null;
(function ($) {
  "use strict";

  window.BP_Repost = {
    init: function init() {
      this.bprpa_repost();
      this.bprpa_set_param();
      // this.bprpa_reset_form();
      this.bprpa_show_whereto_post();
      this.bprpa_show_repost_options();
    },
    /**
     * Set perameter in ajax request for post update.
     */
    bprpa_set_param: function bprpa_set_param() {
      $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        // Check if form is available or not.
        if ($('.bp-repost-activity').length == 0) {
          return true;
        } // Modify options, control originalOptions, store jqXHR, etc

        try {
          if (originalOptions.data == null || typeof originalOptions.data === 'undefined' || typeof originalOptions.data.action === 'undefined') {
            return true;
          }
        } catch (e) {
          return true;
        }
        var original_activity_id = $('#repost-activity-form #original_item_id').val(),
          posting_at = $('#repost-activity-form #posting_at').val(),
          group_id = $('#repost-activity-form #rpa_group_id').val(),
          group_args = '',
          new_content = $('#repost-activity-form #repost_comment').val();
        if ('undefined' !== typeof posting_at && 'groups' === posting_at) {
          group_args = '&object=group&item_id=' + group_id;
        } // Set form data into activity ajax.

        if (typeof original_activity_id !== 'undefined' && '' !== original_activity_id && originalOptions.data.action === 'post_update') {
          var repost_content = new_content ? new_content : '';
          options.data += '&original_item_id=' + original_activity_id + '&content=repost&repost_content=' + repost_content + group_args;
        }
      });
      $(document).ajaxComplete(function (event, xhr, settings) {
        // Get ajax data.
        var setting_data = settings.data; // If it's related to spotlight, then run the script.

        if (typeof setting_data !== 'undefined' && setting_data.indexOf('original_item_id') != -1) {
          $('#repost-activity-form #original_item_id').val('');
          $('#repost-activity-form #posting_at').val('');
          $('#repost-box').hide();
          $('#rpa_group_id').hide();
        }
      });
    },
    bprpa_repost: function bprpa_repost() {
      // When we submit repost form.
      $(document).on('submit', '#repost-activity-form', function (e) {
        e.preventDefault();
        if (typeof RE_Post_Activity.theme_package_id === 'undefined') {
          return;
        } // Click if it's legacy.

        if ('legacy' === RE_Post_Activity.theme_package_id) {
          $('#aw-whats-new-submit').trigger('click');
        } else {
          // Submit, if nouveau.
          $('#whats-new-form').trigger('submit');
        }
      }); // Set data in hidden fields when we click on repost button.

      $(document).on('click', '.bp-repost-activity', function (e) {
        e.preventDefault();
        var activity_id = $(this).data('activity_id'),
          original_content = $('#activity-stream #activity-' + activity_id + ' .activity-inner').html(); // Set values in hidden fields.

        $('#repost-activity-form #original_item_id').val(activity_id); // Show content in popup which we are going to repost.

        $('#repost-activity-form .content').html(original_content);
      });
    },
    /**
     * Reset form when popup is closed.
     */
    bprpa_reset_form: function bprpa_reset_form() {
      $('#repost-box').on('click', '.close', function () {
        $('#repost-activity-form #original_item_id').val('');
        $('#repost-activity-form #posting_at').val('');
      });
    },
    /**
     * Show groups when select group from dropdown.
     */
    bprpa_show_whereto_post: function bprpa_show_whereto_post() {
      $(document).on('change', '#posting_at', function () {
        var posting_at = $(this).val(),
          group_selector = $('#rpa_group_id'); // Display group dropdown if selected group.

        if ('undefined' !== typeof posting_at && 'groups' === posting_at) {
          group_selector.show();
        } else {
          // Hide otherwise.
          group_selector.hide();
        }
      });
    },
    bprpa_show_repost_options: function () {
      $(document).on('click', '.repost-btn', function () {
        console.log($(this).next().filter('.repost-dropdown'));
        $(this).next().filter('.repost-dropdown').addClass('show');
      });
    }
  };
  $(document).on('ready', function () {
    BP_Repost.init();
  });
})(jQuery);

/***/ }),

/***/ "./assets/js/index.js":
/*!****************************!*\
  !*** ./assets/js/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./custom */ "./assets/js/custom.js");
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_custom__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modal */ "./assets/js/modal.js");
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_modal__WEBPACK_IMPORTED_MODULE_1__);



/***/ }),

/***/ "./assets/js/modal.js":
/*!****************************!*\
  !*** ./assets/js/modal.js ***!
  \****************************/
/***/ (() => {

jQuery(document).ready(function ($) {
  const modal = $("#repost-box");
  $(document).on("click", '.bp-repost-activity', function () {
    modal.show();
  });
  $(document).on("click", ".close", function () {
    $('#repost-activity-form #original_item_id').val('');
    $('#repost-activity-form #posting_at').val('');
    modal.hide();
    $('#rpa_group_id').hide();
  });
  $(document).on("click", "#bprpa-close-modal", function () {
    $('#repost-activity-form #original_item_id').val('');
    $('#repost-activity-form #posting_at').val('');
    modal.hide();
    $('#rpa_group_id').hide();
  });
});

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
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
/******/ 			"bp-repost-activity": 0,
/******/ 			"./style-bp-repost-activity": 0
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
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkbp_repost_activity"] = globalThis["webpackChunkbp_repost_activity"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["./style-bp-repost-activity"], () => (__webpack_require__("./assets/js/index.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-bp-repost-activity"], () => (__webpack_require__("./assets/css/style.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=bp-repost-activity.js.map