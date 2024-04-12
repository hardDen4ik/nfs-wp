/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ([
/* 0 */,
/* 1 */,
/* 2 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _extends)
/* harmony export */ });
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}

/***/ }),
/* 3 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _objectWithoutProperties)
/* harmony export */ });
/* harmony import */ var _objectWithoutPropertiesLoose_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4);

function _objectWithoutProperties(source, excluded) {
  if (source == null) return {};
  var target = (0,_objectWithoutPropertiesLoose_js__WEBPACK_IMPORTED_MODULE_0__["default"])(source, excluded);
  var key, i;
  if (Object.getOwnPropertySymbols) {
    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
    for (i = 0; i < sourceSymbolKeys.length; i++) {
      key = sourceSymbolKeys[i];
      if (excluded.indexOf(key) >= 0) continue;
      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
      target[key] = source[key];
    }
  }
  return target;
}

/***/ }),
/* 4 */
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _objectWithoutPropertiesLoose)
/* harmony export */ });
function _objectWithoutPropertiesLoose(source, excluded) {
  if (source == null) return {};
  var target = {};
  var sourceKeys = Object.keys(source);
  var key, i;
  for (i = 0; i < sourceKeys.length; i++) {
    key = sourceKeys[i];
    if (excluded.indexOf(key) >= 0) continue;
    target[key] = source[key];
  }
  return target;
}

/***/ }),
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
/* 19 */
/***/ ((module) => {

module.exports = window["wp"]["coreData"];

/***/ }),
/* 20 */
/***/ ((module) => {

module.exports = window["wp"]["compose"];

/***/ }),
/* 21 */
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

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
/* harmony export */   "PluginPostStatusInfoTest": () => (/* binding */ PluginPostStatusInfoTest)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/* harmony import */ var _babel_runtime_helpers_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(3);
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
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(19);
/* harmony import */ var _wordpress_core_data__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_core_data__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(20);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(21);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13__);



var _excluded = ["instanceId", "value", "label", "info"];

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

// _featured
// _visibility

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
var visibilityOptions = {
  visible: {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Shop and search results'),
    info: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Visible to everyone.')
  },
  catalog: {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Shop only'),
    info: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Only visible to site admins and editors.')
  },
  search: {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Search results only'),
    info: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Only those with the password can view this post.')
  },
  hidden: {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Hidden'),
    info: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Only those with the password can view this post.')
  }
};
function PluginPostStatusInfoTest(_ref) {
  var currentProductId = _ref.currentProductId;
  var instanceId = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_12__.useInstanceId)(PluginPostStatusInfoTest);

  // console.log("Current product id", currentProductId);
  // let isFeatured = 'yet to set';
  // if (!_.isUndefined(currentProductId)) {
  // isFeatured = useEntityRecord('postType', 'customProduct', currentProductId);
  // const isFeatured = useEntityRecord('postType', 'page', 11240);
  // }

  // const visibility = 'visible';
  // console.log("current featured", Promise.all(currentFeatured).then(res => {
  //     return res.data
  // }))
  var _useState = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('visible'),
    _useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState, 2),
    catalogVisibility = _useState2[0],
    setCatalogVisibility = _useState2[1];
  var _useState3 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false),
    _useState4 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState3, 2),
    isFeatured = _useState4[0],
    setIsFeatured = _useState4[1];
  // const isFeatured = false;
  // const [currentVisibility, setCurrentVisibility] = useState('');

  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default()({
      path: "wc/v3/products/".concat(currentProductId),
      method: 'GET'
    }).then(function (res) {
      // console.log("featured", res.featured);
      // setCurrentlyFeatured(res.featured);
      if (isFeatured !== res.featured) {
        console.log("featured", res.featured);
        // setIsChanged(true);
        setIsFeatured(res.featured);
      }
      console.log("visibility", res.catalog_visibility);
      if (catalogVisibility !== res.catalog_visibility) {
        setCatalogVisibility(res.catalog_visibility);
      }
      // setIsFeatured(res.featured)
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    // if (isChanged) {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default()({
      path: "wc/v3/products/".concat(currentProductId),
      method: 'POST',
      data: {
        featured: isFeatured
      }
    }).then(function (res) {
      console.log("res post", res);
      // setIsChanged(false);
      // setFeaturedProduct(isFeatured);
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
    // }
  }, [isFeatured]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_13___default()({
      path: "wc/v3/products/".concat(currentProductId),
      method: 'POST',
      data: {
        catalog_visibility: catalogVisibility
      }
    }).then(function (res) {
      console.log("res post", res);
      // setIsChanged(false);
      // setFeaturedProduct(isFeatured);
    })["catch"](function (err) {
      return console.log(err);
    })["finally"](function () {});
  }, [catalogVisibility]);
  var _useSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_10__.useSelect)(function (select, props) {
      //     const { getEditedPostAttribute, getCurrentPostType, getEditedPostVisibility, getCurrentPostAttribute, getTerms, getCurrentPostId } = select('core/editor');
      var _select = select('core'),
        getMedia = _select.getMedia,
        getUser = _select.getUser,
        getEntityRecords = _select.getEntityRecords,
        getEntityRecord = _select.getEntityRecord,
        getEditedEntityRecord = _select.getEditedEntityRecord,
        getEntityConfig = _select.getEntityConfig;

      // console.log("get entity record", getEntityRecord('postType', 'customProduct', currentProductId));

      //     const currentProduct = getEntityRecord('postType', 'customProduct', getCurrentPostId());
      //     const isFeatured = _.get(currentProduct, ['featured']);

      //     // console.log("core editor", select('core/editor'));
      //     // console.log("block editro", select('core/block-editor'));
      //     console.log("core", select('core'), getEntityRecord('postType', 'customProduct', getCurrentPostId()));
      //     // console.log("core tax", select('core').getTaxonomies(), select('core').getTaxonomy('product_cat'))

      //     // console.log("taxs", getEntityRecords('product_cat'));
      //     // console.log("edit enntiry", getEditedEntityRecord(), getEntityConfig);
      //     // console.log("get terms", getTerms);
      //     // console.log("core current post type", getCurrentPostType());
      //     // console.log("It's current attribute", getCurrentPostAttribute('meta')['agni_product_layout_choice'], getCurrentPostAttribute('product_visiblity'))
      //     // console.log("It's called", getEditedPostAttribute('meta'), getEditedPostAttribute('taxonomy'))
      //     // console.log("It's ", getEditedPostVisibility())

      return {
        productId: currentProductId
        //         // isFeatured: isFeatured,
        //         visibility: 'visible'
      };
    }),
    productId = _useSelect.productId;
  var handlePageMeta = function handlePageMeta(field, value) {
    setPageMeta(field, value);
  };
  console.log('is featured', isFeatured);
  // console.log("first")
  var _useState5 = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(true),
    _useState6 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState5, 2),
    isActive = _useState6[0],
    setIsActive = _useState6[1];
  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7__.PluginPostStatusInfo, null, /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('This is a featured product', 'agni-cartify'),
    checked: isFeatured,
    onChange: function onChange(value) {
      // console.log("set featured to ", value);

      // setFeaturedProduct(productId, value)
      // setIsChanged(true);
      setIsFeatured(value);
    }
  })), /*#__PURE__*/React.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_7__.PluginPostStatusInfo, null, /*#__PURE__*/React.createElement("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Catalog visibility', 'agni-cartify')), /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.Dropdown, {
    contentClassName: "edit-post-post-visibility__dialog",
    position: "bottom left",
    renderToggle: function renderToggle(_ref2) {
      var isOpen = _ref2.isOpen,
        onToggle = _ref2.onToggle;
      return /*#__PURE__*/React.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_8__.Button, {
        variant: "tertiary",
        onClick: onToggle,
        "aria-expanded": isOpen
      }, lodash__WEBPACK_IMPORTED_MODULE_4___default().get(visibilityOptions, [catalogVisibility, 'label']));
    },
    renderContent: function renderContent() {
      return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("fieldset", {
        className: "editor-post-visibility__dialog-fieldset"
      }, /*#__PURE__*/React.createElement("legend", {
        className: "editor-post-visibility__dialog-legend"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Catalog Visibility')), /*#__PURE__*/React.createElement(PostVisibilityChoice, {
        instanceId: instanceId,
        value: "visible",
        label: visibilityOptions.visible.label,
        info: visibilityOptions.visible.info,
        onChange: function onChange() {
          return setCatalogVisibility("visible");
        },
        checked: catalogVisibility === 'visible'
      }), /*#__PURE__*/React.createElement(PostVisibilityChoice, {
        instanceId: instanceId,
        value: "catalog",
        label: visibilityOptions.catalog.label,
        info: visibilityOptions.catalog.info,
        onChange: function onChange() {
          return setCatalogVisibility("catalog");
        },
        checked: catalogVisibility === 'catalog'
      }), /*#__PURE__*/React.createElement(PostVisibilityChoice, {
        instanceId: instanceId,
        value: "search",
        label: visibilityOptions.search.label,
        info: visibilityOptions.search.info,
        onChange: function onChange() {
          return setCatalogVisibility("search");
        },
        checked: catalogVisibility == 'search'
      }), /*#__PURE__*/React.createElement(PostVisibilityChoice, {
        instanceId: instanceId,
        value: "hidden",
        label: visibilityOptions.hidden.label,
        info: visibilityOptions.hidden.info,
        onChange: function onChange() {
          return setCatalogVisibility("hidden");
        },
        checked: catalogVisibility == 'hidden'
      })));
    }
  })));
}
;
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__.registerPlugin)('post-status-info-test', {
  render: (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_12__.compose)([(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_10__.withDispatch)(function (dispatch) {
    // const addProductEntity = (productId) => {
    //     dispatch('core').addEntities([
    //         {
    //             name: 'customProduct',
    //             kind: 'postType',
    //             baseURL: `wc/v3/products/${productId}`
    //         }
    //     ])
    // }

    var setFeaturedProduct = function setFeaturedProduct(isFeatured) {
      console.log("edit post dispatched");
      // dispatch('core/editor').savePost();
      // dispatch('core/editor').editPost(
      //     { featured: isFeatured }
      // );
    };

    // const setFeaturedProduct = (productId, isFeatured) => {

    //     dispatch('core').editEntityRecord(
    //         'postType',
    //         'customProduct',
    //         productId,
    //         { 'featured': isFeatured }
    //     )

    //     dispatch('core').saveEditedEntityRecord(
    //         'postType',
    //         'customProduct',
    //         productId
    //     )
    // }

    var setCatalogVisibility = function setCatalogVisibility(productId, visibility) {
      console.log("catalog", visibility);
      dispatch('core').editEntityRecord('postType', 'customProduct', productId, {
        'catalog_visibility': visibility
      });
      dispatch('core').saveEditedEntityRecord('postType', 'customProduct', productId);
    };
    return {
      setFeaturedProduct: setFeaturedProduct,
      setCatalogVisibility: setCatalogVisibility
    };
  }), (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_10__.withSelect)(function (select) {
    var currentProductId = select('core/editor').getCurrentPostId();
    // const [isCurrentlyFeatured, setIsCurrentlyFeatured] = useState();
    // const currentFeatured = async () => {
    //     return await apiFetch({
    //         path: `wc/v3/products/${currentProductId}`,
    //         method: 'GET',

    //     })
    //         .then((res) => {
    //             console.log("featured select", res.featured);
    //             // setCurrentlyFeatured(res.featured);

    //             return res.featured;
    //         })
    //         .catch((err) => console.log(err))
    //         .finally(() => {

    //         })
    // }
    // Promise.all(currentFeatured).then(res => {
    //     setIsCurrentlyFeatured(res.data);
    //     // return res.data;
    // })
    // console.log("current product with select", currentProductId);

    // const { getEntityRecord } = select('core');
    // console.log('Use entity', useEntityRecord('postType', 'customProduct', currentProductId));
    // console.log("get entity record with select", getEntityRecord('postType', 'product', currentProductId));
    // console.log("get entity record with select embed", getEntityRecord('postType', 'product', currentProductId, { id: currentProductId, _embed: true }));
    return {
      currentProductId: currentProductId
    };
  })])(PluginPostStatusInfoTest)
});
function PostVisibilityChoice(_ref3) {
  var instanceId = _ref3.instanceId,
    value = _ref3.value,
    label = _ref3.label,
    info = _ref3.info,
    props = (0,_babel_runtime_helpers_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_1__["default"])(_ref3, _excluded);
  return /*#__PURE__*/React.createElement("div", {
    className: "editor-post-visibility__dialog-choice"
  }, /*#__PURE__*/React.createElement("input", (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
    type: "radio",
    name: "editor-post-visibility__dialog-setting-".concat(instanceId),
    value: value,
    id: "editor-post-".concat(value, "-").concat(instanceId),
    "aria-describedby": "editor-post-".concat(value, "-").concat(instanceId, "-description"),
    className: "editor-post-visibility__dialog-radio"
  }, props)), /*#__PURE__*/React.createElement("label", {
    htmlFor: "editor-post-".concat(value, "-").concat(instanceId),
    className: "editor-post-visibility__dialog-label"
  }, label), /*#__PURE__*/React.createElement("p", {
    id: "editor-post-".concat(value, "-").concat(instanceId, "-description"),
    className: "editor-post-visibility__dialog-info"
  }, info));
}

// export function CustomMetaOptions({ setPageTitle, setPageMeta }) {

//     const { pageTitle, pageMeta, currentPostType } = useSelect((select, props) => {

//         const { getEditedPostAttribute, getCurrentPostType } = select('core/editor');
//         // console.log("core editor", getCurrentPostType());
//         // console.log("It's called", getEditedPostAttribute('meta'))

//         return {
//             pageTitle: getEditedPostAttribute('title'),
//             pageMeta: getEditedPostAttribute('meta'),
//             currentPostType: getCurrentPostType(),
//         }
//     })
//     // const [headerOptions, setHeaderOptions] = useState([]);
//     const [headerBuilderOptions, setHeaderBuilderOptions] = useState([]);
//     const [productBuilderOptions, setProductBuilderOptions] = useState([]);
//     const [sliderOptions, setSliderOptions] = useState([])
//     const [blockOptions, setBlockOptions] = useState([])
//     const [bgColor, setBgColor] = useState(pageMeta[META_PAGE_BG_COLOR]);
//     const [bgGradient, setBgGradient] = useState(pageMeta[META_PAGE_BG_GRADIENT]);

//     useEffect(() => {

//         apiFetch({
//             path: `agni-header-builder/v1/headers`,
//             method: 'GET',

//         })
//             .then((res) => {
//                 setHeaderBuilderOptions([{ value: '', label: 'Inherit' }, ..._.map(res, header => {
//                     return {
//                         value: header.id,
//                         label: _.unescape(header.title)
//                     }
//                 })])
//             })
//             .catch((err) => console.log(err))
//             .finally(() => {

//             })

//         apiFetch({
//             path: `agni-slider-builder/v1/sliders`,
//             method: 'GET',

//         })
//             .then((res) => {
//                 setSliderOptions([{ value: '', label: 'Inherit' }, ..._.map(res, slider => {
//                     return {
//                         value: slider.id,
//                         label: _.unescape(slider.title)
//                     }
//                 })])
//             })
//             .catch((err) => console.log(err))
//             .finally(() => {

//             })

//         apiFetch({
//             path: `agni-product-builder/v1/layouts`,
//             method: 'GET',

//         })
//             .then((res) => {
//                 setProductBuilderOptions([{ value: '', label: 'Inherit' }, ..._.map(res, ({ id, title }) => {
//                     return {
//                         value: id,
//                         label: _.unescape(title)
//                     }
//                 })])
//             })
//             .catch((err) => console.log(err))
//             .finally(() => {

//             })

//         apiFetch({
//             path: `wp/v2/agni_block?per_page=99&context=edit`,
//             method: 'GET',

//         })
//             .then((res) => {
//                 // console.log(res)
//                 setBlockOptions([{ value: '', label: 'Inherit' }, ..._.map(res, block => {
//                     return {
//                         value: block.id,
//                         label: _.get(block, ['title', 'raw'])
//                     }
//                 })])
//             })
//             .catch((err) => console.log(err))
//             .finally(() => {

//             })

//     }, [])

//     useEffect(() => {
//         // console.log("page meta", pageMeta);

//         if (pageMeta[META_HIDE_PAGE_TITLE] == 'on') {
//             // document.querySelector(".editor-post-title").style.display = 'none';
//             document.querySelector(".block-editor").classList.add('has-no-title');
//         }
//         else if (pageMeta[META_HIDE_PAGE_TITLE] == 'off') {
//             // document.querySelector(".editor-post-title").style.display = 'block';
//             document.querySelector(".block-editor").classList.remove('has-no-title');
//         }

//         if (document.querySelector(".block-editor")) {
//             if (pageMeta[META_REMOVE_PAGE_MARGIN] == 'on') {
//                 document.querySelector(".block-editor").classList.add('has-no-margin');
//             }
//             else if (pageMeta[META_REMOVE_PAGE_MARGIN] == 'off') {
//                 document.querySelector(".block-editor").classList.remove('has-no-margin');
//             }
//         }

//         if (document.querySelector(".editor-post-title")) {
//             document.querySelector(".editor-post-title").style.textAlign = pageMeta[META_PAGE_TITLE_ALIGNMENT];
//         }

//         // const pageBgString = _.get(pageMeta, [META_PAGE_BG], {});
//         // setPageBg(_.fromPairs(pageBgString.map(s => s.split(':'))));

//     }, [pageMeta, pageTitle])

//     useEffect(() => {
//         // handlePageMeta(META_PAGE_BG, JSON.stringify({
//         //     bg_color: bgColor,
//         //     bg_gradient: bgGradient
//         // }))

//         handlePageMeta(META_PAGE_BG_COLOR, !_.isUndefined(bgColor) ? bgColor : '');
//         handlePageMeta(META_PAGE_BG_GRADIENT, !_.isUndefined(bgGradient) ? bgGradient : '');
//     }, [bgColor, bgGradient])

//     const handlePageTitle = (title) => {
//         // console.log("PAge title", title);

//         setPageTitle(title);
//     }

//     const handlePageTitleHide = (value) => {
//         // if (value == 'on') {
//         //     document.querySelector(".editor-post-title").style.display = 'none';
//         // }
//         // else if (value == 'off') {
//         //     document.querySelector(".editor-post-title").style.display = 'block';
//         // }

//         handlePageMeta(META_HIDE_PAGE_TITLE, value)
//     }

//     const handlePageMargin = (value) => {
//         // if (value == 'on') {
//         //     document.querySelector(".block-editor-block-list__layout").classList.add('no-margin');
//         // }
//         // else if (value == 'off') {
//         //     document.querySelector(".block-editor-block-list__layout").classList.remove('no-margin');
//         // }

//         handlePageMeta(META_REMOVE_PAGE_MARGIN, value)
//     }

//     const handlePageMeta = (field, value) => {
//         // let newFieldValue = value;
//         // console.log("pageMeta field", pageMeta[field])

//         setPageMeta(field, value);

//         // console.log("value", newFieldValue)
//         // console.log("value field", pageMeta[field])
//     }

//     // console.log("Page Meta:", pageMeta);
//     // console.log("Bg", bgColor, bgGradient);

//     return <>
//         <PluginDocumentSettingPanel className="cartify-document-setting-panel" name="cartify-document-setting-panel" title={__('Cartify Page Options', 'agni-cartify')}>
//             <TextControl
//                 label={__('Title', 'agni-cartify')}
//                 value={pageTitle}
//                 onChange={(title) => handlePageTitle(title)}
//             />
//             <HorizontalRule />

//             {/* <BaseControl label={'Display Title'}> */}
//             <ToggleControl
//                 label={__('Global Title Options (Inherit)', 'agni-cartify')}
//                 checked={_.isEmpty(pageMeta[META_HIDE_PAGE_TITLE])}
//                 onChange={(value) => { handlePageTitleHide(!value ? 'off' : '') }}
//             />
//             {/* </BaseControl> */}
//             {!_.isEmpty(pageMeta[META_HIDE_PAGE_TITLE]) && <ToggleControl
//                 label={__('Hide Title?', 'agni-cartify')}
//                 checked={pageMeta[META_HIDE_PAGE_TITLE] == 'on'}
//                 onChange={(value) => handlePageTitleHide(value ? 'on' : 'off')}
//             />}

//             {/* <ToggleControl
//                 label={__('Inherit', 'agni-cartify')}
//                 checked={_.isEmpty(pageMeta[META_PAGE_TITLE_ALIGNMENT])}
//                 onChange={(value) => handlePageMeta(META_PAGE_TITLE_ALIGNMENT, !value ? 'left' : '')}
//             /> */}
//             {!_.isEmpty(pageMeta[META_HIDE_PAGE_TITLE]) && <RadioControl
//                 label={__('Title Alignment', 'agni-cartify')}
//                 options={[
//                     { value: 'left', label: __('Left', 'agni-cartify') },
//                     { value: 'center', label: __('Center', 'agni-cartify') },
//                     { value: 'right', label: __('Right', 'agni-cartify') }
//                 ]}
//                 selected={pageMeta[META_PAGE_TITLE_ALIGNMENT]}
//                 onChange={(value) => handlePageMeta(META_PAGE_TITLE_ALIGNMENT, value)}
//             />}
//             <HorizontalRule />
//             {/* <ColorGradientControl
//                 colorValue={_.get(pageMeta, [META_PAGE_BG, 'bg_color'], '')}
//                 gradientValue={_.get(pageMeta, [META_PAGE_BG, 'bg_gradient'], '')}
//                 onColorChange={(backgroundColor) => handlePageMeta(META_PAGE_BG, { bg_color: backgroundColor })}
//                 onGradientChange={(backgroundGradient) => handlePageMeta(META_PAGE_BG, { bg_gradient: backgroundGradient })}
//                 label={__('Background Color', 'agni-cartify')}
//             /> */}

//             <ToggleControl
//                 label={__('Global Margin Options (Inherit)', 'agni-cartify')}
//                 checked={_.isEmpty(pageMeta[META_REMOVE_PAGE_MARGIN])}
//                 onChange={(value) => handlePageMeta(META_REMOVE_PAGE_MARGIN, !value ? 'off' : '')}
//             />
//             {!_.isEmpty(pageMeta[META_REMOVE_PAGE_MARGIN]) && <ToggleControl
//                 label={__('Remove Top & Bottom Margin?', 'agni-cartify')}
//                 checked={pageMeta[META_REMOVE_PAGE_MARGIN] == 'on'}
//                 onChange={(value) => handlePageMargin(value ? 'on' : 'off')}
//             />}
//             <HorizontalRule />
//             <ToggleControl
//                 label={__('Global Sidebar Options (Inherit)', 'agni-cartify')}
//                 checked={_.isEmpty(pageMeta[META_PAGE_SIDEBAR_CHOICE])}
//                 onChange={(value) => handlePageMeta(META_PAGE_SIDEBAR_CHOICE, !value ? 'no-sidebar' : '')}
//             />
//             {!_.isEmpty(pageMeta[META_PAGE_SIDEBAR_CHOICE]) && <SelectControl
//                 label={__('Sidebar Placement', 'agni-cartify')}
//                 options={[
//                     { value: 'no-sidebar', label: __('No sidebar', 'agni-cartify') },
//                     { value: 'left', label: __('Left', 'agni-cartify') },
//                     { value: 'right', label: __('Right', 'agni-cartify') }
//                 ]}
//                 value={pageMeta[META_PAGE_SIDEBAR_CHOICE]}
//                 onChange={(value) => handlePageMeta(META_PAGE_SIDEBAR_CHOICE, value)}
//             />}
//             <HorizontalRule />
//             <RadioControl
//                 label={__('Header Source', 'agni-cartify')}
//                 selected={_.get(pageMeta, [META_PAGE_HEADER_SOURCE])}
//                 onChange={(value) => handlePageMeta(META_PAGE_HEADER_SOURCE, value)}
//                 options={headerOptions}
//             />
//             <SelectControl
//                 label={__('Header Choice', 'agni-cartify')}
//                 value={pageMeta[META_PAGE_HEADER_CHOICE]}
//                 onChange={(value) => handlePageMeta(META_PAGE_HEADER_CHOICE, value)}
//                 options={pageMeta[META_PAGE_HEADER_SOURCE] == '2' ? blockOptions : headerBuilderOptions}
//             />
//             <HorizontalRule />
//             <SelectControl
//                 label={__('Footer Choice', 'agni-cartify')}
//                 value={pageMeta[META_PAGE_FOOTER_CHOICE]}
//                 onChange={(value) => handlePageMeta(META_PAGE_FOOTER_CHOICE, value)}
//                 options={[...blockOptions, { value: 'none', label: 'None' }]}
//             />
//             <HorizontalRule />
//             <SelectControl
//                 label={__('Slider Choice', 'agni-cartify')}
//                 value={pageMeta[META_PAGE_SLIDER_CHOICE]}
//                 onChange={(value) => handlePageMeta(META_PAGE_SLIDER_CHOICE, value)}
//                 options={sliderOptions}
//             />
//             {(currentPostType == 'product') && <>
//                 <HorizontalRule />
//                 <SelectControl
//                     label={__('Product Layout Choice', 'agni-cartify')}
//                     value={pageMeta[META_PRODUCT_LAYOUT_CHOICE]}
//                     onChange={(value) => handlePageMeta(META_PRODUCT_LAYOUT_CHOICE, value)}
//                     options={productBuilderOptions}
//                 />
//             </>}
//             <HorizontalRule />
//             <ColorGradientControl
//                 colorValue={_.get(pageMeta, META_PAGE_BG_COLOR)}
//                 gradientValue={_.get(pageMeta, META_PAGE_BG_GRADIENT)}
//                 onColorChange={(backgroundColor) => setBgColor(backgroundColor)}
//                 onGradientChange={(backgroundGradient) => setBgGradient(backgroundGradient)}
//                 label={__('Page Background Color', 'agni-cartify')}
//             />
//         </PluginDocumentSettingPanel>
//     </>
// }

// // export default CustomMetaOptions;

// registerPlugin('agni-page-meta-options-panel', {
//     render: compose([
//         withDispatch((dispatch) => {
//             const setPageTitle = (title) => {
//                 dispatch('core/editor').editPost(
//                     { title }
//                 );
//             }
//             const setPageMeta = (field, value) => {
//                 dispatch('core/editor').editPost(
//                     { meta: { [field]: value } }
//                 );
//             }
//             return { setPageTitle, setPageMeta }
//         })
//     ])(CustomMetaOptions)
// });
})();

/******/ })()
;