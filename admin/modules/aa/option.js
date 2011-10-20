var toolArray;
var actionArray=Array();
  /********************************************************
  * ONLOAD function:
  * this initialize the toolbar, add button and action 
  * to button
  *********************************************************/
    onload=function(){
    }

  /********************************************************
  * ONRESIZE function:
  * this adapt the toolbar width with the body width
  *********************************************************/
    //onresize = function() {toolbar.setW(core.bodyWidth());}
  
   
  /********************************************************
  * SHOW and HIDE the frame:
  * this hide or show the left area of tab
  *********************************************************/
  function Frame() {
    if (parent.framesetBottom.cols=="0,*,0") { FrameSize = "0,*,"+parent.fixedWidth; }
    else { FrameSize = "0,*,0" }
	  parent.framesetBottom.cols = FrameSize;
  }

  if (parent.framesetBottom) {
    parent.framesetBottom.cols="0,*,"+parent.fixedWidth;
  }
  
  function toggle(elm,img) {
    var box=document.getElementById(elm);
    if (box.style.display=="none") {
      box.style.display="inline";
      img.src="images/minimizeBox.gif";
    } else {
      box.style.display="none";
      img.src="images/maximizeBox.gif";
    }
  }
  
  /***********************************************************
  /*            BUTTONS                                      *
  /**********************************************************/
  function buttonAction(buttonObj,act,what){
   if(buttonObj.className!='disabled'){
    switch(act){
     case 'raised':    buttonObj.className='raised';imageClass(buttonObj,'icons2');break;
     case 'raised2':   buttonObj.className='raised2';imageClass(buttonObj,'icons2');break;
     case 'flat':      buttonObj.className='flat';imageClass(buttonObj,'icons');break;
     case 'pressed':   buttonObj.className='pressed';imageClass(buttonObj,'icons2');break;
     case 'pressed2':  buttonObj.className='pressed2';imageClass(buttonObj,'icons2');break;
    }
    actions(what)
   }else{
    return false
   }
  }
  
  function imageClass(obj,cls){
   img=obj.childNodes[0]
   if(img.tagName=='IMG'){
    img.className=cls
   }
  }
  
  var buttonCounter=0
  function makeToolBar(where,vWidth){
   var buttonCounter=0
   str=''
   breaker=''
   vRaised='raised'
   vPressed='pressed'
   toolBarObj=document.getElementById(where)
   toolBarObj.noWrap=true
   if(vWidth){
    str+='<span class="sidebar"></span>'
    breaker='<br>'
    vRaised='raised2'
    vPressed='pressed2'
   }else{
    vWidth=''
   }
   for(a=0;a<toolArray.length;a+=5){
    txt='&nbsp;';img='';dis='flat';
    if(toolArray[a].indexOf('seperator')>=0){
     if(vWidth){
      str+='<hr width="'+vWidth+'" class="hr">'
     }else{
      str+='&nbsp;<span class="'+toolArray[a]+'"></span>&nbsp;'
     }
    }else{
     if(toolArray[a]){txt='&nbsp;'+toolArray[a]+'&nbsp;'}
     if(toolArray[a+1]){img='<img src="'+toolArray[a+1]+'" class="icons" align="absmiddle" id="button_img_'+buttonCounter+'">'}
     if(toolArray[a+4]){dis='disabled'}
     str+='<span id="button_'+buttonCounter+'" type="button" class="'+dis+'" style="width:'+vWidth+';" onmouseover="buttonAction(this,\''+vRaised+'\')" onmouseout="buttonAction(this,\'flat\')" onmousedown="buttonAction(this,\''+vPressed+'\')" onmouseup="buttonAction(this,\''+vRaised+'\',\''+toolArray[a+2]+'\')" align="center" onselectstart="return false" title="'+toolArray[a+3]+'">'+img+txt+'</span>'+breaker
     buttonCounter+=1
    }
   }
   toolBarObj.innerHTML+=str
  }
  
  function setButtonClass(what,cls){
   i=document.getElementsByTagName('FONT')
   for(a=0;a<i.length;a++){
    if(i[a].type=='button'){
     if(i[a].innerHTML.indexOf(what)>=0){
      i[a].className=cls
     }
    }
   }
  }

  function actions(what){
    if(what){
     switch(true){
  	// button actions....
      case what=='newBtn':eval(actionArray[0]);break;
      case what=='saveBtn':eval(actionArray[1]);break;
      case what=='removeBtn':eval(actionArray[2]);break;
      case what=='switchBtn':eval(actionArray[3]);break;
      case what=='refreshBtn':eval(actionArray[4]);break;
      case what=='homeBtn':eval(actionArray[5]);break;
      case what=='backBtn':eval(actionArray[6]);break;
     }
     
    }
  }
  
  