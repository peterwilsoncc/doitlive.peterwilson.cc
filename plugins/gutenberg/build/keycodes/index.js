this.wp=this.wp||{},this.wp.keycodes=function(t){var n={};function r(e){if(n[e])return n[e].exports;var u=n[e]={i:e,l:!1,exports:{}};return t[e].call(u.exports,u,u.exports,r),u.l=!0,u.exports}return r.m=t,r.c=n,r.d=function(t,n,e){r.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:e})},r.r=function(t){Object.defineProperty(t,"__esModule",{value:!0})},r.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(n,"a",n),n},r.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},r.p="",r(r.s=198)}({1:function(t,n){!function(){t.exports=this.lodash}()},198:function(t,n,r){"use strict";r.r(n);var e=r(26),u=r(25),c=r(1);function o(){var t=(arguments.length>0&&void 0!==arguments[0]?arguments[0]:window).navigator.platform;return-1!==t.indexOf("Mac")||Object(c.includes)(["iPad","iPhone"],t)}r.d(n,"BACKSPACE",function(){return i}),r.d(n,"TAB",function(){return a}),r.d(n,"ENTER",function(){return f}),r.d(n,"ESCAPE",function(){return d}),r.d(n,"SPACE",function(){return l}),r.d(n,"LEFT",function(){return s}),r.d(n,"UP",function(){return b}),r.d(n,"RIGHT",function(){return p}),r.d(n,"DOWN",function(){return O}),r.d(n,"DELETE",function(){return j}),r.d(n,"F10",function(){return h}),r.d(n,"ALT",function(){return v}),r.d(n,"CTRL",function(){return y}),r.d(n,"COMMAND",function(){return m}),r.d(n,"SHIFT",function(){return A}),r.d(n,"rawShortcut",function(){return w}),r.d(n,"displayShortcutList",function(){return E}),r.d(n,"displayShortcut",function(){return S}),r.d(n,"isKeyboardEvent",function(){return P});var i=8,a=9,f=13,d=27,l=32,s=37,b=38,p=39,O=40,j=46,h=121,v="alt",y="ctrl",m="meta",A="shift",g={primary:function(t){return t()?[m]:[y]},primaryShift:function(t){return t()?[A,m]:[y,A]},primaryAlt:function(t){return t()?[v,m]:[y,v]},secondary:function(t){return t()?[A,v,m]:[y,A,v]},access:function(t){return t()?[y,v]:[A,v]},ctrl:function(){return[y]},ctrlShift:function(){return[y,A]},shift:function(){return[A]},shiftAlt:function(){return[A,v]}},w=Object(c.mapValues)(g,function(t){return function(n){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:o;return Object(u.a)(t(r)).concat([n.toLowerCase()]).join("+")}}),E=Object(c.mapValues)(g,function(t){return function(n){var r,i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:o,a=i(),f=(r={},Object(e.a)(r,v,a?"⌥":"Alt"),Object(e.a)(r,y,a?"^":"Ctrl"),Object(e.a)(r,m,"⌘"),Object(e.a)(r,A,a?"⇧":"Shift"),r),d=t(i).reduce(function(t,n){var r=Object(c.get)(f,n,n);return a?Object(u.a)(t).concat([r]):Object(u.a)(t).concat([r,"+"])},[]),l=Object(c.capitalize)(n);return Object(u.a)(d).concat([l])}}),S=Object(c.mapValues)(E,function(t){return function(n){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:o;return t(n,r).join("")}}),P=Object(c.mapValues)(g,function(t){return function(n,r){var e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:o,u=t(e);return!!u.every(function(t){return n["".concat(t,"Key")]})&&(r?n.key===r:Object(c.includes)(u,n.key.toLowerCase()))}})},25:function(t,n,r){"use strict";var e=r(46);function u(t){return function(t){if(Array.isArray(t)){for(var n=0,r=new Array(t.length);n<t.length;n++)r[n]=t[n];return r}}(t)||Object(e.a)(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}r.d(n,"a",function(){return u})},26:function(t,n,r){"use strict";function e(t,n,r){return n in t?Object.defineProperty(t,n,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[n]=r,t}r.d(n,"a",function(){return e})},46:function(t,n,r){"use strict";function e(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}r.d(n,"a",function(){return e})}});