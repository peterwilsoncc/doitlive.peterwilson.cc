this.wp=this.wp||{},this.wp.nux=function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=203)}({0:function(e,t){!function(){e.exports=this.wp.element}()},1:function(e,t){!function(){e.exports=this.wp.i18n}()},15:function(e,t,n){"use strict";function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}n.d(t,"a",function(){return r})},17:function(e,t,n){"use strict";var r=n(34);function i(e){return function(e){if(Array.isArray(e)){for(var t=0,n=new Array(e.length);t<e.length;t++)n[t]=e[t];return n}}(e)||Object(r.a)(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}n.d(t,"a",function(){return i})},2:function(e,t){!function(){e.exports=this.lodash}()},203:function(e,t,n){"use strict";n.r(t);var r={};n.d(r,"triggerGuide",function(){return l}),n.d(r,"dismissTip",function(){return p}),n.d(r,"disableTips",function(){return d}),n.d(r,"enableTips",function(){return b});var i={};n.d(i,"getAssociatedGuide",function(){return O}),n.d(i,"isTipVisible",function(){return g}),n.d(i,"areTipsEnabled",function(){return j});var u=n(5),o=n(15),c=n(8),a=n(17);var s=Object(u.combineReducers)({areTipsEnabled:function(){var e=!(arguments.length>0&&void 0!==arguments[0])||arguments[0];switch((arguments.length>1?arguments[1]:void 0).type){case"DISABLE_TIPS":return!1;case"ENABLE_TIPS":return!0}return e},dismissedTips:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"DISMISS_TIP":return Object(c.a)({},e,Object(o.a)({},t.id,!0));case"ENABLE_TIPS":return{}}return e}}),f=Object(u.combineReducers)({guides:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"TRIGGER_GUIDE":return[].concat(Object(a.a)(e),[t.tipIds])}return e},preferences:s});function l(e){return{type:"TRIGGER_GUIDE",tipIds:e}}function p(e){return{type:"DISMISS_TIP",id:e}}function d(){return{type:"DISABLE_TIPS"}}function b(){return{type:"ENABLE_TIPS"}}var v=n(27),h=n(31),y=n(2),O=Object(h.a)(function(e,t){var n=!0,r=!1,i=void 0;try{for(var u,o=e.guides[Symbol.iterator]();!(n=(u=o.next()).done);n=!0){var c=u.value;if(Object(y.includes)(c,t)){var a=Object(y.difference)(c,Object(y.keys)(e.preferences.dismissedTips)),s=Object(v.a)(a,2),f=s[0],l=void 0===f?null:f,p=s[1];return{tipIds:c,currentTipId:l,nextTipId:void 0===p?null:p}}}}catch(e){r=!0,i=e}finally{try{n||null==o.return||o.return()}finally{if(r)throw i}}return null},function(e){return[e.guides,e.preferences.dismissedTips]});function g(e,t){if(!e.preferences.areTipsEnabled)return!1;if(Object(y.has)(e.preferences.dismissedTips,[t]))return!1;var n=O(e,t);return!n||n.currentTipId===t}function j(e){return e.preferences.areTipsEnabled}Object(u.registerStore)("core/nux",{reducer:f,actions:r,selectors:i,persist:["preferences"]});var m=n(0),T=n(6),x=n(4),w=n(1);function I(e){return e.parentNode.getBoundingClientRect()}function E(e){e.stopPropagation()}var _=Object(T.compose)(Object(u.withSelect)(function(e,t){var n=t.tipId,r=e("core/nux"),i=r.isTipVisible,u=(0,r.getAssociatedGuide)(n);return{isVisible:i(n),hasNextTip:!(!u||!u.nextTipId)}}),Object(u.withDispatch)(function(e,t){var n=t.tipId,r=e("core/nux"),i=r.dismissTip,u=r.disableTips;return{onDismiss:function(){i(n)},onDisable:function(){u()}}}))(function(e){var t=e.children,n=e.isVisible,r=e.hasNextTip,i=e.onDismiss,u=e.onDisable;return n?Object(m.createElement)(x.Popover,{className:"nux-dot-tip",position:"middle right",noArrow:!0,focusOnMount:"container",getAnchorRect:I,role:"dialog","aria-label":Object(w.__)("Editor tips"),onClick:E},Object(m.createElement)("p",null,t),Object(m.createElement)("p",null,Object(m.createElement)(x.Button,{isLink:!0,onClick:i},r?Object(w.__)("See next tip"):Object(w.__)("Got it"))),Object(m.createElement)(x.IconButton,{className:"nux-dot-tip__disable",icon:"no-alt",label:Object(w.__)("Disable tips"),onClick:u})):null});n.d(t,"DotTip",function(){return _})},27:function(e,t,n){"use strict";var r=n(37);var i=n(36);function u(e,t){return Object(r.a)(e)||function(e,t){var n=[],r=!0,i=!1,u=void 0;try{for(var o,c=e[Symbol.iterator]();!(r=(o=c.next()).done)&&(n.push(o.value),!t||n.length!==t);r=!0);}catch(e){i=!0,u=e}finally{try{r||null==c.return||c.return()}finally{if(i)throw u}}return n}(e,t)||Object(i.a)()}n.d(t,"a",function(){return u})},31:function(e,t,n){"use strict";var r,i;function u(e){return[e]}function o(e){return!!e&&"object"==typeof e}function c(){var e={clear:function(){e.head=null}};return e}function a(e,t,n){var r;if(e.length!==t.length)return!1;for(r=n;r<e.length;r++)if(e[r]!==t[r])return!1;return!0}r={},i="undefined"!=typeof WeakMap,t.a=function(e,t){var n,s;function f(){n=i?new WeakMap:c()}function l(){var n,r,i,u,o,c=arguments.length;for(u=new Array(c),i=0;i<c;i++)u[i]=arguments[i];for(o=t.apply(null,u),(n=s(o)).isUniqueByDependants||(n.lastDependants&&!a(o,n.lastDependants,0)&&n.clear(),n.lastDependants=o),r=n.head;r;){if(a(r.args,u,1))return r!==n.head&&(r.prev.next=r.next,r.next&&(r.next.prev=r.prev),r.next=n.head,r.prev=null,n.head.prev=r,n.head=r),r.val;r=r.next}return r={val:e.apply(null,u)},u[0]=null,r.args=u,n.head&&(n.head.prev=r,r.next=n.head),n.head=r,r.val}return t||(t=u),s=i?function(e){var t,i,u,a,s=n,f=!0;for(t=0;t<e.length;t++){if(!o(i=e[t])){f=!1;break}s.has(i)?s=s.get(i):(u=new WeakMap,s.set(i,u),s=u)}return s.has(r)||((a=c()).isUniqueByDependants=f,s.set(r,a)),s.get(r)}:function(){return n},l.getDependants=t,l.clear=f,f(),l}},34:function(e,t,n){"use strict";function r(e){if(Symbol.iterator in Object(e)||"[object Arguments]"===Object.prototype.toString.call(e))return Array.from(e)}n.d(t,"a",function(){return r})},36:function(e,t,n){"use strict";function r(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}n.d(t,"a",function(){return r})},37:function(e,t,n){"use strict";function r(e){if(Array.isArray(e))return e}n.d(t,"a",function(){return r})},4:function(e,t){!function(){e.exports=this.wp.components}()},5:function(e,t){!function(){e.exports=this.wp.data}()},6:function(e,t){!function(){e.exports=this.wp.compose}()},8:function(e,t,n){"use strict";n.d(t,"a",function(){return i});var r=n(15);function i(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},i=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(i=i.concat(Object.getOwnPropertySymbols(n).filter(function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable}))),i.forEach(function(t){Object(r.a)(e,t,n[t])})}return e}}});