(()=>{var e;function r(e,r){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);r&&(n=n.filter((function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable}))),t.push.apply(t,n)}return t}function t(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?r(Object(o),!0).forEach((function(r){n(e,r,o[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):r(Object(o)).forEach((function(r){Object.defineProperty(e,r,Object.getOwnPropertyDescriptor(o,r))}))}return e}function n(e,r,t){return r in e?Object.defineProperty(e,r,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[r]=t,e}function o(e){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},o(e)}window.senna=t(t({},null!==(e=window.senna)&&void 0!==e?e:{}),{},{helpers:{mergeDeep:function e(r,t){var n=function(e){return e&&"object"===o(e)};return n(r)&&n(t)?(Object.keys(t).forEach((function(o){var c=r[o],i=t[o];Array.isArray(c)&&Array.isArray(i)?r[o]=c.concat(i):n(c)&&n(i)?r[o]=e(Object.assign({},c),i):r[o]=i})),r):t}}})})();