/*!
 * jQuery UI Droppable 1.10.0
 * http://jqueryui.com
 *
 * Copyright 2013 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/droppable/
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *	jquery.ui.mouse.js
 *	jquery.ui.draggable.js
 */(function(e,t){function n(e,t,n){return e>t&&e<t+n}e.widget("ui.droppable",{version:"1.10.0",widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect",activate:null,deactivate:null,drop:null,out:null,over:null},_create:function(){var t=this.options,n=t.accept;this.isover=!1;this.isout=!0;this.accept=e.isFunction(n)?n:function(e){return e.is(n)};this.proportions={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight};e.ui.ddmanager.droppables[t.scope]=e.ui.ddmanager.droppables[t.scope]||[];e.ui.ddmanager.droppables[t.scope].push(this);t.addClasses&&this.element.addClass("ui-droppable")},_destroy:function(){var t=0,n=e.ui.ddmanager.droppables[this.options.scope];for(;t<n.length;t++)n[t]===this&&n.splice(t,1);this.element.removeClass("ui-droppable ui-droppable-disabled")},_setOption:function(t,n){t==="accept"&&(this.accept=e.isFunction(n)?n:function(e){return e.is(n)});e.Widget.prototype._setOption.apply(this,arguments)},_activate:function(t){var n=e.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass);n&&this._trigger("activate",t,this.ui(n))},_deactivate:function(t){var n=e.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass);n&&this._trigger("deactivate",t,this.ui(n))},_over:function(t){var n=e.ui.ddmanager.current;if(!n||(n.currentItem||n.element)[0]===this.element[0])return;if(this.accept.call(this.element[0],n.currentItem||n.element)){this.options.hoverClass&&this.element.addClass(this.options.hoverClass);this._trigger("over",t,this.ui(n))}},_out:function(t){var n=e.ui.ddmanager.current;if(!n||(n.currentItem||n.element)[0]===this.element[0])return;if(this.accept.call(this.element[0],n.currentItem||n.element)){this.options.hoverClass&&this.element.removeClass(this.options.hoverClass);this._trigger("out",t,this.ui(n))}},_drop:function(t,n){var r=n||e.ui.ddmanager.current,i=!1;if(!r||(r.currentItem||r.element)[0]===this.element[0])return!1;this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function(){var t=e.data(this,"ui-droppable");if(t.options.greedy&&!t.options.disabled&&t.options.scope===r.options.scope&&t.accept.call(t.element[0],r.currentItem||r.element)&&e.ui.intersect(r,e.extend(t,{offset:t.element.offset()}),t.options.tolerance)){i=!0;return!1}});if(i)return!1;if(this.accept.call(this.element[0],r.currentItem||r.element)){this.options.activeClass&&this.element.removeClass(this.options.activeClass);this.options.hoverClass&&this.element.removeClass(this.options.hoverClass);this._trigger("drop",t,this.ui(r));return this.element}return!1},ui:function(e){return{draggable:e.currentItem||e.element,helper:e.helper,position:e.position,offset:e.positionAbs}}});e.ui.intersect=function(e,t,r){if(!t.offset)return!1;var i,s,o=(e.positionAbs||e.position.absolute).left,u=o+e.helperProportions.width,a=(e.positionAbs||e.position.absolute).top,f=a+e.helperProportions.height,l=t.offset.left,c=l+t.proportions.width,h=t.offset.top,p=h+t.proportions.height;switch(r){case"fit":return l<=o&&u<=c&&h<=a&&f<=p;case"intersect":return l<o+e.helperProportions.width/2&&u-e.helperProportions.width/2<c&&h<a+e.helperProportions.height/2&&f-e.helperProportions.height/2<p;case"pointer":i=(e.positionAbs||e.position.absolute).left+(e.clickOffset||e.offset.click).left;s=(e.positionAbs||e.position.absolute).top+(e.clickOffset||e.offset.click).top;return n(s,h,t.proportions.height)&&n(i,l,t.proportions.width);case"touch":return(a>=h&&a<=p||f>=h&&f<=p||a<h&&f>p)&&(o>=l&&o<=c||u>=l&&u<=c||o<l&&u>c);default:return!1}};e.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(t,n){var r,i,s=e.ui.ddmanager.droppables[t.options.scope]||[],o=n?n.type:null,u=(t.currentItem||t.element).find(":data(ui-droppable)").addBack();e:for(r=0;r<s.length;r++){if(s[r].options.disabled||t&&!s[r].accept.call(s[r].element[0],t.currentItem||t.element))continue;for(i=0;i<u.length;i++)if(u[i]===s[r].element[0]){s[r].proportions.height=0;continue e}s[r].visible=s[r].element.css("display")!=="none";if(!s[r].visible)continue;o==="mousedown"&&s[r]._activate.call(s[r],n);s[r].offset=s[r].element.offset();s[r].proportions={width:s[r].element[0].offsetWidth,height:s[r].element[0].offsetHeight}}},drop:function(t,n){var r=!1;e.each(e.ui.ddmanager.droppables[t.options.scope]||[],function(){if(!this.options)return;!this.options.disabled&&this.visible&&e.ui.intersect(t,this,this.options.tolerance)&&(r=this._drop.call(this,n)||r);if(!this.options.disabled&&this.visible&&this.accept.call(this.element[0],t.currentItem||t.element)){this.isout=!0;this.isover=!1;this._deactivate.call(this,n)}});return r},dragStart:function(t,n){t.element.parentsUntil("body").bind("scroll.droppable",function(){t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,n)})},drag:function(t,n){t.options.refreshPositions&&e.ui.ddmanager.prepareOffsets(t,n);e.each(e.ui.ddmanager.droppables[t.options.scope]||[],function(){if(this.options.disabled||this.greedyChild||!this.visible)return;var r,i,s,o=e.ui.intersect(t,this,this.options.tolerance),u=!o&&this.isover?"isout":o&&!this.isover?"isover":null;if(!u)return;if(this.options.greedy){i=this.options.scope;s=this.element.parents(":data(ui-droppable)").filter(function(){return e.data(this,"ui-droppable").options.scope===i});if(s.length){r=e.data(s[0],"ui-droppable");r.greedyChild=u==="isover"}}if(r&&u==="isover"){r.isover=!1;r.isout=!0;r._out.call(r,n)}this[u]=!0;this[u==="isout"?"isover":"isout"]=!1;this[u==="isover"?"_over":"_out"].call(this,n);if(r&&u==="isout"){r.isout=!1;r.isover=!0;r._over.call(r,n)}})},dragStop:function(t,n){t.element.parentsUntil("body").unbind("scroll.droppable");t.options.refreshPositions||e.ui.ddmanager.prepareOffsets(t,n)}}})(jQuery);