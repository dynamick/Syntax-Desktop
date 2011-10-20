// check browsers
var ua = navigator.userAgent;
var opera = /opera [56789]|opera\/[56789]/i.test(ua);
var ie = !opera && /MSIE/.test(ua);
var ie50 = ie && /MSIE 5\.[01234]/.test(ua);
var ie6 = ie && /MSIE [6789]/.test(ua);
var ieBox = ie && (document.compatMode == null || document.compatMode != "CSS1Compat");
var moz = !opera && /gecko/i.test(ua);
var nn6 = !opera && /netscape.*6\./i.test(ua);


/********************************
*  INIZIALIZZAZIONE DEL DESKTOP *
*********************************/
function desktopInit() {

  changeWallpaper(3);
  
  // set default css file to use
  //Menu.prototype.cssFile = "styles/"+title+"/desktop.css";

  //show the clock
  show_clock();
  
  //the default status Message
  window.status="SyntaxDesktop - Content Management System";
  
  //inizializzo il menu
  if (TransMenu.isSupported()) {TransMenu.initialize();}
  
  //Catch di Eventi:
  //ONRESIZE
  window.onresize=function (){
      var w=winSize('W');
      var h=winSize('H')
      if (contentarea!=null) setSize(contentarea,w,h-(topBarHeight+bottomBarHeight));
  }
}



/********************************
* SWITCH WALLPAPER DEL DESKTOP  *
*********************************/
function changeWallpaper(theme) {
  if((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >=4)){ 
    windowwidth = window.innerWidth;  windowheight = window.innerHeight;
  } else if((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >=4)){ 
    windowwidth = document.body.clientWidth;  windowheight = document.body.clientHeight;
  }else{ windowwidth = 800;  windowheight = 600; }

  theme=theme.toString();
  switch (theme) {
    case '1': img="images/wallpaper/desktop_bg.gif";break;
    case '2': img="images/wallpaper/night.jpg";break;
    case '3': img="images/wallpaper/blue.jpg";break;
    case '4': img="images/wallpaper/Field.jpg";break;
    case '5': img="images/wallpaper/deleterix.jpg";break;
    default: img="images/wallpaper/desktop_bg.gif";break;
  }
  //document.body.style.backgroundImage="url("+img+")"; 
  document.body.style.backgroundImage="url(includes/php/wallpaper.php?img="+img+"&width="+windowwidth+"&height="+windowheight+")";
  document.body.style.backgroundPosition="top left";
  //document.body.style.backgroundRepeat="no-repeat";
  document.body.style.backgroundColor="#0858BF";
}



/*************************************************
* CALCULATE WINDOW SIZE                          *
*************************************************/
function winSize(what){
 //if(what=='H')return document.body.clientHeight
 //if(what=='W')return document.body.clientWidth

 var docRect = getDocumentRect(); 
 if(what=='H')return docRect.height;
 if(what=='W')return docRect.width;
}

function createElement(x,y,w,h) {
    var contentarea = new Object();
    contentarea=document.createElement("iframe");
    contentarea.style.position="absolute";
    contentarea.style.left=x+"px";
    contentarea.style.top=y+"px";
    contentarea.style.width=w+"px";
    contentarea.style.height=h+"px";
    contentarea.style.display="block";
    contentarea.style.backgroundColor="#ffffff";
    document.body.appendChild(contentarea);
    return contentarea;
}
function setSize (elm, w,h) {
    elm.style.width=w+"px";
    elm.style.height=h+"px";
}

// mozilla bug! scrollbars not included in innerWidth/height
function getDocumentRect(el) {
  return {
    left:  0,
    top:  0,
    width:  (ie ?
          (ieBox ? document.body.clientWidth : document.documentElement.clientWidth) :
          window.innerWidth
        ),
    height:  (ie ?
          (ieBox ? document.body.clientHeight : document.documentElement.clientHeight) :
          window.innerHeight
        )
  };
}

/*************************************************
* CREATE THE CONTENT AREA IN THE MIDDLE OF PAGE  *
*************************************************/
function createWindow(titolo,file) {
  var w=winSize('W');
  var h=winSize('H')
  //if the first time, instance a new contentarea
  if (contentarea==null) 
    contentarea=createElement(0,topBarHeight,w,h-(topBarHeight+bottomBarHeight));
  else {
    removeWindow();
    contentarea=createElement(0,topBarHeight,w,h-(topBarHeight+bottomBarHeight));
  }
  
  contentarea.style.border=0;
  contentarea.src=file;
  setDocTitle(titolo);
  if (window.top.document.getElementById("closeBtn")==null) setWindowButtons();
}

function setWindowButtons() {
  var doc=window.top.document;
  var newElm=doc.createElement("div");
  newElm.innerHTML="<div id=\"close\" onclick=\"removeWindow(); this.parentNode.removeChild(this);\"><img src=\"modules/aa/images/synClose.gif\" onmouseover=\"this.src='modules/aa/images/synClose_over.gif'\" onmouseout=\"this.src='modules/aa/images/synClose.gif'\" /></div>";
  newElm.id="closeBtn";
  newElm.style.zindex="255";
  newElm.style.position="absolute";
  newElm.style.top="35";
  newElm.style.right="21";
  doc.body.appendChild(newElm);
}


function createWindow2(arg1,arg2) {
  alert(arg1);
  alert(arg2);
}


function removeWindow() {
    var ca=window.top.contentarea;
    var closebtn=window.top.document.getElementById("closeBtn");

    if (closebtn!=null) closebtn.parentNode.removeChild(closebtn);
    if (ca.parentNode!=null) ca.parentNode.removeChild(ca); 
}

/*************************************************
* Fade in                                        *
*************************************************/
function fadeIn(obj) {
  obj.setAlpha(1);
  //to level, type, steps, speed
  obj.alphaTo(100,2,10,1);
}

/*************************************************
* Fade out                                       *
*************************************************/
function fadeOut(obj) { 
  //to level, type, steps, speed
  obj.alphaTo(0,1,10,1);
}

/*************************************************
* Set the title of the current window            *
*************************************************/
function setDocTitle(titolo) {
/*
  //status bar
  window.status=titolo;
  //document title
  document.title=docTitle+" - "+titolo;
*/
}

