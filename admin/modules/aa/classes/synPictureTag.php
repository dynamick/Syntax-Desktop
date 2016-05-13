<?php
/*************************************
* class INPUTFILE                    *
* Create a input type="file" obj     *
**************************************/
class synPictureTag extends synElement {

  var $mat;

  //constructor(name, value, label, size, help)
  function __construct($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      if(isset($_REQUEST[$n])) $this->value = $_REQUEST[$n];
    } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR(".$this->size.") NOT NULL";

    $this->configuration();
  }


  function tags($media_id) {
    global $db;
    $qry="SELECT * FROM tags JOIN tagged ON tags.id=tagged.tag_id WHERE tagged.media_id = ".$media_id;
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $tags[] = $arr["tag"];
    }
    return implode(", ",$tags);
  }

  //private function
  function _html() {
    global $db, ${$this->name}, $synPublicPath;
    $fieldname = $this->name;
    $value = $this->getValue();
    $session_id = urlencode(session_id());
    $ret = "";

    if ($value!="") {
      $qry = "SELECT * FROM media WHERE id=".$value;
      $res=$db->Execute($qry);
      if ($arr=$res->FetchRow()) {
        $filename_parts = pathinfo($arr["filename"]);
        $path = $arr['path'] . '/' . $filename_parts['filename'].'.'.$filename_parts['extension'];
        $src = $synPublicPath.'/thumb.php?src='.$path.'&amp;w=240&amp;h=240&amp;zc=1';
        $ret .= "<div id=\"currentpic\">";
        $ret .= "<h2>Immagine scelta</h2>";
        $ret .= "<div >";
        //$ret .= cleverThumbAlmanacco($arr["path"]."/", $filename_parts["filename"], $filename_parts["extension"], "", $arr["caption"], 240, 240, false, " rel=\"".$arr["id"]."\" class=\"selected\" ");
        $ret .= "<img src=\"{$src}\" alt=\"\" rel=\"{$arr["id"]}\" class=\"selected\"/>";
        $ret .= "</div>";
        $ret .= "<div >";
        $ret .= "<table cellpadding=\"0\" cellspacing=\"0\">";
        $ret .= "<tr><td class=\"label\">Filename: </td><td>".$arr["filename"]."</td></tr>";
        if ($arr["title"]!="") $ret .= "<tr><td class=\"label\">Title: </td><td>".$arr["title"]."</td></tr>";
        if ($arr["caption"]!="") $ret .= "<tr><td class=\"label\">Caption: </td><td>".$arr["caption"]."</td></tr>";
        if ($arr["author"]!="") $ret .= "<tr><td class=\"label\">Author: </td><td>".$arr["author"]."</td></tr>";
        $ret .= "<tr><td class=\"label\">Tags: </td><td>".$this->tags($arr["id"])."</td></tr>";
        $ret .= "</table>";
        $ret .= "</div>";
        $ret .= "</div>";
      }
    }
    $ret .= ${$this->name};
    $ret .= <<<EOF
        <h3>Cerca una nuova immagine</h3>
        <div>Inserisci i tag separati da virgola. Per esempio <em>seconda divisione, 2010, portiere</em>.</div>
        <input type="text" id="tagsearch"/> <button id="ajaxSearch">Cerca</button>
        <input type="hidden" name="$fieldname" id="$fieldname" value="$value"/>
        <div id="tagresult"></div>
        <script type="text/javascript">
          $(function() {
            $("#ajaxSearch").click(function() {
              tags = $("#tagsearch").val()
              if(tags.length < 3) {
                alert('Stringa di ricerca troppo corta.');
                return false;
              } else {
                $.ajax({
                  url: 'ihtml/picture_dispatch.php?session_id=$session_id&tags='+tags,
                  success: function(data) {
            	      $('#tagresult').html(data);
                  },
                  beforeSend: function() {
                  	$("#ajaxSearch").attr('disabled','disabled').html("caricamento...")
                  },
                  complete: function() {
                  	$("#ajaxSearch").removeAttr('disabled').html("Cerca")
                  }
                });
            		return false;
              }
            })

            $("#tagresult img").on('click', function() {
                if ($(this).parent().hasClass("selected")) {
                  $("#tagresult .picture_result").removeClass("selected");
                  $("#$fieldname").val($value);
                } else {
                  $("#tagresult .picture_result").removeClass("selected");
                  $(this).parent().addClass("selected")
                  $("#$fieldname").val($(this).attr('rel'));
                }
             });

          });
        </script>
EOF;

    return "<div class=\"pictag-container\">$ret</div>";;

  }


  //get the label of the element
  function getCell() {
  	global $synAbsolutePath, $db;
    $value = $this->value;
    $ret = '';
    if (!empty($value)) {
      $qry = "SELECT * FROM media WHERE id=".$value;
      $res = $db->Execute($qry);
      if ($arr = $res->FetchRow()) {
        $filename_parts = pathinfo( $arr["filename"] );
        $filename = $arr["path"] . "/" . $arr["filename"];
        $ext      = $filename_parts['extension'];
        $size = filesize($synAbsolutePath . $filename );
        $fsize = byteConvert($size);
        list( $w, $h ) = @getimagesize( $synAbsolutePath . $filename );
        $show = "style=\"background-image:url(/public/mat/" . $filename . ");\"";
        //$ret .= "<a class=\"preview\" {$show} onMouseOver=\"openbox('/public/mat/thumb/{$arr["filename"]}')\" onMouseOut=\"closebox()\"><span>".$ext." - <strong>".byteConvert($size)."</strong><br/>".$w."&#215;".$h."px</span></a>";
        if ($size < 100000)
          $show = "style=\"background-image:url('{$filename}');\"";
        $ret .= "<a class=\"preview\" {$show} href=\"{$filename}\" data-ext=\"{$ext}\" data-size=\"{$fsize}\" data-width=\"{$w}\" data-height=\"{$h}px\">&nbsp;</a>";

      } else $ret="<span style='color: gray'>Empty</span>";
    }
    return $ret;
    //die;
  }



  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class inputfile
?>
