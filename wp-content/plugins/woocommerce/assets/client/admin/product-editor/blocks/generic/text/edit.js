"use strict";var __importDefault=this&&this.__importDefault||function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(exports,"__esModule",{value:!0}),exports.Edit=void 0;const element_1=require("@wordpress/element"),compose_1=require("@wordpress/compose"),i18n_1=require("@wordpress/i18n"),block_templates_1=require("@woocommerce/block-templates"),classnames_1=__importDefault(require("classnames")),components_1=require("@wordpress/components"),validation_context_1=require("../../../contexts/validation-context"),use_product_entity_prop_1=__importDefault(require("../../../hooks/use-product-entity-prop"));function Edit({attributes:e,context:{postType:t}}){const o=(0,block_templates_1.useWooBlockProps)(e),{property:r,label:n,placeholder:i,required:s,validationRegex:l,validationErrorMessage:_,minLength:a,maxLength:c}=e,[m,p]=(0,use_product_entity_prop_1.default)(r,{postType:t,fallbackValue:""}),u=(0,compose_1.useInstanceId)(components_1.BaseControl,r),{error:d,validate:f}=(0,validation_context_1.useValidation)(r,(async function(){return"string"!=typeof m?(0,i18n_1.__)("Unexpected property type assigned to field.","woocommerce"):s&&!m?(0,i18n_1.__)("This field is required.","woocommerce"):l&&!new RegExp(l).test(m)?_||(0,i18n_1.__)("Invalid value for the field.","woocommerce"):"number"==typeof a&&m.length<a?(0,i18n_1.sprintf)((0,i18n_1.__)("The minimum length of the field is %d","woocommerce"),a):"number"==typeof c&&m.length>c?(0,i18n_1.sprintf)((0,i18n_1.__)("The maximum length of the field is %d","woocommerce"),c):void 0}),[m]);return(0,element_1.createElement)("div",{...o},(0,element_1.createElement)(components_1.BaseControl,{id:u,label:s?(0,element_1.createInterpolateElement)(`${n} <required/>`,{required:(0,element_1.createElement)("span",{className:"woocommerce-product-form__required-input"},(0,i18n_1.__)("*","woocommerce"))}):n,className:(0,classnames_1.default)({"has-error":d}),help:d},(0,element_1.createElement)(components_1.__experimentalInputControl,{id:u,placeholder:i,value:m,onChange:p,onBlur:f})))}exports.Edit=Edit;