    //reference to 'frameToolbar' frame
    var f=window.parent.option;



    //*****************************BUTTON FUNCTION****************************
    
    //define the action of the button

    function action(txtBtn, act) {
      if (txtBtn=="newBtn") f.actionArray[0]=act;
      if (txtBtn=="saveBtn") f.actionArray[1]=act;
      if (txtBtn=="removeBtn") f.actionArray[2]=act;
      //if (txtBtn=="switchBtn") f.actionArray[3]=act;
      if (txtBtn=="refreshBtn") f.actionArray[3]=act;
      if (txtBtn=="homeBtn") f.actionArray[4]=act;
      if (txtBtn=="backBtn") f.actionArray[5]=act;
    }

    function getButton(n) {
      return f.document.getElementById("button_"+n);
    }
    //init toolbar
    //function initToolbar (newBtn,saveBtn,removeBtn,switchBtn,refreshBtn,homeBtn,backBtn) {
    function initToolbar (newBtn,saveBtn,removeBtn,refreshBtn,homeBtn,backBtn) {
      f.innerHTML="";
      //window.parent.option.document.getElementById("button_0").style.display="none";
      /*
      if (!newBtn) getButton(0).className="disabled"; else getButton(0).className="flat";
      if (!saveBtn) getButton(1).className="disabled"; else getButton(1).className="flat";
      if (!removeBtn) getButton(2).className="disabled"; else getButton(2).className="flat";
      if (!switchBtn) getButton(3).className="disabled"; else getButton(3).className="flat";
      if (!refreshBtn) getButton(4).className="disabled"; else getButton(4).className="flat";
      if (!homeBtn) getButton(5).className="disabled"; else getButton(5).className="flat";
      if (!backBtn) getButton(6).className="disabled"; else getButton(6).className="flat";
      */
      
      //alert(saveBtn);//.style.visibility="hidden";
      
      if (!newBtn) getButton(0).style.visibility="hidden"; else getButton(0).style.visibility="visible";
      if (!saveBtn) getButton(1).style.visibility="hidden"; else getButton(1).style.visibility="visible";
      if (!removeBtn) getButton(2).style.visibility="hidden"; else getButton(2).style.visibility="visible";
      //if (!switchBtn) getButton(3).style.visibility="hidden"; else getButton(3).style.visibility="visible";
      if (!refreshBtn) getButton(3).style.visibility="hidden"; else getButton(3).style.visibility="visible";
      if (!homeBtn) getButton(4).style.visibility="hidden"; else getButton(4).style.visibility="visible";
      if (!backBtn) getButton(5).style.visibility="hidden"; else getButton(5).style.visibility="visible";
    }
    
    //change the class name of the cell
    function sel(obj) {
      obj.old=obj.className;
      obj.className='rowsel';
    }
    //restore the class name of the cell
    function desel(obj) {
      obj.className=obj.old;
    }

    //*****************************CONTENT EDITOR*******************************
    //the text in a cell is to be changed
    function contentStore(obj) {
       obj.old=obj.innerHTML;
    }
    //check if a cell content is changed...
    function contentCheck(obj) {
      if (obj.innerHTML!=obj.old) {
        alert(top.str['aa_change']);
        obj.innerHTML=obj.old;
        obj.contentEditable=true;
      }
    }
    //check if a cell content is changed... and save with RPC
    function contentEnter(evt,obj) {
      if (evt.keyCode==13) {
        obj.contentEditable=false;
        obj.old=obj.innerHTML;
        key=obj.getAttribute("key");
        field=obj.getAttribute("field");
        value=obj.innerHTML;
        callServer(key, field, value);
      }
    }

    function contentClick(obj) {
        obj.contentEditable=true;
    }
    
    
    //********************************* PAGING *********************************
    //              ('content.php','content.php','content.php?syntax_next_page=2','content.php?syntax_next_page=21', arrpage, 'Pag 1/21');
    function paging (start, prev, next, end, pages, status) {
      //alert ('Start: '+start.indexOf('?syntax_next_page')+'; Prev: '+prev.indexOf('?syntax_next_page')+'; Next: '+next.indexOf('?syntax_next_page')+'; End: '+end.indexOf('?syntax_next_page'));
      //alert ((next.indexOf('?syntax_next_page'))>0);
      enable('first', start, start.indexOf('?syntax_next_page'));
      enable('prev',  prev,  prev.indexOf('?syntax_next_page'));
      enable('next',  next,  next.indexOf('?syntax_next_page'));
      enable('last',  end,   end.indexOf('?syntax_next_page'));
      
      var list='';
      for(i=0; i<pages.length; i++){ //genera la lista delle pagine
        list += "  <li>"+pages[i]+"</li>\n";
      }
      window.parent.option.document.getElementById("pages").innerHTML=list;
      window.parent.option.document.getElementById("status").innerHTML=status;
    }

    function enable(button, link, status) {//abilita i bottoni della paginazione
      var el = window.parent.option.document.getElementById(button);
      el.className = (status>0 ? 'enabled' : 'disabled');
      el.target = (status>0 ? 'content' : '');
      //el.href = (status>0 ? link : el.href.remove);
      if (status>0) el.href=link; else el.removeAttribute('href');
    }


    //********************************* SEARCH *********************************
    function addSearchField(n, v) {
      if (window.parent.option.document.getElementById("cs_"+n)==null) {
        var sel = window.parent.option.document.getElementById('colsearch');
        var opt=document.createElement('option');
        opt.innerHTML=n;
        opt.value=v;
        opt.id="cs_"+n;
        if (sel.addEventListener) { // per GECKO //
          sel.appendChild(opt);
        } else if (sel.attachEvent) { // per IE //
          sel.insertAdjacentElement("BeforeEnd",opt);
        }
      }
    }

    //********************************* CUSTOM BOX *********************************
    function addBox(id,txt) {

      var el = window.parent.option.document.getElementById(id); 
      //remove old div
      if (el!=null) 
        if (el.parentNode!=null)  
          el.parentNode.removeChild(el);

      if (el==null) {
        var custom = window.parent.option.document.getElementById('custom');
        var div=document.createElement('div');
        div.innerHTML=txt;
        div.id=id;
        //custom.insertAdjacentElement("BeforeEnd",div);
        custom.innerHTML=txt;
      }
    }
    //*****************************FUNCTION FOR IMAGE VIEW LAYER*****************************

    // TO DO: creare al volo il popupbox
    function openbox(thisbox) {
      var box = document.getElementById('popupbox');
      box.style.display = 'block';
      box.innerHTML = "<img src='"+thisbox+"' />";

      document.onmousemove = function(e) {// al movimento del mouse sposto il box
        var canvasW = ((document.documentElement && document.documentElement.clientWidth) ? document.documentElement.clientWidth : window.innerWidth);
        var canvasH = ((document.documentElement && document.documentElement.clientHeight) ? document.documentElement.clientHeight : window.innerHeight);
        var offsetX = box.offsetWidth;
        var offsetY = box.offsetHeight;
        var mouseX = ((window.Event) ? e.pageX : event.clientX)+10;
        var mouseY = ((window.Event) ? e.pageY : event.clientY)+20;
        if((mouseX+offsetX)>canvasW) {
          var diffX = (mouseX+offsetX)-canvasW;
          mouseX = mouseX-diffX;
        }
        if((mouseY+offsetY)>canvasH) {
          var diffY = (mouseY+offsetY)-canvasH;
          mouseY = mouseY-diffY;
        }
        box.style.left = mouseX+"px";
        box.style.top = mouseY+"px";
      };
    }

    function closebox(){
      var box = document.getElementById('popupbox');
      box.style.display = 'none';
    }
    
    //*****************************VARIOUS FUNCTION*****************************
    //Change the content div to a "LOADING" screen...
    function loading() {
      document.getElementById("content").innerHTML="<div style='color: white; font-family: Verdana; font-size: normal; width: 100%; height: 100%; text-align: center; top: 100px; position: relative;'><img src='images/LoadingImages.gif'></div>";
      return true;
    }
    
    //confirm before delete...
		function myconf(o,s) { 
		if (!confirm(top.str['aa_confirmDel']))  
			o.href='#'; 
		}
    
    //change the background image (input: an integer number)
    function changeBg(i) {
      switch (i%3) {
        case 0: bg="url(images/winter.jpg) fixed top left no-repeat";break;
        case 1: bg="url(images/autumn.jpg) fixed top left no-repeat";break;
        default: bg="url(images/summer.jpg) fixed top left no-repeat";break;
      }
      //document.body.style.background=bg;
      //window.parent.option.document.body.style.background=bg;
    }

    
    //*****************************VARIOUS FUNCTION*****************************
    function estensione(nomefile) { 
      return nomefile.substr(nomefile.lastIndexOf(".")+1);
    }
    function previewimg(url,imgname) {
      if ( (estensione(url)!="jpg") && (estensione(url)!="gif") && (estensione(url)!="png") )
        return false; 
        
      //the preview field id is: fieldname+"_preview"
      var obj = document.getElementById(imgname);
      obj.style.width = "auto";
      obj.style.height = "auto";
      obj.style.display = "none";
      obj.src = url;
      //the hidden field id is: fieldname+"_preview"+"Img"
      var objHidden = document.getElementById(imgname+"Img");
      objHidden.value = url;
    }
    function restoreimg(imgname,url) {
      if ( (estensione(url)!="jpg") && (estensione(url)!="gif") && (estensione(url)!="png") )
        return false; 
      var obj=document.getElementById(imgname+"_preview");
      obj.src=url;
      obj.style.width ='';
      obj.style.height ='';
    }
    function checkimg(imgname) {
      var obj = document.getElementById(imgname);
      obj.style.display = "";
      w = obj.offsetWidth;
      h = obj.offsetHeight;
      if (w > 400) {var wh = h/w;w = 400;h = w*wh;}
      if (h > 200) {var hw = w/h;h = 200;w = h*hw;}
      obj.style.width = w;
      obj.style.height = h;
    }

    function debug (txt) {
      var debugDIV = window.parent.tree.document.getElementById('debug');
      if (window.top.synDebug && debugDIV!=null) {
          debugDIV.insertAdjacentHTML("BeforeEnd",txt+"<hr>");
          window.parent.openTreeFrame();
      }
    }
    
    function highlight(obj) {
      obj.oldColor=obj.style.backgroundColor;
      obj.style.backgroundColor='#FFFCA7';
    }
    function highlightRestore(obj) {
      obj.style.backgroundColor=obj.oldColor;
    }    
    
//**************************** SIMPLE TEXTAREA ********************************
    function textLimitCheck(thisArea, fName, maxLength )
	{
		if (thisArea.value.length > maxLength)
		{
			alert(maxLength + ' characters limit. \rExcessive data will be truncated.');
			thisArea.value = thisArea.value.substring(0, maxLength-1);
			thisArea.focus();
		}
		//messageCount.innerText = thisArea.value.length;
    //alert(fName);
		document.getElementById("messageCount_"+fName).innerText = thisArea.value.length;
	}
