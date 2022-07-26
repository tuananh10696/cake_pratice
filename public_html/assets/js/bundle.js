"use strict";(self.webpackChunkfrontend_template=self.webpackChunkfrontend_template||[]).push([[296],{190:(e,t,i)=>{var s=i(363);function n(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}function o(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}class r{static get isPassive(){return!!r.HAS_PASSIVE&&{passive:!0}}}o(r,"IS_SP",!1),o(r,"Dispatcher",new class{constructor(){n(this,"listeners",void 0),n(this,"addEventListener",((e,t)=>{null==this.listeners[e]&&(this.listeners[e]=[]),this.listeners[e].push(t)})),n(this,"removeEventListener",((e,t)=>{if(t){const i=this.listeners[e],s=i.length;for(let e=0;e<s;e+=1){i[e]===t&&i.splice(e,1)}}else this.listeners[e]&&(this.listeners[e]=null)})),n(this,"dispatchEvent",((e,t)=>{void 0===t&&(t=null);const i=this.listeners[e];if(null!=i)for(let e=0,s=i.length;e<s;e+=1){const s=i[e];if(s){s({target:this,data:t})}}})),this.listeners={}}}),o(r,"HAS_PASSIVE",!1),o(r,"log",(e=>{0})),o(r,"dir",(e=>{0})),o(r,"warn",(e=>{0})),o(r,"getQueryAsObject",(()=>{const e={},t=location.search.split(/[?&]/);for(let i=0,s=t.length;i<s;++i){const s=t[i].split("="),n=decodeURI(s[0]),o=decodeURI(s[1]);n&&(e[n]=o)}return e}));try{const e=()=>{},t=Object.defineProperty({},"passive",{get:()=>(r.HAS_PASSIVE=!0,!0)});window.addEventListener("test",e,t),window.removeEventListener("test",e)}catch(e){r.HAS_PASSIVE=!1}function c(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}if(window.MSInputMethodContext&&document.documentMode){const e=document.createElement("script");e.src="https://cdn.jsdelivr.net/gh/nuxodin/ie11CustomProperties@4.1.0/ie11CustomProperties.min.js",document.body.appendChild(e),e.onload=()=>{try{const e=window.document.createEvent("UIEvents");e.initUIEvent("resize",!0,!1,window,0),window.dispatchEvent(e)}catch(e){}}}var l,h,a,d=i(535),u=i(970);function f(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}d.ZP.registerPlugin(u.L);class w{constructor(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:.6;if(f(this,"currentX",0),f(this,"currentY",0),f(this,"offsetY",0),f(this,"speed",0),f(this,"offsetTarget",null),f(this,"go2DefaultTarget",(e=>{let t=this.offsetY;this.offsetTarget&&(t=this.offsetTarget.offsetHeight);try{d.ZP.killTweensOf(window),d.ZP.set(window,{scrollTo:{x:e,y:e,offsetY:t}})}catch(i){const s=e.offsetLeft,n=e.offsetTop-t;window.scrollTo(s,n)}})),f(this,"go2Anchor",(e=>{let t=this.offsetY;this.offsetTarget&&(t=this.offsetTarget.offsetHeight);let i=e.currentTarget.getAttribute("href").split("/");i=i.length>=2?i[1]:i[0];const s=document.querySelector(i);if(s){try{d.ZP.killTweensOf(window),d.ZP.to(window,this.speed,{scrollTo:{x:s,y:s,autoKill:!1,offsetY:t},ease:"power1.inOut"})}catch(e){this.currentX=window.scrollX,this.currentY=window.scrollY;const i=s.offsetLeft,n=s.offsetTop-t;d.ZP.killTweensOf(this),d.ZP.to(this,this.speed,{currentX:i,currentY:n,ease:"power1.inOut",onUpdate:()=>{window.scrollTo(this.currentX,this.currentY)}})}e.preventDefault()}})),this.speed=t,"number"===typeof e&&(this.offsetY=e),"string"===typeof e&&(this.offsetTarget=document.querySelector(e)),window.location.hash){const e=document.querySelector(window.location.hash);e&&("scrollRestoration"in window.history&&(window.history.scrollRestoration="manual"),this.go2DefaultTarget(e))}const i=Array.prototype.slice.call(document.querySelectorAll('a[href^="#"], a[href^="/#"]'));for(const e of i){const t=e.getAttribute("href");t&&t.length>1&&e.addEventListener("click",this.go2Anchor,!1)}}}function g(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}function p(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}class v{constructor(){var e,t,i,s;p(this,"header",document.getElementById("header")),p(this,"flag",!1),p(this,"headerBtn",null===(e=this.header)||void 0===e?void 0:e.getElementsByClassName("js-headerBtn")[0]),p(this,"headerLinks",[...null===(t=this.header)||void 0===t?void 0:t.getElementsByClassName("header__nav-link")]),p(this,"toggle",(()=>{this.flag?this.close():this.open()})),p(this,"open",(()=>{var e;null===(e=this.header)||void 0===e||e.classList.add("open"),this.flag=!0})),p(this,"close",(()=>{var e;null===(e=this.header)||void 0===e||e.classList.remove("open"),this.flag=!1})),null===(i=this.headerBtn)||void 0===i||i.addEventListener("click",this.toggle,!1),null===(s=this.headerLinks)||void 0===s||s.forEach((e=>{e.addEventListener("click",this.close,!1)}))}}class m{}a=!1,(h="IS_FIRST_VISIT")in(l=m)?Object.defineProperty(l,h,{value:a,enumerable:!0,configurable:!0,writable:!0}):l[h]=a;class y{constructor(){sessionStorage.getItem("access")?m.IS_FIRST_VISIT=!1:(sessionStorage.setItem("access",0),m.IS_FIRST_VISIT=!0)}}new class{constructor(){let e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];c(this,"isOnlyWidth",!1),c(this,"refSize",{width:0,height:0}),c(this,"currentSize",{width:0,height:0}),c(this,"onResize",(()=>{const e=document.body&&document.body.clientWidth||0;this.isOnlyWidth&&r.IS_SP&&e===this.refSize.width||(this.refSize.width=e,this.refSize.height=window.innerHeight,document.documentElement.style.setProperty("--vw","".concat(this.refSize.width/100,"px")),document.documentElement.style.setProperty("--vh","".concat(this.refSize.height/100,"px")))})),this.isOnlyWidth=e;const t=document.body&&document.body.clientWidth||0;this.currentSize.width=t,this.currentSize.height=window.innerHeight,window.addEventListener("resize",this.onResize,r.isPassive),this.onResize()}},new class{constructor(){var e,t,i;i=e=>{e.matches?r.IS_SP=!1:r.IS_SP=!0,r.Dispatcher.dispatchEvent("DeviceChange")},(t="onDeviceChange")in(e=this)?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i;const s=window.matchMedia("(min-width: 769px)");s.addEventListener?s.addEventListener("change",this.onDeviceChange):s.addListener(this.onDeviceChange),this.onDeviceChange(s)}},new class{constructor(){g(this,"prevTop",0),g(this,"isLock",!1),g(this,"isToggle",!1),g(this,"lock",(()=>{!this.isToggle&&this.isLock||(this.isLock=!0,this.prevTop=document.documentElement&&document.documentElement.scrollTop||document.body.scrollTop,document.body.style.position="fixed",document.body.style.left="0",document.body.style.top="".concat(-this.prevTop,"px"),document.body.style.width="100%")})),g(this,"release",(()=>{(this.isToggle||this.isLock)&&(this.isLock=!1,document.body.removeAttribute("style"),window.scrollTo(0,this.prevTop))})),g(this,"toggle",(()=>{this.isToggle=!0,this.isLock=!this.isLock,this.isLock?this.lock():this.release(),this.isToggle=!1})),r.Dispatcher.addEventListener("SCROLL_LOCK",this.lock),r.Dispatcher.addEventListener("SCROLL_RELEASE",this.release)}};class b{constructor(){new w("#header"),new v,new y,(async()=>{const e=window.location.pathname;(0,s.Bo)("/").exec(e)&&await i.e(826).then(i.bind(i,258)).then((e=>{new e.default}))})()}}window.addEventListener("DOMContentLoaded",(()=>{new b})),window.addEventListener("load",(()=>{new w("#header")}))}},e=>{e.O(0,[736],(()=>{return t=190,e(e.s=t);var t}));e.O()}]);