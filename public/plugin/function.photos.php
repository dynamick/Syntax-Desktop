<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.lista_schede_foto.php
* Type:     function
* Name:     lista_schede_foto
* Purpose:  Visualizza le schede di questa sezione come un album fotografico
* -------------------------------------------------------------
*/
function smarty_function_photos($params, &$smarty)
{
  global $db,$synAdminPath, $synPublicPath,$synAbsolutePath;
  if (isset($_GET["id"])) return false;
  else {
    $qry="SELECT * FROM photos ORDER BY date DESC";
    $pager = new synPager($db,'photo', "", "", true);
    $res=$pager->Execute($qry,4,"");
    if ($res->RecordCount()==0) return false;
    $count=1;
    ob_start();
?>
    <div >
      <table>
        <tr>
<?
    while ($arr=$res->FetchRow()) {
      $id=$arr["id"];
      $title=$arr["title"];
      $text=$arr["text"];
      $photo=$arr["photo"];
      $data=sql2date($arr["data"]);
      if ($photo!="") {
        $img=$synPublicPath."/mat/photos_photo_id".$id.".".$photo;
        $thumbnail=new Image_Toolbox($synAbsolutePath."/".$img);
        $thumbnail->setResizeMethod('resample');
        $thumbnail->newOutputSize(100,75,1,false,'#FFFFFF');
        //$thumbnail->addImage('./img/logo.png');
        //$thumbnail->blend('right','bottom');
        $resultimg=$synPublicPath."/public/mat/thumb/photos_photo_id".$id.".".$photo;
        $thumbnail->save($synAbsolutePath."/".$resultimg,$photo);
        $rand=rand();
        $filename="<img src=\"$resultimg?rand=$rand\" alt=\"".str_replace("\"","",htmlentities($title))."\" class=\"img\"/>";
      } else $filename="";
      ?>
          <td><div><strong><?=$title?></strong></div>
            <a href="<?=$img?>" target="_blank"><?=$filename?></a>
            <div><?=$text?></div>
          </td>
      <?
      if ($count%2==0) echo "\n</tr><tr>\n";
      $count++;
    } //end of while
?>
        </tr>
      </table>
    </div>
<?
    //stampa i bottoni di navigazione. (imgPath,pageIndex,currentPage)
    echo "<div id=\"pager\">".$pager->renderPager($synAdminPath."/modules/aa/",true,true)."</div>";
    $ret.=ob_get_contents();
    ob_end_clean();
  }

  return $ret;
}




?>
