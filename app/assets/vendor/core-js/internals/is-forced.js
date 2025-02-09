/**
 * Bundled by jsDelivr using Rollup v2.79.2 and Terser v5.37.0.
 * Original file: /npm/core-js@3.40.0/internals/is-forced.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
var t="object"==typeof document&&document.all,n=function(t){try{return!!t()}catch(t){return!0}},o=void 0===t&&void 0!==t?function(n){return"function"==typeof n||n===t}:function(t){return"function"==typeof t},r=/#|\.prototype\./,e=function(t,r){var e=c[u(t)];return e===f||e!==a&&(o(r)?n(r):!!r)},u=e.normalize=function(t){return String(t).replace(r,".").toLowerCase()},c=e.data={},a=e.NATIVE="N",f=e.POLYFILL="P",i=e;export{i as default};
