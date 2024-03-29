//Pell.min.js
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports):"function"==typeof define&&define.amd?define(["exports"],e):e(t.pell={})}(this,function(t){"use strict";var e=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(t[r]=n[r])}return t},n=function(t,e,n){return t.addEventListener(e,n)},r=function(t,e){return t.appendChild(e)},i=function(t){return document.createElement(t)},o=function(t){return document.queryCommandState(t)},u=function(t){return document.queryCommandValue(t)},c=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return document.execCommand(t,!1,e)},l={bold:{icon:"<b>B</b>",title:"Bold",state:function(){return o("bold")},result:function(){return c("bold")}},italic:{icon:"<i>I</i>",title:"Italic",state:function(){return o("italic")},result:function(){return c("italic")}},underline:{icon:"<u>U</u>",title:"Underline",state:function(){return o("underline")},result:function(){return c("underline")}},strikethrough:{icon:"<strike>S</strike>",title:"Strike-through",state:function(){return o("strikeThrough")},result:function(){return c("strikeThrough")}},heading1:{icon:"<b>H<sub>1</sub></b>",title:"Heading 1",result:function(){return c("formatBlock","<h1>")}},heading2:{icon:"<b>H<sub>2</sub></b>",title:"Heading 2",result:function(){return c("formatBlock","<h2>")}},paragraph:{icon:"&#182;",title:"Paragraph",result:function(){return c("formatBlock","<p>")}},quote:{icon:"&#8220; &#8221;",title:"Quote",result:function(){return c("formatBlock","<blockquote>")}},olist:{icon:"&#35;",title:"Ordered List",result:function(){return c("insertOrderedList")}},ulist:{icon:"&#8226;",title:"Unordered List",result:function(){return c("insertUnorderedList")}},code:{icon:"&lt;/&gt;",title:"Code",result:function(){return c("formatBlock","<pre>")}},line:{icon:"&#8213;",title:"Horizontal Line",result:function(){return c("insertHorizontalRule")}},link:{icon:"&#128279;",title:"Link",result:function(){var t=window.prompt("Enter the link URL");t&&c("createLink",t)}},image:{icon:"&#128247;",title:"Image",result:function(){var t=window.prompt("Enter the image URL");t&&c("insertImage",t)}}},a={actionbar:"pell-actionbar",button:"pell-button",content:"pell-content",selected:"pell-button-selected"},s=function(t){var o=t.actions?t.actions.map(function(t){return"string"==typeof t?l[t]:l[t.name]?e({},l[t.name],t):t}):Object.keys(l).map(function(t){return l[t]}),s=e({},a,t.classes),f=t.defaultParagraphSeparator||"div",d=i("div");d.className=s.actionbar,r(t.element,d);var m=t.element.content=i("div");return m.contentEditable=!0,m.className=s.content,m.oninput=function(e){var n=e.target.firstChild;n&&3===n.nodeType?c("formatBlock","<"+f+">"):"<br>"===m.innerHTML&&(m.innerHTML=""),t.onChange(m.innerHTML)},m.onkeydown=function(t){"Tab"===t.key?t.preventDefault():"Enter"===t.key&&"blockquote"===u("formatBlock")&&setTimeout(function(){return c("formatBlock","<"+f+">")},0)},r(t.element,m),o.forEach(function(t){var e=i("button");if(e.className=s.button,e.innerHTML=t.icon,e.title=t.title,e.setAttribute("type","button"),e.onclick=function(){return t.result()&&m.focus()},t.state){var o=function(){return e.classList[t.state()?"add":"remove"](s.selected)};n(m,"keyup",o),n(m,"mouseup",o),n(e,"click",o)}r(d,e)}),t.styleWithCSS&&c("styleWithCSS"),c("defaultParagraphSeparator",f),t.element},f={exec:c,init:s};t.exec=c,t.init=s,t.default=f,Object.defineProperty(t,"__esModule",{value:!0})});

// Configure for IQAC
Array.from(document.getElementsByTagName("textarea")).forEach(function(e) {
    e.style.display = "none";
    var content = e.value;
    var a = document.createElement("div");
    a.classList.add("pell");
    e.parentNode.insertBefore(a, e);
    const editor = window.pell.init({
        element: a,
        defaultParagraphSeparator: "p",
        onChange: function(a) { e.value = a; }
    });
    editor.content.innerHTML = content;
});

// Process score class
Array.from(document.getElementsByClassName("score")).forEach(function(e){
    if( isNaN(e.innerHTML) || e.innerHTML=="" ) return;
    e.style.backgroundColor = "hsl("+Math.floor(e.innerHTML*1.2)+", 100%, 60%)";
    e.style.color = "#222";
});