var RoomListHabblet=Class.create();
RoomListHabblet.prototype={initialize:function(roomListContainerId,toggleMoreDataId,moreDataContainerId){this.opened=false;
this.roomListElementClassName="rooms";
this.roomListContainer=$(roomListContainerId);
this.toggleMoreDataId=toggleMoreDataId;
this.moreDataContainerId=moreDataContainerId;
var self=this;
if($(this.toggleMoreDataId)){Event.observe($(this.toggleMoreDataId),"click",function(e){Event.stop(e);
self.toggleMoreData()
})
}this.randomizeRoomList();
$$("#"+roomListContainerId+" ul.habblet-list").each(function(item){Event.observe(item,"click",function(event){var target=Event.element(event);
if(target.tagName.toUpperCase()=="A"){return 
}Event.stop(event);
var roomId=$(target).up("li").down("span.enter-room-link").readAttribute("roomid");
if(roomId){this.roomForward(roomId)
}}.bind(self))
})
},toggleMoreData:function(){if(this.opened){new Effect.BlindUp($(this.moreDataContainerId));
$(this.toggleMoreDataId).innerHTML=L10N.get("show.more");
$(this.toggleMoreDataId).removeClassName("less");
this.opened=false
}else{new Effect.BlindDown($(this.moreDataContainerId));
$(this.toggleMoreDataId).addClassName("less");
$(this.toggleMoreDataId).innerHTML=L10N.get("show.less");
this.opened=true
}},randomizeRoomList:function(toggleDetailsClassName,roomListAccordion){var sourceElements=this.roomListContainer.getElementsByClassName(this.roomListElementClassName);
if(sourceElements){var randomElements=new Array();
for(i=0;
i<sourceElements.length;
i++){var index;
do{index=Math.round(Math.random()*sourceElements.length)
}while(!sourceElements[index]);
randomElements[i]=sourceElements[index].innerHTML;
delete sourceElements[index]
}var destinationElements=this.roomListContainer.getElementsByClassName(this.roomListElementClassName);
for(i=0;
i<destinationElements.length;
i++){destinationElements[i].innerHTML=randomElements[i]
}}},roomForward:function(roomId){window.roomForward("client.php?forwardId=2&roomId="+roomId,roomId,"private")
}}
