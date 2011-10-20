<?

/*************************************
* class INPUTFILE                    *
* Create a input type="file" obj     *
**************************************/
class synMultiPictureTag extends synElement {

  var $mat;

  //constructor(name, value, label, size, help)
  function synMultiPictureTag($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $_REQUEST[$n]; } else $this->value = $v;
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
    global $db, ${$this->name};
    $fieldname = $this->name;
    $value = $this->getValue();
    $session_id = urlencode(session_id());
  
    ob_start();
    ?>
        <!-- Product list HTML -->   
        <div id="smartcart" class="scContainer2">   
        
          <h3>Cerca una nuova immagine</h3>
          <div>Inserisci i tag separati da virgola. Per esempio <em>seconda divisione, 2010, portiere</em>.</div>
          <div>
            <input type="text" id="tagsearch_<?=$fieldname?>"/> <button id="ajaxSearch_<?=$fieldname?>">Cerca</button>  
            <input type="hidden" name="<?=$fieldname?>" id="<?=$fieldname?>" value="<?=$value?>"/>  
          <div>              		
          <div id="sc_productlist" class="scProductList"></div>
          <!-- Cart HTML -->
          <div id="sc_cart" class="scCart">
            <h3 class="clear">Immagini selezionate</h3>
            <!-- Cart List: Selected Products are listed inside div below -->
            <div id="sc_cartlist" class="scCartList"></div>
          </div>
          <!-- End Cart HTML -->
       </div>
       
        <script type="text/javascript">
          $(function() {
          
            function updateValue() {
              value = ''
              $('#sc_cartlist .pic_id').each(function(index) {
                pic = $(this)
                if (value=='') value = pic.val()
                else value = value + '|' + pic.val()
              })
              return value;
            }
          
            $("#ajaxSearch_<?=$fieldname?>").click(function() {
              tags = $("#tagsearch_<?=$fieldname?>").val()
          
              $.ajax({
                url: 'ihtml/picture_dispatch_multiple.php?session_id=<?=$session_id?>&tags='+tags,
                success: function(data) {
          	      $('#sc_productlist').html(data);
                },
                beforeSend: function() {
                	$("#ajaxSearch_<?=$fieldname?>").attr('disabled','disabled').html("caricamento...")
                },
                complete: function() {
                	$("#ajaxSearch_<?=$fieldname?>").removeAttr('disabled').html("Cerca")
                  $("#sc_productlist input[type=button]").click(function() {
                      picture_container = $(this).parent()
                      picture_container.appendTo('#sc_cartlist');   
                      $(this).val("Elimina").click(function() { 
                        $(this).parent().remove()
                        $("#<?=$fieldname?>").val(updateValue());       
                      })
                      $("#<?=$fieldname?>").val(updateValue());       
                    }
                  )

                }
              });
          		return false;
            })
            
            // INIT: populate the selected list
            if ($("#<?=$fieldname?>").val()!="" ) {
              $.ajax({
                url: 'ihtml/picture_dispatch_multiple.php?session_id=<?=$session_id?>&selected='+$("#<?=$fieldname?>").val(),
                success: function(data) {
          	      $('#sc_cartlist').html(data);
                },
                beforeSend: function() {
                	$("#ajaxSearch_<?=$fieldname?>").attr('disabled','disabled').html("caricamento...")
                },
                complete: function() {
                	$("#ajaxSearch_<?=$fieldname?>").removeAttr('disabled').html("Cerca")
                  $("#sc_cartlist input[type=button]").val("Elimina").click(function() { 
                    $(this).parent().remove()
                    $("#<?=$fieldname?>").val(updateValue());       
                  })
                }  // complete
              });  // fine ajax
            } 
          
          });
        </script>       
       
    <?
    $ret .= ob_get_contents();
    ob_end_clean();                

    return "<div class=\"pictag-container\">$ret</div>";;
  }

  //get the label of the element
  function getCell() {
  	global $synAbsolutePath, $db;
    $value = $this->value;
    
    if ($value!="") {
      $selected=str_replace('|',',',$value);    
      $qry = "SELECT * FROM media WHERE id IN (".$selected.")";
      $res=$db->Execute($qry);
      while ($arr=$res->FetchRow()) {
        $filename_parts = pathinfo($arr["filename"]);
        $size=filesize($synAbsolutePath.$arr["path"]."/".$arr["filename"]);
        list($w,$h)=@getimagesize($synAbsolutePath.$arr["path"]."/".$arr["filename"]);
        $show="style=\"background-image:url(/public/mat/thumb/".$arr["filename"].");\"";
        $ret .= "<a class=\"preview\" ".$show." onMouseOver=\"openbox('/public/mat/thumb/".$arr["filename"]."')\" onMouseOut=\"closebox()\"><span>".$ext." - <strong>".byteConvert($size)."</strong><br/>".$w."&#215;".$h."px</span></a>";
      } 
    } else $ret="<span style='color: gray'>Empty</span>";
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
