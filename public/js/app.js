//alert 提示框
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.Sweetalert2=t()}(this,function(){"use strict";var e={title:"",titleText:"",text:"",html:"",type:null,customClass:"",target:"body",animation:!0,allowOutsideClick:!0,allowEscapeKey:!0,allowEnterKey:!0,showConfirmButton:!0,showCancelButton:!1,preConfirm:null,confirmButtonText:"OK",confirmButtonAriaLabel:"",confirmButtonColor:"#3085d6",confirmButtonClass:null,cancelButtonText:"Cancel",cancelButtonAriaLabel:"",cancelButtonColor:"#aaa",cancelButtonClass:null,buttonsStyling:!0,reverseButtons:!1,focusConfirm:!0,focusCancel:!1,showCloseButton:!1,closeButtonAriaLabel:"Close this dialog",showLoaderOnConfirm:!1,imageUrl:null,imageWidth:null,imageHeight:null,imageAlt:"",imageClass:null,timer:null,width:500,padding:20,background:"#fff",input:null,inputPlaceholder:"",inputValue:"",inputOptions:{},inputAutoTrim:!0,inputClass:null,inputAttributes:{},inputValidator:null,grow:!1,position:"center",progressSteps:[],currentProgressStep:null,progressStepsDistance:"40px",onBeforeOpen:null,onOpen:null,onClose:null,useRejections:!0},t=function(e){var t={};for(var n in e)t[e[n]]="swal2-"+e[n];return t},n=t(["container","shown","iosfix","modal","overlay","fade","show","hide","noanimation","close","title","content","buttonswrapper","confirm","cancel","icon","image","input","file","range","select","radio","checkbox","textarea","inputerror","validationerror","progresssteps","activeprogressstep","progresscircle","progressline","loading","styled","top","top-left","top-right","center","center-left","center-right","bottom","bottom-left","bottom-right","grow-row","grow-column","grow-fullscreen"]),o=t(["success","warning","info","question","error"]),r=function(e,t){(e=String(e).replace(/[^0-9a-f]/gi,"")).length<6&&(e=e[0]+e[0]+e[1]+e[1]+e[2]+e[2]),t=t||0;for(var n="#",o=0;o<3;o++){var r=parseInt(e.substr(2*o,2),16);n+=("00"+(r=Math.round(Math.min(Math.max(0,r+r*t),255)).toString(16))).substr(r.length)}return n},i=function(e){console.warn("SweetAlert2: "+e)},a=function(e){console.error("SweetAlert2: "+e)},l={previousWindowKeyDown:null,previousActiveElement:null,previousBodyPadding:null},s=function(e){var t=c();t&&t.parentNode.removeChild(t);{if("undefined"!=typeof document){var o=document.createElement("div");o.className=n.container,o.innerHTML=u;("string"==typeof e.target?document.querySelector(e.target):e.target).appendChild(o);var r=d(),i=E(r,n.input),l=E(r,n.file),s=r.querySelector("."+n.range+" input"),p=r.querySelector("."+n.range+" output"),f=E(r,n.select),m=r.querySelector("."+n.checkbox+" input"),g=E(r,n.textarea);return i.oninput=function(){I.resetValidationError()},l.onchange=function(){I.resetValidationError()},s.oninput=function(){I.resetValidationError(),p.value=s.value},s.onchange=function(){I.resetValidationError(),s.previousSibling.value=s.value},f.onchange=function(){I.resetValidationError()},m.onchange=function(){I.resetValidationError()},g.oninput=function(){I.resetValidationError()},r}a("SweetAlert2 requires document to initialize")}},u=('\n <div role="dialog" aria-modal="true" aria-labelledby="'+n.title+'" aria-describedby="'+n.content+'" class="'+n.modal+'" tabindex="-1">\n   <ul class="'+n.progresssteps+'"></ul>\n   <div class="'+n.icon+" "+o.error+'">\n     <span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span>\n   </div>\n   <div class="'+n.icon+" "+o.question+'">?</div>\n   <div class="'+n.icon+" "+o.warning+'">!</div>\n   <div class="'+n.icon+" "+o.info+'">i</div>\n   <div class="'+n.icon+" "+o.success+'">\n     <div class="swal2-success-circular-line-left"></div>\n     <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>\n     <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>\n     <div class="swal2-success-circular-line-right"></div>\n   </div>\n   <img class="'+n.image+'" />\n   <h2 class="'+n.title+'" id="'+n.title+'"></h2>\n   <div id="'+n.content+'" class="'+n.content+'"></div>\n   <input class="'+n.input+'" />\n   <input type="file" class="'+n.file+'" />\n   <div class="'+n.range+'">\n     <output></output>\n     <input type="range" />\n   </div>\n   <select class="'+n.select+'"></select>\n   <div class="'+n.radio+'"></div>\n   <label for="'+n.checkbox+'" class="'+n.checkbox+'">\n     <input type="checkbox" />\n   </label>\n   <textarea class="'+n.textarea+'"></textarea>\n   <div class="'+n.validationerror+'" id="'+n.validationerror+'"></div>\n   <div class="'+n.buttonswrapper+'">\n     <button type="button" class="'+n.confirm+'">OK</button>\n     <button type="button" class="'+n.cancel+'">Cancel</button>\n   </div>\n   <button type="button" class="'+n.close+'">×</button>\n </div>\n').replace(/(^|\n)\s*/g,""),c=function(){return document.body.querySelector("."+n.container)},d=function(){return c()?c().querySelector("."+n.modal):null},p=function(e){return c()?c().querySelector("."+e):null},f=function(){return p(n.title)},m=function(){return p(n.content)},g=function(){return p(n.image)},b=function(){return p(n.progresssteps)},v=function(){return p(n.validationerror)},h=function(){return p(n.confirm)},y=function(){return p(n.cancel)},w=function(){return p(n.buttonswrapper)},C=function(){return p(n.close)},k=function(){var e=Array.from(d().querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])')).sort(function(e,t){return e=parseInt(e.getAttribute("tabindex")),t=parseInt(t.getAttribute("tabindex")),e>t?1:e<t?-1:0}),t=Array.prototype.slice.call(d().querySelectorAll('button, input:not([type=hidden]), textarea, select, a, [tabindex="0"]'));return function(e){var t=[];for(var n in e)-1===t.indexOf(e[n])&&t.push(e[n]);return t}(e.concat(t))},x=function(e,t){return!!e.classList&&e.classList.contains(t)},S=function(e){if(e.focus(),"file"!==e.type){var t=e.value;e.value="",e.value=t}},A=function(e,t){if(e&&t){t.split(/\s+/).filter(Boolean).forEach(function(t){e.classList.add(t)})}},B=function(e,t){if(e&&t){t.split(/\s+/).filter(Boolean).forEach(function(t){e.classList.remove(t)})}},E=function(e,t){for(var n=0;n<e.childNodes.length;n++)if(x(e.childNodes[n],t))return e.childNodes[n]},P=function(e,t){t||(t="block"),e.style.opacity="",e.style.display=t},L=function(e){e.style.opacity="",e.style.display="none"},T=function(e){return e.offsetWidth||e.offsetHeight||e.getClientRects().length},q=function(){var e=document.createElement("div"),t={WebkitAnimation:"webkitAnimationEnd",OAnimation:"oAnimationEnd oanimationend",animation:"animationend"};for(var n in t)if(t.hasOwnProperty(n)&&void 0!==e.style[n])return t[n];return!1}(),V="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},O=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(e[o]=n[o])}return e},M=O({},e),N=[];"undefined"==typeof Promise&&a("This package requires a Promise library, please include a shim to enable it in this browser (See: https://github.com/limonte/sweetalert2/wiki/Migration-from-SweetAlert-to-SweetAlert2#1-ie-support)");var H=function(e){("string"==typeof e.target&&!document.querySelector(e.target)||"string"!=typeof e.target&&!e.target.appendChild)&&(i('Target parameter is not valid, defaulting to "body"'),e.target="body");var t=void 0,r=d(),l="string"==typeof e.target?document.querySelector(e.target):e.target;t=r&&l&&r.parentNode!==l.parentNode?s(e):r||s(e);for(var u in e)I.isValidParameter(u)||i('Unknown parameter "'+u+'"');t.style.width="number"==typeof e.width?e.width+"px":e.width,t.style.padding=e.padding+"px",t.style.background=e.background;for(var p=t.querySelectorAll("[class^=swal2-success-circular-line], .swal2-success-fix"),v=0;v<p.length;v++)p[v].style.background=e.background;var k=c(),x=f(),S=m(),E=w(),T=h(),q=y(),O=C();if(e.titleText?x.innerText=e.titleText:x.innerHTML=e.title.split("\n").join("<br />"),e.text||e.html){if("object"===V(e.html))if(S.innerHTML="",0 in e.html)for(var M=0;M in e.html;M++)S.appendChild(e.html[M].cloneNode(!0));else S.appendChild(e.html.cloneNode(!0));else e.html?S.innerHTML=e.html:e.text&&(S.textContent=e.text);P(S)}else L(S);if(e.position in n&&A(k,n[e.position]),e.grow&&"string"==typeof e.grow){var N="grow-"+e.grow;N in n&&A(k,n[N])}e.showCloseButton?(O.setAttribute("aria-label",e.closeButtonAriaLabel),P(O)):L(O),t.className=n.modal,e.customClass&&A(t,e.customClass);var H=b(),j=parseInt(null===e.currentProgressStep?I.getQueueStep():e.currentProgressStep,10);e.progressSteps.length?(P(H),function(e){for(;e.firstChild;)e.removeChild(e.firstChild)}(H),j>=e.progressSteps.length&&i("Invalid currentProgressStep parameter, it should be less than progressSteps.length (currentProgressStep like JS arrays starts from 0)"),e.progressSteps.forEach(function(t,o){var r=document.createElement("li");if(A(r,n.progresscircle),r.innerHTML=t,o===j&&A(r,n.activeprogressstep),H.appendChild(r),o!==e.progressSteps.length-1){var i=document.createElement("li");A(i,n.progressline),i.style.width=e.progressStepsDistance,H.appendChild(i)}})):L(H);for(var R=d().querySelectorAll("."+n.icon),U=0;U<R.length;U++)L(R[U]);if(e.type){var W=!1;for(var D in o)if(e.type===D){W=!0;break}if(!W)return a("Unknown alert type: "+e.type),!1;var K=t.querySelector("."+n.icon+"."+o[e.type]);if(P(K),e.animation)switch(e.type){case"success":A(K,"swal2-animate-success-icon"),A(K.querySelector(".swal2-success-line-tip"),"swal2-animate-success-line-tip"),A(K.querySelector(".swal2-success-line-long"),"swal2-animate-success-line-long");break;case"error":A(K,"swal2-animate-error-icon"),A(K.querySelector(".swal2-x-mark"),"swal2-animate-x-mark")}}var z=g();e.imageUrl?(z.setAttribute("src",e.imageUrl),z.setAttribute("alt",e.imageAlt),P(z),e.imageWidth?z.setAttribute("width",e.imageWidth):z.removeAttribute("width"),e.imageHeight?z.setAttribute("height",e.imageHeight):z.removeAttribute("height"),z.className=n.image,e.imageClass&&A(z,e.imageClass)):L(z),e.showCancelButton?q.style.display="inline-block":L(q),e.showConfirmButton?function(e,t){e.style.removeProperty?e.style.removeProperty(t):e.style.removeAttribute(t)}(T,"display"):L(T),e.showConfirmButton||e.showCancelButton?P(E):L(E),T.innerHTML=e.confirmButtonText,q.innerHTML=e.cancelButtonText,T.setAttribute("aria-label",e.confirmButtonAriaLabel),q.setAttribute("aria-label",e.cancelButtonAriaLabel),e.buttonsStyling&&(T.style.backgroundColor=e.confirmButtonColor,q.style.backgroundColor=e.cancelButtonColor),T.className=n.confirm,A(T,e.confirmButtonClass),q.className=n.cancel,A(q,e.cancelButtonClass),e.buttonsStyling?(A(T,n.styled),A(q,n.styled)):(B(T,n.styled),B(q,n.styled),T.style.backgroundColor=T.style.borderLeftColor=T.style.borderRightColor="",q.style.backgroundColor=q.style.borderLeftColor=q.style.borderRightColor=""),!0===e.animation?B(t,n.noanimation):A(t,n.noanimation),e.showLoaderOnConfirm&&!e.preConfirm&&i("showLoaderOnConfirm is set to true, but preConfirm is not defined.\nshowLoaderOnConfirm should be used together with preConfirm, see usage example:\nhttps://limonte.github.io/sweetalert2/#ajax-request")},j=function(){null===l.previousBodyPadding&&document.body.scrollHeight>window.innerHeight&&(l.previousBodyPadding=document.body.style.paddingRight,document.body.style.paddingRight=function(){if("ontouchstart"in window||navigator.msMaxTouchPoints)return 0;var e=document.createElement("div");e.style.width="50px",e.style.height="50px",e.style.overflow="scroll",document.body.appendChild(e);var t=e.offsetWidth-e.clientWidth;return document.body.removeChild(e),t}()+"px")},R=function(){if(/iPad|iPhone|iPod/.test(navigator.userAgent)&&!window.MSStream&&!x(document.body,n.iosfix)){var e=document.body.scrollTop;document.body.style.top=-1*e+"px",A(document.body,n.iosfix)}},I=function e(){for(var t=arguments.length,o=Array(t),i=0;i<t;i++)o[i]=arguments[i];if(void 0===o[0])return a("SweetAlert2 expects at least 1 attribute!"),!1;var s=O({},M);switch(V(o[0])){case"string":s.title=o[0],s.html=o[1],s.type=o[2];break;case"object":O(s,o[0]),s.extraParams=o[0].extraParams,"email"===s.input&&null===s.inputValidator&&(s.inputValidator=function(e){return new Promise(function(t,n){/^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(e)?t():n("Invalid email address")})}),"url"===s.input&&null===s.inputValidator&&(s.inputValidator=function(e){return new Promise(function(t,n){/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/.test(e)?t():n("Invalid URL")})});break;default:return a('Unexpected type of argument! Expected "string" or "object", got '+V(o[0])),!1}H(s);var u=c(),p=d();return new Promise(function(t,o){s.timer&&(p.timeout=setTimeout(function(){e.closeModal(s.onClose),s.useRejections?o("timer"):t({dismiss:"timer"})},s.timer));var i=function(e){if(!(e=e||s.input))return null;switch(e){case"select":case"textarea":case"file":return E(p,n[e]);case"checkbox":return p.querySelector("."+n.checkbox+" input");case"radio":return p.querySelector("."+n.radio+" input:checked")||p.querySelector("."+n.radio+" input:first-child");case"range":return p.querySelector("."+n.range+" input");default:return E(p,n.input)}};s.input&&setTimeout(function(){var e=i();e&&S(e)},0);for(var O=function(n){s.showLoaderOnConfirm&&e.showLoading(),s.preConfirm?s.preConfirm(n,s.extraParams).then(function(o){e.closeModal(s.onClose),t(o||n)},function(t){e.hideLoading(),t&&e.showValidationError(t)}):(e.closeModal(s.onClose),t(s.useRejections?n:{value:n}))},M=function(n){var a=n||window.event,l=a.target||a.srcElement,u=h(),c=y(),d=u&&(u===l||u.contains(l)),p=c&&(c===l||c.contains(l));switch(a.type){case"mouseover":case"mouseup":s.buttonsStyling&&(d?u.style.backgroundColor=r(s.confirmButtonColor,-.1):p&&(c.style.backgroundColor=r(s.cancelButtonColor,-.1)));break;case"mouseout":s.buttonsStyling&&(d?u.style.backgroundColor=s.confirmButtonColor:p&&(c.style.backgroundColor=s.cancelButtonColor));break;case"mousedown":s.buttonsStyling&&(d?u.style.backgroundColor=r(s.confirmButtonColor,-.2):p&&(c.style.backgroundColor=r(s.cancelButtonColor,-.2)));break;case"click":if(d&&e.isVisible())if(e.disableButtons(),s.input){var f=function(){var e=i();if(!e)return null;switch(s.input){case"checkbox":return e.checked?1:0;case"radio":return e.checked?e.value:null;case"file":return e.files.length?e.files[0]:null;default:return s.inputAutoTrim?e.value.trim():e.value}}();s.inputValidator?(e.disableInput(),s.inputValidator(f,s.extraParams).then(function(){e.enableButtons(),e.enableInput(),O(f)},function(t){e.enableButtons(),e.enableInput(),t&&e.showValidationError(t)})):O(f)}else O(!0);else p&&e.isVisible()&&(e.disableButtons(),e.closeModal(s.onClose),s.useRejections?o("cancel"):t({dismiss:"cancel"}))}},N=p.querySelectorAll("button"),I=0;I<N.length;I++)N[I].onclick=M,N[I].onmouseover=M,N[I].onmouseout=M,N[I].onmousedown=M;C().onclick=function(){e.closeModal(s.onClose),s.useRejections?o("close"):t({dismiss:"close"})},u.onclick=function(n){n.target===u&&s.allowOutsideClick&&(e.closeModal(s.onClose),s.useRejections?o("overlay"):t({dismiss:"overlay"}))};var U=w(),W=h(),D=y();s.reverseButtons?W.parentNode.insertBefore(D,W):W.parentNode.insertBefore(W,D);var K=function(e,t){for(var n=k(s.focusCancel),o=0;o<n.length;o++){(e+=t)===n.length?e=0:-1===e&&(e=n.length-1);var r=n[e];if(T(r))return r.focus()}},z=function(n){var r=n||window.event;if("Enter"===r.key){if(r.target===i()){if("textarea"===r.target.tagName.toLowerCase())return;e.clickConfirm(),r.preventDefault()}}else if("Tab"===r.key){for(var a=r.target||r.srcElement,l=k(s.focusCancel),u=-1,c=0;c<l.length;c++)if(a===l[c]){u=c;break}r.shiftKey?K(u,-1):K(u,1),r.stopPropagation(),r.preventDefault()}else-1!==["ArrowLeft","ArrowRight","ArrowUp","Arrowdown"].indexOf(r.key)?document.activeElement===W&&T(D)?D.focus():document.activeElement===D&&T(W)&&W.focus():"Escape"!==r.key&&"Esc"!==r.key||!0!==s.allowEscapeKey||(e.closeModal(s.onClose),s.useRejections?o("esc"):t({dismiss:"esc"}))};window.onkeydown&&window.onkeydown.toString()===z.toString()||(l.previousWindowKeyDown=window.onkeydown,window.onkeydown=z),s.buttonsStyling&&(W.style.borderLeftColor=s.confirmButtonColor,W.style.borderRightColor=s.confirmButtonColor),e.hideLoading=e.disableLoading=function(){s.showConfirmButton||(L(W),s.showCancelButton||L(w())),B(U,n.loading),B(p,n.loading),p.removeAttribute("aria-busy"),W.disabled=!1,D.disabled=!1},e.getTitle=function(){return f()},e.getContent=function(){return m()},e.getInput=function(){return i()},e.getImage=function(){return g()},e.getButtonsWrapper=function(){return w()},e.getConfirmButton=function(){return h()},e.getCancelButton=function(){return y()},e.enableButtons=function(){W.disabled=!1,D.disabled=!1},e.disableButtons=function(){W.disabled=!0,D.disabled=!0},e.enableConfirmButton=function(){W.disabled=!1},e.disableConfirmButton=function(){W.disabled=!0},e.enableInput=function(){var e=i();if(!e)return!1;if("radio"===e.type)for(var t=e.parentNode.parentNode.querySelectorAll("input"),n=0;n<t.length;n++)t[n].disabled=!1;else e.disabled=!1},e.disableInput=function(){var e=i();if(!e)return!1;if(e&&"radio"===e.type)for(var t=e.parentNode.parentNode.querySelectorAll("input"),n=0;n<t.length;n++)t[n].disabled=!0;else e.disabled=!0},e.showValidationError=function(e){var t=v();t.innerHTML=e,P(t);var o=i();o&&(o.setAttribute("aria-invalid",!0),o.setAttribute("aria-describedBy",n.validationerror),S(o),A(o,n.inputerror))},e.resetValidationError=function(){var e=v();L(e);var t=i();t&&(t.removeAttribute("aria-invalid"),t.removeAttribute("aria-describedBy"),B(t,n.inputerror))},e.getProgressSteps=function(){return s.progressSteps},e.setProgressSteps=function(e){s.progressSteps=e,H(s)},e.showProgressSteps=function(){P(b())},e.hideProgressSteps=function(){L(b())},e.enableButtons(),e.hideLoading(),e.resetValidationError();for(var Z=["input","file","range","select","radio","checkbox","textarea"],Q=void 0,Y=0;Y<Z.length;Y++){var _=n[Z[Y]],$=E(p,_);if(Q=i(Z[Y])){for(var J in Q.attributes)if(Q.attributes.hasOwnProperty(J)){var X=Q.attributes[J].name;"type"!==X&&"value"!==X&&Q.removeAttribute(X)}for(var F in s.inputAttributes)Q.setAttribute(F,s.inputAttributes[F])}$.className=_,s.inputClass&&A($,s.inputClass),L($)}var G=void 0;switch(s.input){case"text":case"email":case"password":case"number":case"tel":case"url":(Q=E(p,n.input)).value=s.inputValue,Q.placeholder=s.inputPlaceholder,Q.type=s.input,P(Q);break;case"file":(Q=E(p,n.file)).placeholder=s.inputPlaceholder,Q.type=s.input,P(Q);break;case"range":var ee=E(p,n.range),te=ee.querySelector("input"),ne=ee.querySelector("output");te.value=s.inputValue,te.type=s.input,ne.value=s.inputValue,P(ee);break;case"select":var oe=E(p,n.select);if(oe.innerHTML="",s.inputPlaceholder){var re=document.createElement("option");re.innerHTML=s.inputPlaceholder,re.value="",re.disabled=!0,re.selected=!0,oe.appendChild(re)}G=function(e){for(var t in e){var n=document.createElement("option");n.value=t,n.innerHTML=e[t],s.inputValue.toString()===t&&(n.selected=!0),oe.appendChild(n)}P(oe),oe.focus()};break;case"radio":var ie=E(p,n.radio);ie.innerHTML="",G=function(e){for(var t in e){var o=document.createElement("input"),r=document.createElement("label"),i=document.createElement("span");o.type="radio",o.name=n.radio,o.value=t,s.inputValue.toString()===t&&(o.checked=!0),i.innerHTML=e[t],r.appendChild(o),r.appendChild(i),r.for=o.id,ie.appendChild(r)}P(ie);var a=ie.querySelectorAll("input");a.length&&a[0].focus()};break;case"checkbox":var ae=E(p,n.checkbox),le=i("checkbox");le.type="checkbox",le.value=1,le.id=n.checkbox,le.checked=Boolean(s.inputValue);var se=ae.getElementsByTagName("span");se.length&&ae.removeChild(se[0]),(se=document.createElement("span")).innerHTML=s.inputPlaceholder,ae.appendChild(se),P(ae);break;case"textarea":var ue=E(p,n.textarea);ue.value=s.inputValue,ue.placeholder=s.inputPlaceholder,P(ue);break;case null:break;default:a('Unexpected type of input! Expected "text", "email", "password", "number", "tel", "select", "radio", "checkbox", "textarea", "file" or "url", got "'+s.input+'"')}"select"!==s.input&&"radio"!==s.input||(s.inputOptions instanceof Promise?(e.showLoading(),s.inputOptions.then(function(t){e.hideLoading(),G(t)})):"object"===V(s.inputOptions)?G(s.inputOptions):a("Unexpected type of inputOptions! Expected object or Promise, got "+V(s.inputOptions))),function(e,t,o){var r=c(),i=d();null!==t&&"function"==typeof t&&t(i),e?(A(i,n.show),A(r,n.fade),B(i,n.hide)):B(i,n.fade),P(i),r.style.overflowY="hidden",q&&!x(i,n.noanimation)?i.addEventListener(q,function e(){i.removeEventListener(q,e),r.style.overflowY="auto"}):r.style.overflowY="auto",A(document.documentElement,n.shown),A(document.body,n.shown),A(r,n.shown),j(),R(),l.previousActiveElement=document.activeElement,null!==o&&"function"==typeof o&&setTimeout(function(){o(i)})}(s.animation,s.onBeforeOpen,s.onOpen),s.allowEnterKey?s.focusCancel&&T(D)?D.focus():s.focusConfirm&&T(W)?W.focus():K(-1,1):document.activeElement&&document.activeElement.blur(),c().scrollTop=0})};return I.isVisible=function(){return!!d()},I.queue=function(e){N=e;var t=function(){N=[],document.body.removeAttribute("data-swal2-queue-step")},n=[];return new Promise(function(e,o){!function r(i,a){i<N.length?(document.body.setAttribute("data-swal2-queue-step",i),I(N[i]).then(function(e){n.push(e),r(i+1,a)},function(e){t(),o(e)})):(t(),e(n))}(0)})},I.getQueueStep=function(){return document.body.getAttribute("data-swal2-queue-step")},I.insertQueueStep=function(e,t){return t&&t<N.length?N.splice(t,0,e):N.push(e)},I.deleteQueueStep=function(e){void 0!==N[e]&&N.splice(e,1)},I.close=I.closeModal=function(e){var t=c(),o=d();if(o){B(o,n.show),A(o,n.hide),clearTimeout(o.timeout),function(){if(window.onkeydown=l.previousWindowKeyDown,l.previousActiveElement&&l.previousActiveElement.focus){var e=window.scrollX,t=window.scrollY;l.previousActiveElement.focus(),e&&t&&window.scrollTo(e,t)}}();var r=function(){t.parentNode&&t.parentNode.removeChild(t),B(document.documentElement,n.shown),B(document.body,n.shown),null!==l.previousBodyPadding&&(document.body.style.paddingRight=l.previousBodyPadding,l.previousBodyPadding=null),function(){if(x(document.body,n.iosfix)){var e=parseInt(document.body.style.top,10);B(document.body,n.iosfix),document.body.style.top="",document.body.scrollTop=-1*e}}()};q&&!x(o,n.noanimation)?o.addEventListener(q,function e(){o.removeEventListener(q,e),x(o,n.hide)&&r()}):r(),null!==e&&"function"==typeof e&&setTimeout(function(){e(o)})}},I.clickConfirm=function(){return h().click()},I.clickCancel=function(){return y().click()},I.showLoading=I.enableLoading=function(){var e=d();e||I(""),e=d();var t=w(),o=h(),r=y();P(t),P(o,"inline-block"),A(t,n.loading),A(e,n.loading),o.disabled=!0,r.disabled=!0,e.setAttribute("aria-busy",!0),e.focus()},I.isValidParameter=function(t){return e.hasOwnProperty(t)||"extraParams"===t},I.setDefaults=function(e){if(!e||"object"!==(void 0===e?"undefined":V(e)))return a("the argument for setDefaults() is required and has to be a object");for(var t in e)I.isValidParameter(t)||(i('Unknown parameter "'+t+'"'),delete e[t]);O(M,e)},I.resetDefaults=function(){M=O({},e)},I.noop=function(){},I.version="6.11.5",I.default=I,I}),window.Sweetalert2&&(window.sweetAlert=window.swal=window.Sweetalert2);
// 赞踩 投票系统
function tp(type, id, obj) {
    $.ajax({
        url: www + 'post' + exp + 'vote',
        type: "POST",
        cache: false,
        data: { type: type, id: id },
        dataType: 'json'
    }).then(function(data) {
        if (obj != "" || obj != undefined || obj != null) {
            if (data.error == true) {
                $(obj).addClass("is-active");
                $(obj).find("span").text(parseInt($(obj).find("span").text()) + 1);
            } else {
                //$(obj).addClass("is-active");
            }

        }
        if (!data.error)
            swal('投票失败', data.info, 'error');


    }, function() {


    });
}


//删除主题
function del_thread(id, type) {
	
    swal({
        title: "真的删除?",
        text: type == 'thread' ? "该主题的所有评论也随着删除!" : '删除该评论',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "删除",
        cancelButtonText: '取消',
        
    }).then(
        function() {
            $.ajax({
                url: www + type + exp + "del",
                type: "POST",
                cache: false,
                data: {
                    id: id
                },
                dataType: 'json'
            }).then(function(e) {
                swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
                if (e.error) {
                    setTimeout(function() {
                        if (type == 'thread')
                            window.location.href = www;
                        else
                            window.location.reload();

                    }, 1000);
                }

            }, function() {
                swal("失败", "请尝试重新提交", "error");
            });
        },
        function() {
        	//alert('ssa2');
        }

    );


}
//置顶主题
function thread_top(id, type, top) {
    swal({
        title: "真的要这么做?",
        text: "置顶操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: '取消',
        
    }).then(
        function() {
            $.post(www + "thread" + exp + "top", { id: id, type: type, top: top }, function(e) {
                swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
                if (e.error) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                }
            }, 'json');
        },
        function() {

        }
    );
}
//加精主题
function thread_digest(id, type) {
    swal({
        title: "真的要这么做?",
        text: "加精操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: '取消',

    }).then(
        function() {
            $.post(www + "thread" + exp + "digest", { id: id, state: type }, function(e) {
                swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
                if (e.error) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000)
                }
            }, 'json');
        },
        function() {

        }
    );
}
//购买主题
function buy_thread(id, gold) {
    swal({
        title: "主题付费",
        text: "你需要花费" + gold + "金币购买主题",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "购买",
        cancelButtonText: '取消',
        
    }).then(
        function() {
            $.ajax({
                url: www + 'ajax' + exp + 'buythread',
                type: "POST",
                cache: false,
                data: {
                    id: id
                },
                dataType: 'json'
            }).then(function(e) {
                if (e.error) {
                    window.location.reload();
                } else {

                    swal("失败", e.info, "error");

                }
            }, function() {
                swal("失败", "购买失败", "error");
            });
        },
        function(){

        }
    );
}

//下载附件
function hy_downfile(id) {
	loading('获取附件信息中...');
    $.ajax({
        url: www + 'ajax' + exp + 'downfile' + exp + 'id' + exp + id,
        type: "GET",
        cache: false,
        dataType: 'json'
    }).then(function(e) {
    	loading_close();
        if (e.error) {
            $('body').append("<iframe style='display:none' src='" + www + 'ajax' + exp + 'downfile' + exp + 'id' + exp + id + "'></iframe>");
        } else {
            if (e.errorid == 3) {
                swal({
                    title: "附件需要付费",
                    text: "你需要花费" + e.info + "金币购买附件",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "购买",
                    cancelButtonText: '取消',
                    
                }).then(
                	function() {
	                    $.post(www + "ajax" + exp + "buyfile", { id: id }, function(e) {
	                        if (e.error) {
	                            swal("成功", e.info, "success");
	                            hy_downfile(id);
	                        } else {

	                            swal("失败", e.info, "error");


	                        }
	                    }, 'json');
                	},
                	function(){

                	}
                );
            } else {
                swal("失败", e.info, "error");
            }
        }



    }, function() {
    	loading_close();
        swal("失败", "请尝试重新提交", "error");
    });
}
//锁帖 解锁 禁止回复
function set_state(id, state) {
    swal({
        title: (state == 1) ? "你要解锁主题?" : '你要锁定主题?',
        text: (state == 1) ? "解锁后帖子可回复" : "锁定后他人无法回复该帖子，但你自己可以回复！",
        type: (state == 1) ? "success" : "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: (state == 1) ? "解锁" : '锁定',
        cancelButtonText: '取消',
        
    }).then(
    	function() {
	        $.ajax({
	            url: www + 'thread' + exp + 'set_state',
	            type: "POST",
	            cache: false,
	            data: {
	                id: id,
	                state: state,
	            },
	            dataType: 'json'
	        }).then(function(e) {
	            if (e.error)
	                return window.location.reload();

	            swal("失败", e.info, "error");

	        }, function() {
	            swal("失败", "请尝试重新提交", "error");
	        });
    	},
    	function(){
    		
    	}
    );


}
//添加好友
function friend_state(uid, callback) {
    $.ajax({
        url: www + 'friend' + exp + 'friend_state',
        type: "POST",
        cache: false,
        data: {
            uid: uid,
        },
        dataType: 'json'
    }).then(function(e) {
        callback(e.error, e.id);
    }, function() {
        swal("失败", "请尝试重新提交", "error");
    });
}
function ajax_api(url,data,success,error){
	success = success|| null;
	error = error || null;

	$.ajax({
		url: url,
        type: "POST",
        cache: false,
        data: data,
        dataType: 'json',
        success:success,
        error:error
	});
}

function loading(text){
	text = text||'加载中';
    var inputOptions = new Promise(function(resolve) {
		// setTimeout(function() {

		// }, 2000);
    });

    swal({
		title: text,
		input: 'radio',
		allowOutsideClick: false,
		inputOptions: inputOptions,
		inputValidator: function(result) {
			return new Promise(function(resolve, reject) {
				if (result) {
					resolve();
				} else {
					reject('You need to select something!');
				}
			});
		}
	}).then(function(result) {
		if (result) {
			swal({
				type: 'success',
				html: 'You selected: ' + result
			});
		}
	})
}
function loading_close(){
	swal.close();
}


//在线情况
$.post(window.location.href, { online: 1 });
