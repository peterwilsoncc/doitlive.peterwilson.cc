this.wp=this.wp||{},this.wp.dom=function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=199)}({19:function(e,t,n){"use strict";var r=n(32);function o(e){return function(e){if(Array.isArray(e)){for(var t=0,n=new Array(e.length);t<e.length;t++)n[t]=e[t];return n}}(e)||Object(r.a)(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}n.d(t,"a",function(){return o})},199:function(e,t,n){"use strict";n.r(t);var r={};n.d(r,"find",function(){return c});var o={};n.d(o,"isTabbableIndex",function(){return d}),n.d(o,"find",function(){return p});var i=n(19),a=["[tabindex]","a[href]","button:not([disabled])",'input:not([type="hidden"]):not([disabled])',"select:not([disabled])","textarea:not([disabled])","iframe","object","embed","area[href]","[contenteditable]:not([contenteditable=false])"].join(",");function u(e){return e.offsetWidth>0||e.offsetHeight>0||e.getClientRects().length>0}function c(e){var t=e.querySelectorAll(a);return Object(i.a)(t).filter(function(e){return!!u(e)&&("AREA"!==e.nodeName||function(e){var t=e.closest("map[name]");if(!t)return!1;var n=document.querySelector('img[usemap="#'+t.name+'"]');return!!n&&u(n)}(e))})}function l(e){var t=e.getAttribute("tabindex");return null===t?0:parseInt(t,10)}function d(e){return-1!==l(e)}function f(e,t){return{element:e,index:t}}function s(e){return e.element}function g(e,t){var n=l(e.element),r=l(t.element);return n===r?e.index-t.index:n-r}function p(e){return c(e).filter(d).map(f).sort(g).map(s)}var v=n(2),m=window.getComputedStyle,h=window.Node,C=h.TEXT_NODE,b=h.ELEMENT_NODE,N=h.DOCUMENT_POSITION_PRECEDING,E=h.DOCUMENT_POSITION_FOLLOWING;function R(e,t){if(Object(v.includes)(["INPUT","TEXTAREA"],e.tagName))return e.selectionStart===e.selectionEnd&&(t?0===e.selectionStart:e.value.length===e.selectionStart);if(!e.isContentEditable)return!0;var n=window.getSelection(),r=n.getRangeAt(0).cloneRange();n.isCollapsed||r.collapse(!function(e){var t=e.anchorNode,n=e.focusNode,r=e.anchorOffset,o=e.focusOffset,i=t.compareDocumentPosition(n);return!(i&N)&&(!!(i&E)||0!==i||r<=o)}(n));var o,i=r.startContainer;if(o=t?0:i.nodeValue?i.nodeValue.length:i.childNodes.length,r["".concat(t?"start":"end","Offset")]!==o)return!1;for(var a=t?"first":"last";i!==e;){var u=i.parentNode;if(u["".concat(a,"Child")]!==i)return!1;i=u}return!0}function w(e,t){if(Object(v.includes)(["INPUT","TEXTAREA"],e.tagName))return R(e,t);if(!e.isContentEditable)return!0;var n=window.getSelection(),r=n.rangeCount?n.getRangeAt(0):null;if(!r)return!1;var o=A(r);if(!o)return!1;var i=o.height/2,a=e.getBoundingClientRect();return!(t&&o.top-i>a.top)&&!(!t&&o.bottom+i<a.bottom)}function A(e){if(!e.collapsed)return e.getBoundingClientRect();var t=e.getClientRects()[0];if(!t){var n=document.createTextNode("​");e.insertNode(n),t=e.getClientRects()[0],n.parentNode.removeChild(n)}return t}function T(e){if(e.isContentEditable){var t=window.getSelection(),n=t.rangeCount?t.getRangeAt(0):null;if(n)return A(n)}}function O(e,t){if(e){if(Object(v.includes)(["INPUT","TEXTAREA"],e.tagName))return e.focus(),void(t?(e.selectionStart=e.value.length,e.selectionEnd=e.value.length):(e.selectionStart=0,e.selectionEnd=0));if(e.focus(),e.isContentEditable){var n=e[t?"lastChild":"firstChild"];if(n){var r=window.getSelection(),o=document.createRange();o.selectNodeContents(n),o.collapse(!t),r.removeAllRanges(),r.addRange(o)}}}}function S(e,t,n,r){r.style.zIndex="10000";var o=function(e,t,n){if(e.caretRangeFromPoint)return e.caretRangeFromPoint(t,n);if(!e.caretPositionFromPoint)return null;var r=e.caretPositionFromPoint(t,n);if(!r)return null;var o=e.createRange();return o.setStart(r.offsetNode,r.offset),o.collapse(!0),o}(e,t,n);return r.style.zIndex=null,o}function y(e,t,n){var r=!(arguments.length>3&&void 0!==arguments[3])||arguments[3];if(e)if(n&&e.isContentEditable){var o=n.height/2,i=e.getBoundingClientRect(),a=n.left,u=t?i.bottom-o:i.top+o,c=S(document,a,u,e);if(!c||!e.contains(c.startContainer))return!r||c&&c.startContainer&&c.startContainer.contains(e)?void O(e,t):(e.scrollIntoView(t),void y(e,t,n,!1));if(c.startContainer.nodeType===C){var l=c.startContainer.parentNode,d=l.getBoundingClientRect(),f=t?"bottom":"top",s=parseInt(m(l).getPropertyValue("padding-".concat(f)),10)||0,g=t?d.bottom-s-o:d.top+s+o;u!==g&&(c=S(document,a,g,e))}var p=window.getSelection();p.removeAllRanges(),p.addRange(c),e.focus(),p.removeAllRanges(),p.addRange(c)}else O(e,t)}function P(e){try{var t=e.nodeName,n=e.selectionStart,r=e.contentEditable;return"INPUT"===t&&null!==n||"TEXTAREA"===t||"true"===r}catch(e){return!1}}function x(){if(P(document.activeElement))return!0;var e=window.getSelection(),t=e.rangeCount?e.getRangeAt(0):null;return t&&!t.collapsed}function I(e){if(Object(v.includes)(["INPUT","TEXTAREA"],e.nodeName))return 0===e.selectionStart&&e.value.length===e.selectionEnd;if(!e.isContentEditable)return!0;var t=window.getSelection(),n=t.rangeCount?t.getRangeAt(0):null;if(!n)return!0;var r=n.startContainer,o=n.endContainer,i=n.startOffset,a=n.endOffset;if(r===e&&o===e&&0===i&&a===e.childNodes.length)return!0;var u=e.lastChild,c=u.nodeType===C?u.data.length:u.childNodes.length;return r===e.firstChild&&o===e.lastChild&&0===i&&a===c}function j(e){if(e){if(e.scrollHeight>e.clientHeight){var t=window.getComputedStyle(e).overflowY;if(/(auto|scroll)/.test(t))return e}return j(e.parentNode)}}function _(e){for(var t;(t=e.parentNode)&&t.nodeType!==b;);return t?"static"!==m(t).position?t:t.offsetParent:null}function B(e,t){F(t,e.parentNode),D(e)}function D(e){e.parentNode.removeChild(e)}function F(e,t){t.parentNode.insertBefore(e,t.nextSibling)}function U(e){for(var t=e.parentNode;e.firstChild;)t.insertBefore(e.firstChild,e);t.removeChild(e)}function H(e,t){for(var n=e.ownerDocument.createElement(t);e.firstChild;)n.appendChild(e.firstChild);return e.parentNode.replaceChild(n,e),n}function V(e,t){t.parentNode.insertBefore(e,t),e.appendChild(t)}n.d(t,"focus",function(){return X}),n.d(t,"isHorizontalEdge",function(){return R}),n.d(t,"isVerticalEdge",function(){return w}),n.d(t,"getRectangleFromRange",function(){return A}),n.d(t,"computeCaretRect",function(){return T}),n.d(t,"placeCaretAtHorizontalEdge",function(){return O}),n.d(t,"placeCaretAtVerticalEdge",function(){return y}),n.d(t,"isTextField",function(){return P}),n.d(t,"documentHasSelection",function(){return x}),n.d(t,"isEntirelySelected",function(){return I}),n.d(t,"getScrollContainer",function(){return j}),n.d(t,"getOffsetParent",function(){return _}),n.d(t,"replace",function(){return B}),n.d(t,"remove",function(){return D}),n.d(t,"insertAfter",function(){return F}),n.d(t,"unwrap",function(){return U}),n.d(t,"replaceTag",function(){return H}),n.d(t,"wrap",function(){return V});var X={focusable:r,tabbable:o}},2:function(e,t){!function(){e.exports=this.lodash}()},32:function(e,t,n){"use strict";function r(e){if(Symbol.iterator in Object(e)||"[object Arguments]"===Object.prototype.toString.call(e))return Array.from(e)}n.d(t,"a",function(){return r})}});