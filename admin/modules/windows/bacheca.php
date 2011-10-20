<body style="margin: 0px; background: white;">
<div style="background: white; position: relative;z-index: 100;">
<?
  include("../aa/classes/fckeditor.php");
    $oFCKeditor = new FCKeditor ;
    $oFCKeditor->Value = $this->value;
    $oFCKeditor->ToolbarSet = "Default";
    $oFCKeditor->CreateFCKeditor( $this->name, "100%", $this->size ) ;
?>
</div>
</body>

