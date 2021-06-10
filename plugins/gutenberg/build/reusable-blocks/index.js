window.wp=window.wp||{},window.wp.reusableBlocks=function(e){var t={};function n(c){if(t[c])return t[c].exports;var o=t[c]={i:c,l:!1,exports:{}};return e[c].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,c){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:c})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var c=Object.create(null);if(n.r(c),Object.defineProperty(c,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(c,o,function(t){return e[t]}.bind(null,o));return c},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=413)}({0:function(e,t){e.exports=window.wp.element},1:function(e,t){e.exports=window.wp.i18n},11:function(e,t){e.exports=window.wp.coreData},2:function(e,t){e.exports=window.lodash},20:function(e,t){e.exports=window.wp.url},28:function(e,t){e.exports=window.wp.notices},3:function(e,t){e.exports=window.wp.components},4:function(e,t){e.exports=window.wp.data},413:function(e,t,n){"use strict";n.r(t),n.d(t,"store",(function(){return j})),n.d(t,"ReusableBlocksMenuItems",(function(){return R}));var c={};n.r(c),n.d(c,"__experimentalConvertBlockToStatic",(function(){return d})),n.d(c,"__experimentalConvertBlocksToReusable",(function(){return p})),n.d(c,"__experimentalDeleteReusableBlock",(function(){return O})),n.d(c,"__experimentalSetEditingReusableBlock",(function(){return _}));var o={};n.r(o),n.d(o,"__experimentalIsEditingReusableBlock",(function(){return m}));var r=n(5),l=n(11),i=n(4),s=n(2),a=n(8),u=n(1),b={CONVERT_BLOCK_TO_STATIC:Object(i.createRegistryControl)(e=>({clientId:t})=>{const n=e.select(r.store).getBlock(t),c=e.select("core").getEditedEntityRecord("postType","wp_block",n.attributes.ref),o=Object(a.parse)(Object(s.isFunction)(c.content)?c.content(c):c.content);e.dispatch(r.store).replaceBlocks(n.clientId,o)}),CONVERT_BLOCKS_TO_REUSABLE:Object(i.createRegistryControl)(e=>async function({clientIds:t,title:n}){const c={title:n||Object(u.__)("Untitled Reusable block"),content:Object(a.serialize)(e.select(r.store).getBlocksByClientId(t)),status:"publish"},o=await e.dispatch("core").saveEntityRecord("postType","wp_block",c),l=Object(a.createBlock)("core/block",{ref:o.id});e.dispatch(r.store).replaceBlocks(t,l),e.dispatch(j).__experimentalSetEditingReusableBlock(l.clientId,!0)}),DELETE_REUSABLE_BLOCK:Object(i.createRegistryControl)(e=>async function({id:t}){if(!e.select("core").getEditedEntityRecord("postType","wp_block",t))return;const n=e.select(r.store).getBlocks().filter(e=>Object(a.isReusableBlock)(e)&&e.attributes.ref===t).map(e=>e.clientId);n.length&&e.dispatch(r.store).removeBlocks(n),await e.dispatch("core").deleteEntityRecord("postType","wp_block",t)})};function*d(e){yield function(e){return{type:"CONVERT_BLOCK_TO_STATIC",clientId:e}}(e)}function*p(e,t){yield function(e,t){return{type:"CONVERT_BLOCKS_TO_REUSABLE",clientIds:e,title:t}}(e,t)}function*O(e){yield function(e){return{type:"DELETE_REUSABLE_BLOCK",id:e}}(e)}function _(e,t){return{type:"SET_EDITING_REUSABLE_BLOCK",clientId:e,isEditing:t}}var f=Object(i.combineReducers)({isEditingReusableBlock:function(e={},t){return"SET_EDITING_REUSABLE_BLOCK"===(null==t?void 0:t.type)?{...e,[t.clientId]:t.isEditing}:e}});function m(e,t){return e.isEditingReusableBlock[t]}const j=Object(i.createReduxStore)("core/reusable-blocks",{actions:c,controls:b,reducer:f,selectors:o});Object(i.register)(j);var E=n(0),w=n(3),k=n(6),y=Object(E.createElement)(k.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},Object(E.createElement)(k.Path,{d:"M7 7.2h8.2L13.5 9l1.1 1.1 3.6-3.6-3.5-4-1.1 1 1.9 2.3H7c-.9 0-1.7.3-2.3.9-1.4 1.5-1.4 4.2-1.4 5.6v.2h1.5v-.3c0-1.1 0-3.5 1-4.5.3-.3.7-.5 1.2-.5zm13.8 4V11h-1.5v.3c0 1.1 0 3.5-1 4.5-.3.3-.7.5-1.3.5H8.8l1.7-1.7-1.1-1.1L5.9 17l3.5 4 1.1-1-1.9-2.3H17c.9 0 1.7-.3 2.3-.9 1.5-1.4 1.5-4.2 1.5-5.6z"})),B=n(28);function C({clientIds:e,rootClientId:t}){const[n,c]=Object(E.useState)(!1),[o,s]=Object(E.useState)(""),b=Object(i.useSelect)(n=>{var c;const{canUser:o}=n(l.store),{getBlocksByClientId:i,canInsertBlockType:s}=n(r.store),u=null!==(c=i(e))&&void 0!==c?c:[];return!(1===u.length&&u[0]&&Object(a.isReusableBlock)(u[0])&&n(l.store).getEntityRecord("postType","wp_block",u[0].attributes.ref))&&s("core/block",t)&&u.every(e=>!!e&&e.isValid&&Object(a.hasBlockSupport)(e.name,"reusable",!0))&&!!o("create","blocks")},[e]),{__experimentalConvertBlocksToReusable:d}=Object(i.useDispatch)(j),{createSuccessNotice:p,createErrorNotice:O}=Object(i.useDispatch)(B.store),_=Object(E.useCallback)((async function(t){try{await d(e,t),p(Object(u.__)("Reusable block created."),{type:"snackbar"})}catch(e){O(e.message,{type:"snackbar"})}}),[e]);return b?Object(E.createElement)(r.BlockSettingsMenuControls,null,({onClose:e})=>Object(E.createElement)(E.Fragment,null,Object(E.createElement)(w.MenuItem,{icon:y,onClick:()=>{c(!0)}},Object(u.__)("Add to Reusable blocks")),n&&Object(E.createElement)(w.Modal,{title:Object(u.__)("Create Reusable block"),closeLabel:Object(u.__)("Close"),onRequestClose:()=>{c(!1),s("")},overlayClassName:"reusable-blocks-menu-items__convert-modal"},Object(E.createElement)("form",{onSubmit:t=>{t.preventDefault(),_(o),c(!1),s(""),e()}},Object(E.createElement)(w.TextControl,{label:Object(u.__)("Name"),value:o,onChange:s}),Object(E.createElement)(w.Flex,{className:"reusable-blocks-menu-items__convert-modal-actions",justify:"flex-end"},Object(E.createElement)(w.FlexItem,null,Object(E.createElement)(w.Button,{variant:"secondary",onClick:()=>{c(!1),s("")}},Object(u.__)("Cancel"))),Object(E.createElement)(w.FlexItem,null,Object(E.createElement)(w.Button,{variant:"primary",type:"submit"},Object(u.__)("Save")))))))):null}var g=n(20),v=function({clientId:e}){const{isVisible:t}=Object(i.useSelect)(t=>{const{getBlock:n}=t(r.store),{canUser:c}=t(l.store),o=n(e);return{isVisible:!!o&&Object(a.isReusableBlock)(o)&&!!c("update","blocks",o.attributes.ref)}},[e]),{__experimentalConvertBlockToStatic:n}=Object(i.useDispatch)(j);return t?Object(E.createElement)(r.BlockSettingsMenuControls,null,Object(E.createElement)(w.MenuItem,{href:Object(g.addQueryArgs)("edit.php",{post_type:"wp_block"})},Object(u.__)("Manage Reusable blocks")),Object(E.createElement)(w.MenuItem,{onClick:()=>n(e)},Object(u.__)("Convert to regular blocks"))):null},R=Object(i.withSelect)(e=>{const{getSelectedBlockClientIds:t}=e(r.store);return{clientIds:t()}})((function({clientIds:e,rootClientId:t}){return Object(E.createElement)(E.Fragment,null,Object(E.createElement)(C,{clientIds:e,rootClientId:t}),1===e.length&&Object(E.createElement)(v,{clientId:e[0]}))}))},5:function(e,t){e.exports=window.wp.blockEditor},6:function(e,t){e.exports=window.wp.primitives},8:function(e,t){e.exports=window.wp.blocks}});