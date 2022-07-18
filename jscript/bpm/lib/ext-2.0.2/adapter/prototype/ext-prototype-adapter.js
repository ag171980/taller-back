/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext={version:"2.0.2"};window["undefined"]=window["undefined"];Ext.apply=function(C,D,B){if(B){Ext.apply(C,B)}if(C&&D&&typeof D=="object"){for(var A in D){C[A]=D[A]}}return C};(function(){var idSeed=0;var ua=navigator.userAgent.toLowerCase();var isStrict=document.compatMode=="CSS1Compat",isOpera=ua.indexOf("opera")>-1,isSafari=(/webkit|khtml/).test(ua),isSafari3=isSafari&&ua.indexOf("webkit/5")!=-1,isIE=!isOpera&&ua.indexOf("msie")>-1,isIE7=!isOpera&&ua.indexOf("msie 7")>-1,isGecko=!isSafari&&ua.indexOf("gecko")>-1,isBorderBox=isIE&&!isStrict,isWindows=(ua.indexOf("windows")!=-1||ua.indexOf("win32")!=-1),isMac=(ua.indexOf("macintosh")!=-1||ua.indexOf("mac os x")!=-1),isAir=(ua.indexOf("adobeair")!=-1),isLinux=(ua.indexOf("linux")!=-1),isSecure=window.location.href.toLowerCase().indexOf("https")===0;if(isIE&&!isIE7){try{document.execCommand("BackgroundImageCache",false,true)}catch(e){}}Ext.apply(Ext,{isStrict:isStrict,isSecure:isSecure,isReady:false,enableGarbageCollector:true,enableListenerCollection:false,SSL_SECURE_URL:"javascript:false",BLANK_IMAGE_URL:"http:/"+"/extjs.com/s.gif",emptyFn:function(){},applyIf:function(o,c){if(o&&c){for(var p in c){if(typeof o[p]=="undefined"){o[p]=c[p]}}}return o},addBehaviors:function(o){if(!Ext.isReady){Ext.onReady(function(){Ext.addBehaviors(o)});return }var cache={};for(var b in o){var parts=b.split("@");if(parts[1]){var s=parts[0];if(!cache[s]){cache[s]=Ext.select(s)}cache[s].on(parts[1],o[b])}}cache=null},id:function(el,prefix){prefix=prefix||"ext-gen";el=Ext.getDom(el);var id=prefix+(++idSeed);return el?(el.id?el.id:(el.id=id)):id},extend:function(){var io=function(o){for(var m in o){this[m]=o[m]}};var oc=Object.prototype.constructor;return function(sb,sp,overrides){if(typeof sp=="object"){overrides=sp;sp=sb;sb=overrides.constructor!=oc?overrides.constructor:function(){sp.apply(this,arguments)}}var F=function(){},sbp,spp=sp.prototype;F.prototype=spp;sbp=sb.prototype=new F();sbp.constructor=sb;sb.superclass=spp;if(spp.constructor==oc){spp.constructor=sp}sb.override=function(o){Ext.override(sb,o)};sbp.override=io;Ext.override(sb,overrides);sb.extend=function(o){Ext.extend(sb,o)};return sb}}(),override:function(origclass,overrides){if(overrides){var p=origclass.prototype;for(var method in overrides){p[method]=overrides[method]}}},namespace:function(){var a=arguments,o=null,i,j,d,rt;for(i=0;i<a.length;++i){d=a[i].split(".");rt=d[0];eval("if (typeof "+rt+" == \"undefined\"){"+rt+" = {};} o = "+rt+";");for(j=1;j<d.length;++j){o[d[j]]=o[d[j]]||{};o=o[d[j]]}}},urlEncode:function(o){if(!o){return""}var buf=[];for(var key in o){var ov=o[key],k=encodeURIComponent(key);var type=typeof ov;if(type=="undefined"){buf.push(k,"=&")}else{if(type!="function"&&type!="object"){buf.push(k,"=",encodeURIComponent(ov),"&")}else{if(Ext.isArray(ov)){if(ov.length){for(var i=0,len=ov.length;i<len;i++){buf.push(k,"=",encodeURIComponent(ov[i]===undefined?"":ov[i]),"&")}}else{buf.push(k,"=&")}}}}}buf.pop();return buf.join("")},urlDecode:function(string,overwrite){if(!string||!string.length){return{}}var obj={};var pairs=string.split("&");var pair,name,value;for(var i=0,len=pairs.length;i<len;i++){pair=pairs[i].split("=");name=decodeURIComponent(pair[0]);value=decodeURIComponent(pair[1]);if(overwrite!==true){if(typeof obj[name]=="undefined"){obj[name]=value}else{if(typeof obj[name]=="string"){obj[name]=[obj[name]];obj[name].push(value)}else{obj[name].push(value)}}}else{obj[name]=value}}return obj},each:function(array,fn,scope){if(typeof array.length=="undefined"||typeof array=="string"){array=[array]}for(var i=0,len=array.length;i<len;i++){if(fn.call(scope||array[i],array[i],i,array)===false){return i}}},combine:function(){var as=arguments,l=as.length,r=[];for(var i=0;i<l;i++){var a=as[i];if(Ext.isArray(a)){r=r.concat(a)}else{if(a.length!==undefined&&!a.substr){r=r.concat(Array.prototype.slice.call(a,0))}else{r.push(a)}}}return r},escapeRe:function(s){return s.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1")},callback:function(cb,scope,args,delay){if(typeof cb=="function"){if(delay){cb.defer(delay,scope,args||[])}else{cb.apply(scope,args||[])}}},getDom:function(el){if(!el||!document){return null}return el.dom?el.dom:(typeof el=="string"?document.getElementById(el):el)},getDoc:function(){return Ext.get(document)},getBody:function(){return Ext.get(document.body||document.documentElement)},getCmp:function(id){return Ext.ComponentMgr.get(id)},num:function(v,defaultValue){if(typeof v!="number"){return defaultValue}return v},destroy:function(){for(var i=0,a=arguments,len=a.length;i<len;i++){var as=a[i];if(as){if(typeof as.destroy=="function"){as.destroy()}else{if(as.dom){as.removeAllListeners();as.remove()}}}}},removeNode:isIE?function(){var d;return function(n){if(n&&n.tagName!="BODY"){d=d||document.createElement("div");d.appendChild(n);d.innerHTML=""}}}():function(n){if(n&&n.parentNode&&n.tagName!="BODY"){n.parentNode.removeChild(n)}},type:function(o){if(o===undefined||o===null){return false}if(o.htmlElement){return"element"}var t=typeof o;if(t=="object"&&o.nodeName){switch(o.nodeType){case 1:return"element";case 3:return(/\S/).test(o.nodeValue)?"textnode":"whitespace"}}if(t=="object"||t=="function"){switch(o.constructor){case Array:return"array";case RegExp:return"regexp"}if(typeof o.length=="number"&&typeof o.item=="function"){return"nodelist"}}return t},isEmpty:function(v,allowBlank){return v===null||v===undefined||(!allowBlank?v==="":false)},value:function(v,defaultValue,allowBlank){return Ext.isEmpty(v,allowBlank)?defaultValue:v},isArray:function(v){return v&&typeof v.pop=="function"},isDate:function(v){return v&&typeof v.getFullYear=="function"},isOpera:isOpera,isSafari:isSafari,isSafari3:isSafari3,isSafari2:isSafari&&!isSafari3,isIE:isIE,isIE6:isIE&&!isIE7,isIE7:isIE7,isGecko:isGecko,isBorderBox:isBorderBox,isLinux:isLinux,isWindows:isWindows,isMac:isMac,isAir:isAir,useShims:((isIE&&!isIE7)||(isGecko&&isMac))});Ext.ns=Ext.namespace})();Ext.ns("Ext","Ext.util","Ext.grid","Ext.dd","Ext.tree","Ext.data","Ext.form","Ext.menu","Ext.state","Ext.lib","Ext.layout","Ext.app","Ext.ux");Ext.apply(Function.prototype,{createCallback:function(){var A=arguments;var B=this;return function(){return B.apply(window,A)}},createDelegate:function(C,B,A){var D=this;return function(){var F=B||arguments;if(A===true){F=Array.prototype.slice.call(arguments,0);F=F.concat(B)}else{if(typeof A=="number"){F=Array.prototype.slice.call(arguments,0);var E=[A,0].concat(B);Array.prototype.splice.apply(F,E)}}return D.apply(C||window,F)}},defer:function(C,E,B,A){var D=this.createDelegate(E,B,A);if(C){return setTimeout(D,C)}D();return 0},createSequence:function(B,A){if(typeof B!="function"){return this}var C=this;return function(){var D=C.apply(this||window,arguments);B.apply(A||this||window,arguments);return D}},createInterceptor:function(B,A){if(typeof B!="function"){return this}var C=this;return function(){B.target=this;B.method=C;if(B.apply(A||this||window,arguments)===false){return }return C.apply(this||window,arguments)}}});Ext.applyIf(String,{escape:function(A){return A.replace(/('|\\)/g,"\\$1")},leftPad:function(D,B,C){var A=new String(D);if(!C){C=" "}while(A.length<B){A=C+A}return A.toString()},format:function(B){var A=Array.prototype.slice.call(arguments,1);return B.replace(/\{(\d+)\}/g,function(C,D){return A[D]})}});String.prototype.toggle=function(B,A){return this==B?A:B};String.prototype.trim=function(){var A=/^\s+|\s+$/g;return function(){return this.replace(A,"")}}();Ext.applyIf(Number.prototype,{constrain:function(B,A){return Math.min(Math.max(this,B),A)}});Ext.applyIf(Array.prototype,{indexOf:function(C){for(var B=0,A=this.length;B<A;B++){if(this[B]==C){return B}}return -1},remove:function(B){var A=this.indexOf(B);if(A!=-1){this.splice(A,1)}return this}});Date.prototype.getElapsed=function(A){return Math.abs((A||new Date()).getTime()-this.getTime())};
(function(){var B;Ext.lib.Dom={getViewWidth:function(D){return D?this.getDocumentWidth():this.getViewportWidth()},getViewHeight:function(D){return D?this.getDocumentHeight():this.getViewportHeight()},getDocumentHeight:function(){var D=(document.compatMode!="CSS1Compat")?document.body.scrollHeight:document.documentElement.scrollHeight;return Math.max(D,this.getViewportHeight())},getDocumentWidth:function(){var D=(document.compatMode!="CSS1Compat")?document.body.scrollWidth:document.documentElement.scrollWidth;return Math.max(D,this.getViewportWidth())},getViewportHeight:function(){var D=self.innerHeight;var E=document.compatMode;if((E||Ext.isIE)&&!Ext.isOpera){D=(E=="CSS1Compat")?document.documentElement.clientHeight:document.body.clientHeight}return D},getViewportWidth:function(){var D=self.innerWidth;var E=document.compatMode;if(E||Ext.isIE){D=(E=="CSS1Compat")?document.documentElement.clientWidth:document.body.clientWidth}return D},isAncestor:function(E,F){E=Ext.getDom(E);F=Ext.getDom(F);if(!E||!F){return false}if(E.contains&&!Ext.isSafari){return E.contains(F)}else{if(E.compareDocumentPosition){return !!(E.compareDocumentPosition(F)&16)}else{var D=F.parentNode;while(D){if(D==E){return true}else{if(!D.tagName||D.tagName.toUpperCase()=="HTML"){return false}}D=D.parentNode}return false}}},getRegion:function(D){return Ext.lib.Region.getRegion(D)},getY:function(D){return this.getXY(D)[1]},getX:function(D){return this.getXY(D)[0]},getXY:function(F){var E,J,L,M,I=(document.body||document.documentElement);F=Ext.getDom(F);if(F==I){return[0,0]}if(F.getBoundingClientRect){L=F.getBoundingClientRect();M=C(document).getScroll();return[L.left+M.left,L.top+M.top]}var N=0,K=0;E=F;var D=C(F).getStyle("position")=="absolute";while(E){N+=E.offsetLeft;K+=E.offsetTop;if(!D&&C(E).getStyle("position")=="absolute"){D=true}if(Ext.isGecko){J=C(E);var O=parseInt(J.getStyle("borderTopWidth"),10)||0;var G=parseInt(J.getStyle("borderLeftWidth"),10)||0;N+=G;K+=O;if(E!=F&&J.getStyle("overflow")!="visible"){N+=G;K+=O}}E=E.offsetParent}if(Ext.isSafari&&D){N-=I.offsetLeft;K-=I.offsetTop}if(Ext.isGecko&&!D){var H=C(I);N+=parseInt(H.getStyle("borderLeftWidth"),10)||0;K+=parseInt(H.getStyle("borderTopWidth"),10)||0}E=F.parentNode;while(E&&E!=I){if(!Ext.isOpera||(E.tagName!="TR"&&C(E).getStyle("display")!="inline")){N-=E.scrollLeft;K-=E.scrollTop}E=E.parentNode}return[N,K]},setXY:function(D,E){D=Ext.fly(D,"_setXY");D.position();var F=D.translatePoints(E);if(E[0]!==false){D.dom.style.left=F.left+"px"}if(E[1]!==false){D.dom.style.top=F.top+"px"}},setX:function(E,D){this.setXY(E,[D,false])},setY:function(D,E){this.setXY(D,[false,E])}};Ext.lib.Event={getPageX:function(D){return Event.pointerX(D.browserEvent||D)},getPageY:function(D){return Event.pointerY(D.browserEvent||D)},getXY:function(D){D=D.browserEvent||D;return[Event.pointerX(D),Event.pointerY(D)]},getTarget:function(D){return Event.element(D.browserEvent||D)},resolveTextNode:function(D){if(D&&3==D.nodeType){return D.parentNode}else{return D}},getRelatedTarget:function(E){E=E.browserEvent||E;var D=E.relatedTarget;if(!D){if(E.type=="mouseout"){D=E.toElement}else{if(E.type=="mouseover"){D=E.fromElement}}}return this.resolveTextNode(D)},on:function(F,D,E){Event.observe(F,D,E,false)},un:function(F,D,E){Event.stopObserving(F,D,E,false)},purgeElement:function(D){},preventDefault:function(D){D=D.browserEvent||D;if(D.preventDefault){D.preventDefault()}else{D.returnValue=false}},stopPropagation:function(D){D=D.browserEvent||D;if(D.stopPropagation){D.stopPropagation()}else{D.cancelBubble=true}},stopEvent:function(D){Event.stop(D.browserEvent||D)},onAvailable:function(I,E,D){var H=new Date(),G;var F=function(){if(H.getElapsed()>10000){clearInterval(G)}var J=document.getElementById(I);if(J){clearInterval(G);E.call(D||window,J)}};G=setInterval(F,50)}};Ext.lib.Ajax=function(){var E=function(F){return F.success?function(G){F.success.call(F.scope||window,{responseText:G.responseText,responseXML:G.responseXML,argument:F.argument})}:Ext.emptyFn};var D=function(F){return F.failure?function(G){F.failure.call(F.scope||window,{responseText:G.responseText,responseXML:G.responseXML,argument:F.argument})}:Ext.emptyFn};return{request:function(K,H,F,I,G){var J={method:K,parameters:I||"",timeout:F.timeout,onSuccess:E(F),onFailure:D(F)};if(G){if(G.headers){J.requestHeaders=G.headers}if(G.xmlData){K="POST";J.contentType="text/xml";J.postBody=G.xmlData;delete J.parameters}if(G.jsonData){K="POST";J.contentType="text/javascript";J.postBody=typeof G.jsonData=="object"?Ext.encode(G.jsonData):G.jsonData;delete J.parameters}}new Ajax.Request(H,J)},formRequest:function(J,I,G,K,F,H){new Ajax.Request(I,{method:Ext.getDom(J).method||"POST",parameters:Form.serialize(J)+(K?"&"+K:""),timeout:G.timeout,onSuccess:E(G),onFailure:D(G)})},isCallInProgress:function(F){return false},abort:function(F){return false},serializeForm:function(F){return Form.serialize(F.dom||F)}}}();Ext.lib.Anim=function(){var D={easeOut:function(F){return 1-Math.pow(1-F,2)},easeIn:function(F){return 1-Math.pow(1-F,2)}};var E=function(F,G){return{stop:function(H){this.effect.cancel()},isAnimated:function(){return this.effect.state=="running"},proxyCallback:function(){Ext.callback(F,G)}}};return{scroll:function(I,G,K,L,F,H){var J=E(F,H);I=Ext.getDom(I);if(typeof G.scroll.to[0]=="number"){I.scrollLeft=G.scroll.to[0]}if(typeof G.scroll.to[1]=="number"){I.scrollTop=G.scroll.to[1]}J.proxyCallback();return J},motion:function(I,G,J,K,F,H){return this.run(I,G,J,K,F,H)},color:function(I,G,J,K,F,H){return this.run(I,G,J,K,F,H)},run:function(G,O,K,N,H,Q,P){var F={};for(var J in O){switch(J){case"points":var M,S,L=Ext.fly(G,"_animrun");L.position();if(M=O.points.by){var R=L.getXY();S=L.translatePoints([R[0]+M[0],R[1]+M[1]])}else{S=L.translatePoints(O.points.to)}F.left=S.left+"px";F.top=S.top+"px";break;case"width":F.width=O.width.to+"px";break;case"height":F.height=O.height.to+"px";break;case"opacity":F.opacity=String(O.opacity.to);break;default:F[J]=String(O[J].to);break}}var I=E(H,Q);I.effect=new Effect.Morph(Ext.id(G),{duration:K,afterFinish:I.proxyCallback,transition:D[N]||Effect.Transitions.linear,style:F});return I}}}();function C(D){if(!B){B=new Ext.Element.Flyweight()}B.dom=D;return B}Ext.lib.Region=function(F,G,D,E){this.top=F;this[1]=F;this.right=G;this.bottom=D;this.left=E;this[0]=E};Ext.lib.Region.prototype={contains:function(D){return(D.left>=this.left&&D.right<=this.right&&D.top>=this.top&&D.bottom<=this.bottom)},getArea:function(){return((this.bottom-this.top)*(this.right-this.left))},intersect:function(H){var F=Math.max(this.top,H.top);var G=Math.min(this.right,H.right);var D=Math.min(this.bottom,H.bottom);var E=Math.max(this.left,H.left);if(D>=F&&G>=E){return new Ext.lib.Region(F,G,D,E)}else{return null}},union:function(H){var F=Math.min(this.top,H.top);var G=Math.max(this.right,H.right);var D=Math.max(this.bottom,H.bottom);var E=Math.min(this.left,H.left);return new Ext.lib.Region(F,G,D,E)},constrainTo:function(D){this.top=this.top.constrain(D.top,D.bottom);this.bottom=this.bottom.constrain(D.top,D.bottom);this.left=this.left.constrain(D.left,D.right);this.right=this.right.constrain(D.left,D.right);return this},adjust:function(F,E,D,G){this.top+=F;this.left+=E;this.right+=G;this.bottom+=D;return this}};Ext.lib.Region.getRegion=function(G){var I=Ext.lib.Dom.getXY(G);var F=I[1];var H=I[0]+G.offsetWidth;var D=I[1]+G.offsetHeight;var E=I[0];return new Ext.lib.Region(F,H,D,E)};Ext.lib.Point=function(D,E){if(Ext.isArray(D)){E=D[1];D=D[0]}this.x=this.right=this.left=this[0]=D;this.y=this.top=this.bottom=this[1]=E};Ext.lib.Point.prototype=new Ext.lib.Region();if(Ext.isIE){function A(){var D=Function.prototype;delete D.createSequence;delete D.defer;delete D.createDelegate;delete D.createCallback;delete D.createInterceptor;window.detachEvent("onunload",A)}window.attachEvent("onunload",A)}})();
