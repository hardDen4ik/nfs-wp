/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ([
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _slicedToArray)
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6);
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7);
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(8);
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(10);




function _slicedToArray(arr, i) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(arr, i) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(arr, i) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),
/* 6 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _arrayWithHoles)
/* harmony export */ });
function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

/***/ }),
/* 7 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _iterableToArrayLimit)
/* harmony export */ });
function _iterableToArrayLimit(arr, i) {
  var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];
  if (_i == null) return;
  var _arr = [];
  var _n = true;
  var _d = false;
  var _s, _e;
  try {
    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);
      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }
  return _arr;
}

/***/ }),
/* 8 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _unsupportedIterableToArray)
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9);

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
}

/***/ }),
/* 9 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _arrayLikeToArray)
/* harmony export */ });
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;
  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }
  return arr2;
}

/***/ }),
/* 10 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _nonIterableRest)
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ }),
/* 11 */
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),
/* 12 */
/***/ ((module) => {

module.exports = window["lodash"];

/***/ }),
/* 13 */
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),
/* 14 */
/***/ ((module) => {

module.exports = window["wp"]["plugins"];

/***/ }),
/* 15 */
/***/ ((module) => {

module.exports = window["wp"]["editPost"];

/***/ }),
/* 16 */
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),
/* 17 */
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),
/* 18 */
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),
/* 19 */,
/* 20 */
/***/ ((module) => {

module.exports = window["wp"]["compose"];

/***/ }),
/* 21 */
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),
/* 22 */,
/* 23 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _defineProperty)
/* harmony export */ });
function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }
  return obj;
}

/***/ }),
/* 24 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _toConsumableArray)
/* harmony export */ });
/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(25);
/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(26);
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(8);
/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(27);




function _toConsumableArray(arr) {
  return (0,_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || (0,_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__["default"])(arr) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(arr) || (0,_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),
/* 25 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _arrayWithoutHoles)
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9);

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr);
}

/***/ }),
/* 26 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _iterableToArray)
/* harmony export */ });
function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

/***/ }),
/* 27 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _nonIterableSpread)
/* harmony export */ });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ })
/******/ 	]);
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
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CustomMetaOptions": () => (/* binding */ CustomMetaOptions)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(23);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(24);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(5);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(11);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(12);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(13);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(14);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(15);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(16);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(17);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(18);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(20);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(21);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12__);




/**
 * External Dependencies
 */


/**
 * WordPress Dependencies
 */









var META_HIDE_PAGE_TITLE = 'agni_page_title_hide';
var META_PAGE_TITLE_ALIGNMENT = 'agni_page_title_align';
var META_PAGE_BG_COLOR = 'agni_page_bg_color';
var META_PAGE_BG_GRADIENT = 'agni_page_bg_gradient';
var META_REMOVE_PAGE_MARGIN = 'agni_page_margin_remove';
var META_PAGE_HEADER_SOURCE = 'agni_page_header_source';
var META_PAGE_HEADER_CHOICE = 'agni_page_header_choice';
var META_PAGE_FOOTER_CHOICE = 'agni_footer_block_id';
var META_PAGE_SIDEBAR_CHOICE = 'agni_page_sidebar_choice';
var META_PAGE_SLIDER_CHOICE = 'agni_slider_id';
var META_PRODUCT_LAYOUT_CHOICE = 'agni_product_layout_choice';
var headerOptions = [{
  value: '1',
  label: 'Builder'
}, {
  value: '2',
  label: 'Content Block'
}, {
  value: '3',
  label: 'None'
}];
function CustomMetaOptions(_ref) {
  var setPageTitle = _ref.setPageTitle,
    setPageMeta = _ref.setPageMeta;
  var _useSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_10__.useSelect)(function (select, props) {
      var _select = select('core/editor'),
        getEditedPostAttribute = _select.getEditedPostAttribute,
        getCurrentPostType = _select.getCurrentPostType;
      // console.log("core editor", getCurrentPostType());
      // console.log("It's called", getEditedPostAttribute('meta'))

      return {
        pageTitle: getEditedPostAttribute('title'),
        pageMeta: getEditedPostAttribute('meta'),
        currentPostType: getCurrentPostType()
      };
    }),
    pageTitle = _useSelect.pageTitle,
    pageMeta = _useSelect.pageMeta,
    currentPostType = _useSelect.currentPostType;
  // const [headerOptions, setHeaderOptions] = useState([]);
  var _useState = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState, 2),
    headerBuilderOptions = _useState2[0],
    setHeaderBuilderOptions = _useState2[1];
  var _useState3 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]),
    _useState4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState3, 2),
    productBuilderOptions = _useState4[0],
    setProductBuilderOptions = _useState4[1];
  var _useState5 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]),
    _useState6 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState5, 2),
    sliderOptions = _useState6[0],
    setSliderOptions = _useState6[1];
  var _useState7 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)([]),
    _useState8 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState7, 2),
    blockOptions = _useState8[0],
    setBlockOptions = _useState8[1];
  var _useState9 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(pageMeta[META_PAGE_BG_COLOR]),
    _useState10 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState9, 2),
    bgColor = _useState10[0],
    setBgColor = _useState10[1];
  var _useState11 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(pageMeta[META_PAGE_BG_GRADIENT]),
    _useState12 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState11, 2),
    bgGradient = _useState12[0],
    setBgGradient = _useState12[1];
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default()({
      path: "agni-header-builder/v1/headers",
      method: 'GET'
    }).then(function (res) {
      setHeaderBuilderOptions([{
        value: '',
        label: 'Inherit'
      }].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(lodash__WEBPACK_IMPORTED_MODULE_4___default().map(res, function (header) {
        return {
          value: header.id,
          label: lodash__WEBPACK_IMPORTED_MODULE_4___default().unescape(header.title)
        };
      }))));
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default()({
      path: "agni-slider-builder/v1/sliders",
      method: 'GET'
    }).then(function (res) {
      setSliderOptions([{
        value: '',
        label: 'Inherit'
      }].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(lodash__WEBPACK_IMPORTED_MODULE_4___default().map(res, function (slider) {
        return {
          value: slider.id,
          label: lodash__WEBPACK_IMPORTED_MODULE_4___default().unescape(slider.title)
        };
      }))));
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default()({
      path: "agni-product-builder/v1/layouts",
      method: 'GET'
    }).then(function (res) {
      setProductBuilderOptions([{
        value: '',
        label: 'Inherit'
      }].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(lodash__WEBPACK_IMPORTED_MODULE_4___default().map(res, function (_ref2) {
        var id = _ref2.id,
          title = _ref2.title;
        return {
          value: id,
          label: lodash__WEBPACK_IMPORTED_MODULE_4___default().unescape(title)
        };
      }))));
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default()({
      path: "wp/v2/agni_block?per_page=99&context=edit",
      method: 'GET'
    }).then(function (res) {
      // console.log(res)
      setBlockOptions([{
        value: '',
        label: 'Inherit'
      }].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(lodash__WEBPACK_IMPORTED_MODULE_4___default().map(res, function (block) {
        return {
          value: block.id,
          label: lodash__WEBPACK_IMPORTED_MODULE_4___default().get(block, ['title', 'raw'])
        };
      }))));
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    // console.log("page meta", pageMeta);

    if (pageMeta[META_HIDE_PAGE_TITLE] == 'on') {
      // document.querySelector(".editor-post-title").style.display = 'none';
      document.querySelector(".block-editor").classList.add('has-no-title');
    } else if (pageMeta[META_HIDE_PAGE_TITLE] == 'off') {
      // document.querySelector(".editor-post-title").style.display = 'block';
      document.querySelector(".block-editor").classList.remove('has-no-title');
    }
    if (document.querySelector(".block-editor")) {
      if (pageMeta[META_REMOVE_PAGE_MARGIN] == 'on') {
        document.querySelector(".block-editor").classList.add('has-no-margin');
      } else if (pageMeta[META_REMOVE_PAGE_MARGIN] == 'off') {
        document.querySelector(".block-editor").classList.remove('has-no-margin');
      }
    }
    if (document.querySelector(".editor-post-title")) {
      document.querySelector(".editor-post-title").style.textAlign = pageMeta[META_PAGE_TITLE_ALIGNMENT];
    }

    // const pageBgString = _.get(pageMeta, [META_PAGE_BG], {});
    // setPageBg(_.fromPairs(pageBgString.map(s => s.split(':'))));
  }, [pageMeta, pageTitle]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    // handlePageMeta(META_PAGE_BG, JSON.stringify({
    //     bg_color: bgColor,
    //     bg_gradient: bgGradient
    // }))

    handlePageMeta(META_PAGE_BG_COLOR, !lodash__WEBPACK_IMPORTED_MODULE_4___default().isUndefined(bgColor) ? bgColor : '');
    handlePageMeta(META_PAGE_BG_GRADIENT, !lodash__WEBPACK_IMPORTED_MODULE_4___default().isUndefined(bgGradient) ? bgGradient : '');
  }, [bgColor, bgGradient]);
  var handlePageTitle = function handlePageTitle(title) {
    // console.log("PAge title", title);

    setPageTitle(title);
  };
  var handlePageTitleHide = function handlePageTitleHide(value) {
    // if (value == 'on') {
    //     document.querySelector(".editor-post-title").style.display = 'none';
    // }
    // else if (value == 'off') {
    //     document.querySelector(".editor-post-title").style.display = 'block';
    // }

    handlePageMeta(META_HIDE_PAGE_TITLE, value);
  };
  var handlePageMargin = function handlePageMargin(value) {
    // if (value == 'on') {
    //     document.querySelector(".block-editor-block-list__layout").classList.add('no-margin');
    // }
    // else if (value == 'off') {
    //     document.querySelector(".block-editor-block-list__layout").classList.remove('no-margin');
    // }

    handlePageMeta(META_REMOVE_PAGE_MARGIN, value);
  };
  var handlePageMeta = function handlePageMeta(field, value) {
    // let newFieldValue = value;
    // console.log("pageMeta field", pageMeta[field])

    setPageMeta(field, value);

    // console.log("value", newFieldValue)
    // console.log("value field", pageMeta[field])
  };

  // console.log("Page Meta:", pageMeta);
  // console.log("Bg", bgColor, bgGradient);

  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7__.PluginDocumentSettingPanel, {
    className: "cartify-document-setting-panel",
    name: "cartify-document-setting-panel",
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Cartify Page Options', 'agni-cartify')
  }, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Title', 'agni-cartify'),
    value: pageTitle,
    onChange: function onChange(title) {
      return handlePageTitle(title);
    }
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Global Title Options (Inherit)', 'agni-cartify'),
    checked: lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_HIDE_PAGE_TITLE]),
    onChange: function onChange(value) {
      handlePageTitleHide(!value ? 'off' : '');
    }
  }), !lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_HIDE_PAGE_TITLE]) && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Hide Title?', 'agni-cartify'),
    checked: pageMeta[META_HIDE_PAGE_TITLE] == 'on',
    onChange: function onChange(value) {
      return handlePageTitleHide(value ? 'on' : 'off');
    }
  }), !lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_HIDE_PAGE_TITLE]) && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.RadioControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Title Alignment', 'agni-cartify'),
    options: [{
      value: 'left',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Left', 'agni-cartify')
    }, {
      value: 'center',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Center', 'agni-cartify')
    }, {
      value: 'right',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Right', 'agni-cartify')
    }],
    selected: pageMeta[META_PAGE_TITLE_ALIGNMENT],
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_TITLE_ALIGNMENT, value);
    }
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Global Margin Options (Inherit)', 'agni-cartify'),
    checked: lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_REMOVE_PAGE_MARGIN]),
    onChange: function onChange(value) {
      return handlePageMeta(META_REMOVE_PAGE_MARGIN, !value ? 'off' : '');
    }
  }), !lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_REMOVE_PAGE_MARGIN]) && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Remove Top & Bottom Margin?', 'agni-cartify'),
    checked: pageMeta[META_REMOVE_PAGE_MARGIN] == 'on',
    onChange: function onChange(value) {
      return handlePageMargin(value ? 'on' : 'off');
    }
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Global Sidebar Options (Inherit)', 'agni-cartify'),
    checked: lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_PAGE_SIDEBAR_CHOICE]),
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_SIDEBAR_CHOICE, !value ? 'no-sidebar' : '');
    }
  }), !lodash__WEBPACK_IMPORTED_MODULE_4___default().isEmpty(pageMeta[META_PAGE_SIDEBAR_CHOICE]) && /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Sidebar Placement', 'agni-cartify'),
    options: [{
      value: 'no-sidebar',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('No sidebar', 'agni-cartify')
    }, {
      value: 'left',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Left', 'agni-cartify')
    }, {
      value: 'right',
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Right', 'agni-cartify')
    }],
    value: pageMeta[META_PAGE_SIDEBAR_CHOICE],
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_SIDEBAR_CHOICE, value);
    }
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.RadioControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Header Source', 'agni-cartify'),
    selected: lodash__WEBPACK_IMPORTED_MODULE_4___default().get(pageMeta, [META_PAGE_HEADER_SOURCE]),
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_HEADER_SOURCE, value);
    },
    options: headerOptions
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Header Choice', 'agni-cartify'),
    value: pageMeta[META_PAGE_HEADER_CHOICE],
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_HEADER_CHOICE, value);
    },
    options: pageMeta[META_PAGE_HEADER_SOURCE] == '2' ? blockOptions : headerBuilderOptions
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Footer Choice', 'agni-cartify'),
    value: pageMeta[META_PAGE_FOOTER_CHOICE],
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_FOOTER_CHOICE, value);
    },
    options: [].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__["default"])(blockOptions), [{
      value: 'none',
      label: 'None'
    }])
  }), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Slider Choice', 'agni-cartify'),
    value: pageMeta[META_PAGE_SLIDER_CHOICE],
    onChange: function onChange(value) {
      return handlePageMeta(META_PAGE_SLIDER_CHOICE, value);
    },
    options: sliderOptions
  }), currentPostType == 'product' && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Product Layout Choice', 'agni-cartify'),
    value: pageMeta[META_PRODUCT_LAYOUT_CHOICE],
    onChange: function onChange(value) {
      return handlePageMeta(META_PRODUCT_LAYOUT_CHOICE, value);
    },
    options: productBuilderOptions
  })), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.HorizontalRule, null), /*#__PURE__*/React.createElement(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_9__.__experimentalColorGradientControl, {
    colorValue: lodash__WEBPACK_IMPORTED_MODULE_4___default().get(pageMeta, META_PAGE_BG_COLOR),
    gradientValue: lodash__WEBPACK_IMPORTED_MODULE_4___default().get(pageMeta, META_PAGE_BG_GRADIENT),
    onColorChange: function onColorChange(backgroundColor) {
      return setBgColor(backgroundColor);
    },
    onGradientChange: function onGradientChange(backgroundGradient) {
      return setBgGradient(backgroundGradient);
    },
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Page Background Color', 'agni-cartify')
  })));
}

// export default CustomMetaOptions;

(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__.registerPlugin)('agni-page-meta-options-panel', {
  render: (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__.compose)([(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_10__.withDispatch)(function (dispatch) {
    var setPageTitle = function setPageTitle(title) {
      dispatch('core/editor').editPost({
        title: title
      });
    };
    var setPageMeta = function setPageMeta(field, value) {
      dispatch('core/editor').editPost({
        meta: (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, field, value)
      });
    };
    return {
      setPageTitle: setPageTitle,
      setPageMeta: setPageMeta
    };
  })])(CustomMetaOptions)
});
})();

/******/ })()
;