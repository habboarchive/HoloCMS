var element_data=[];var currentElement=false;function bringElementOnTop(B){var A=B.style.zIndex;var C=0;$("playground").select(".movable").each(function(E){var D=parseInt(E.style.zIndex);if(D>A){E.style.zIndex=D-1}if(D>=C){C=D}});B.style.zIndex=C+1}function getNextZIndex(){var A=0;$("playground").select(".movable").each(function(C){var B=parseInt(C.style.zIndex);
if(B>=A){A=B}});return A+1}function getElementCount(){return $("playground").select(".movable").length}var ZetaWatcher=Class.create();ZetaWatcher.prototype={initialize:function(A){this.element=A;this.element.onclick=this.updatePositions.bindAsEventListener(this)},updatePositions:function(A){if(!isNotWithinPlayground(this.element)){bringElementOnTop(this.element);
savePosition(this.element)}}};var EditButtonObserver=Class.create();EditButtonObserver.prototype={initialize:function(){},onStart:function(){document.body.className="dragging"},onEnd:function(){document.body.className=""}};function editBg(D){var E=[Event.pointerX(D),Event.pointerY(D)];var C=$("playground").cumulativeOffset();
var B=$("dialog-background-inventory");var A=Element.getDimensions(B);B.style.left=(E[0]-C[0]-A.width)+"px";B.style.top=(E[1]-C[1])+"px";B.style.visibility="visible";initBackgrounds()}function savePosition(D){if(D.id){var C=D.id;var A=D.style.left;var B=D.style.top;var E=C.substring(C.indexOf("-")+1)+":"+A.substring(0,A.length-2)+","+B.substring(0,B.length-2)+","+D.style.zIndex+"/";
element_data[D.id]=E}}function attachStickerObserver(A){Event.observe("sticker-"+A+"-edit","click",function(D){Event.stop(D);var B=$("remove_sticker_id");B.value="sticker-"+A;var E=$("dialog-edit-sticker");var C=$("sticker-"+A);E.style.top=C.style.top;E.style.left=C.style.left;bringElementOnTop(E);E.show()
},false)}function clearDraggables(){Draggables.drags.each(function(A){A.destroy()})}function isEditModeDisabled(A){return A.responseText=="EDIT_MODE_DISABLED"}var cancelObserver=function(A){Event.stop(A);cancelEditing()};var saveStart=0;var saveObserver=function(A){Event.stop(A);if(showSaveOverlay()){saveStart=new Date().getTime();
new Ajax.Updater("edit-save",habboReqPath+getSaveEditingActionName(),{method:"post",evalScripts:true,postBody:generatePostBody()})}};function waitAndGo(B){var C=new Date().getTime();var A=C-saveStart;if(A<1000){A=1000}window.setTimeout(function(){location.href=B},A)}function generatePostBody(){var E="";
var D="";var A="";var B=element_data.background;$("playground").select(".movable").each(function(F){if(Element.hasClassName(F,"stickie")){value=element_data[F.id];if(value){E+=value}}else{if(Element.hasClassName(F,"sticker")){value=element_data[F.id];if(value){D+=value}}else{if(Element.hasClassName(F,"widget")){value=element_data[F.id];
if(value){A+=value}}}}});var C="";if(D.length>0){C="stickers="+D}if(E.length>0){if(C.length>0){C+="&"}C+="stickienotes="+E}if(A.length>0){if(C.length>0){C+="&"}C+="widgets="+A}if(B!=null){if(C.length>0){C+="&"}C+="background="+B}return C}function initEditToolbar(){Event.observe($("save-button"),"click",saveObserver,false);
Event.observe($("cancel-button"),"click",cancelObserver,false)}function initMovableItems(){clearDraggables();$("playground").select(".movable").each(function(A){new Draggable(A,{handle:A.id+"-handle",revert:isNotWithinPlayground,starteffect:Prototype.emptyFunction,endeffect:function(B){if(!isNotWithinPlayground(B)){bringElementOnTop(B);
savePosition(B)}},zindex:9000});new ZetaWatcher(A)});Draggables.addObserver(new EditButtonObserver());initDraggableDialogs()}function placeWidget(B,A){if(!isElementLimitReached()){doPlaceWidget(B,A);closeWidgetInventory();Overlay.hide()}}function doPlaceWidget(B,A){if(!isElementLimitReached()){new Ajax.Request(habboReqPath+"widget_add.php",{parameters:{widgetId:B,privileged:A,zindex:getNextZIndex()},onSuccess:function(C,D){$("playground").insert(C.responseText);
initMovableItems();Element.hide($("widget-"+D.id));new Effect.Appear($("widget-"+D.id))},onFailure:function(C){showEditErrorDialog()}})}}function changeBg(B,A){closeBackgroundInventory();Overlay.hide();var D=$("playground").select(".movable");var C=D.length;if(C==0){doChangeBg(B,A)}else{D.each(function(E){window.setTimeout(function(){new Effect.Fade(E,{duration:0.5});
C=C-1;if(C==0){window.setTimeout(function(){doChangeBg(B,A)},800)}},300+Math.floor(Math.random()*500))})}}function doChangeBg(B,A){element_data.background=A+":"+B;$("playground").select(".movable").each(function(C){C.hide();window.setTimeout(function(){new Effect.Appear(C,{duration:0.8})},200+Math.floor(Math.random()*2500))
});$("mypage-bg").className=B}function placeImageOnPage(A){if(!isElementLimitReached()){doPlaceImageOnPage(A);closeStickerInventory();Overlay.hide()}}function doPlaceImageOnPage(B,C){if(!isElementLimitReached()){var A={selectedStickerId:B,zindex:getNextZIndex()};if(C){A.placeAll="true";A.elementCount=getElementCount()
}new Ajax.Request(habboReqPath+"store.php?key=place_sticker",{parameters:A,evalScripts:true,onSuccess:function(F,E){if(isEditModeDisabled(F)){editModeDisabledDialog.show()}else{$("playground").insert(F.responseText);for(var D=0;D<E.length;D++){Element.hide($("sticker-"+E[D]));new Effect.Appear($("sticker-"+E[D]))
}initMovableItems()}}})}}var editMenuOpen=false;var chosenElement=null;function openEditMenu(E,H,D,B,A){Event.stop(E);closeEditMenu();var G=$(B).cumulativeOffset();var F=$("edit-menu");F.style.top=(G[1]-5)+"px";F.style.left=(G[0]-5)+"px";editMenuOpen=true;chosenElement={id:H,type:D,elementId:B};if(D=="widget"||D=="stickie"){var C=$(D+"-"+H);
updateSkinMenu(findFirstDivChild(C));if(Element.hasClassName(C,"ProfileWidget")||Element.hasClassName(C,"GroupInfoWidget")){$("edit-menu-remove").style.display="none"}if(Element.hasClassName(C,"RatingWidget")){$("rating-edit-menu").style.display="block"}if(Element.hasClassName(C,"GuestbookWidget")){$("edit-menu-gb-availability").style.display="block"
}if(Element.hasClassName(C,"TraxPlayerWidget")){$("edit-menu-trax-select").style.display="block";populateTraxSelect()}if(Element.hasClassName(C,"HighscoreListWidget")){$("highscorelist-edit-menu").show();(WidgetRegistry.getWidgetById(H)).selectGameId()}if(D=="stickie"){$("edit-menu-stickie").style.display="block"
}}if(A){$("edit-menu-remove-group-warning").style.display="block"}}function closeEditMenu(){var A=$("edit-menu");A.style.left="-1500px";editMenuOpen=false;chosenElement=null;$("edit-menu-remove").style.display="block";$("edit-menu-skins").style.display="none";$("edit-menu-stickie").style.display="none";
$("rating-edit-menu").style.display="none";$("edit-menu-remove-group-warning").style.display="none";$("edit-menu-gb-availability").style.display="none";$("edit-menu-trax-select").style.display="none";$("highscorelist-edit-menu").hide()}function updateSkinMenu(A){$A($("edit-menu-skins-select").options).each(function(B){if(A.className.substring(7)==(B.id.substring(23))){$("edit-menu-skins-select").selectedIndex=B.index
}});$("edit-menu-skins").style.display="block"}function handleGuestbookPrivacySettings(A){Event.stop(A);if(chosenElement){new Ajax.Request(habboReqPath+"/myhabbo/guestbook/configure",{parameters:{widgetId:chosenElement.id}});closeEditMenu()}}function handleTraxplayerTrackChange(A){var B=$F("trax-select-options");
Event.stop(A);if(chosenElement&&B){new Ajax.Updater("traxplayer-content",habboReqPath+"myhabbo/trax_select_song.php",{parameters:{songId:B,widgetId:chosenElement.id},evalScripts:true});closeEditMenu()}else{void (0)}}function populateTraxSelect(){var A=$("trax-select-options");var C=$("trax-select-options-temp");
if(A.options.length==0&&C){var F=C.cloneNode(true);var E=F.options.length;for(var D=0;D<E;D++){var B=F.options[D].cloneNode(true);A.appendChild(B)}}if(!C){A.hide()}}function handleEditRemove(B){Event.stop(B);if(chosenElement){var A;var C={};if(chosenElement.type=="sticker"){A="delete_sticker.php";
C.stickerId=chosenElement.id}else{if(chosenElement.type=="widget"){A="widget_delete.php";C.widgetId=chosenElement.id}else{if(chosenElement.type=="stickie"){A="delete_stickie.php";C.stickieId=chosenElement.id}}}if(A){new Ajax.Request(habboReqPath+A,{parameters:C,onComplete:function(D){setTimeout(function(){D.responseText.evalScripts()
},10);if(isEditModeDisabled(D)){editModeDisabledDialog.show()}else{new Effect.Fade(chosenElement.type+"-"+chosenElement.id,{afterFinish:function(E){Element.remove(E.element)}});loadWebStore(function(){if(window.WebStore&&WebStore.Inventory.inventoryOpened){WebStore.Inventory.waitingForReload=true}})}closeEditMenu()
}})}}}function findFirstDivChild(A){var B=A.firstChild;while(B.nodeName!="DIV"){B=B.nextSibling}return B}function handleEditSkinChange(C){Event.stop(C);if(chosenElement){var A,B;var D={skinId:$F("edit-menu-skins-select")};if(chosenElement.type=="widget"){A=habboReqPath+"edit_widget.php";D.widgetId=chosenElement.id
}else{if(chosenElement.type=="stickie"){A=habboReqPath+"edit_stickie.php";D.stickieId=chosenElement.id}}if(A){new Ajax.Request(A,{parameters:D,onComplete:function(F,G){setTimeout(function(){F.responseText.evalScripts()},10);if(isEditModeDisabled(F)){editModeDisabledDialog.show()}else{var E=$(G.type+"-"+G.id);
window.setTimeout(function(){new Effect.Fade(E,{duration:0.3});window.setTimeout(function(){E.hide();window.setTimeout(function(){new Effect.Appear(E,{duration:0.5,afterFinish:function(){if(isNotWithinPlayground(E)){var J=$("playground");var H=Element.getDimensions(J);var I=Element.getDimensions(E);if(E.offsetTop+I.height>H.height){E.style.top=(H.height-I.height-2)+"px"
}if(E.offsetLeft+I.width>H.width){E.style.left=(H.width-I.width-2)+"px"}if(E.offsetTop<0){E.style.top=0}if(E.offsetLeft<0){E.style.left=0}savePosition(E)}}})},200);findFirstDivChild(E).className=G.cssClass},400)},100)}}})}}closeEditMenu()}function getElementsInInvalidPositions(){var A=[];$("playground").select(".movable").each(function(B){if(isNotWithinPlayground(B)){A.push(B)
}});return A}function showHabboHomeMessageBox(E,D,C){Overlay.show();var A=Dialog.createDialog("myhabbo-message",E,"9003");var B=Builder.node("a",{href:"#",className:"new-button"},[Builder.node("b",C),Builder.node("i")]);Dialog.appendDialogBody(A,Builder.node("p",D));Dialog.appendDialogBody(A,Builder.node("p",[B]));
Event.observe(B,"click",function(F){Event.stop(F);closeHabboHomeMessageBox()},false);Overlay.move("9002");Dialog.makeDialogDraggable(A);Dialog.moveDialogToCenter(A)}function closeHabboHomeMessageBox(){Element.remove("myhabbo-message");Overlay.hide()}function previewEsticker(D,B,E,C,A){if(window.WebStore){WebStore.StickerEditor.preview({gender:D,figure:B,pose:E,gesture:C,bdirection:A})
}}function closeEditor(){if(window.WebStore){WebStore.close()}};