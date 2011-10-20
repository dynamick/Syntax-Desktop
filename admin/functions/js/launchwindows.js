  core.loadUnit("window");
  core.loadUnit("menubar");

    function createWindow(titolo,file) {
      var w=core.bodyWidth();
      var h=core.bodyHeight();
      elm1=core.createElm(null,0,30,w,h-60,"#4E6FD6","#ffffff", "IFRAME");
      elm1.style.zIndex=20;
      elm1.src=file;
    }
