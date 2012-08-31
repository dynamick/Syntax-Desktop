<?php
/*
 Use this function to create friendly attachment URLs

 1) Create an .htaccess file inside the attachment's folder, like this:
 # BEGIN
 <IfModule mod_rewrite.c>
 RewriteEngine On
 RewriteCond %{REQUEST_FILENAME} !-d
 RewriteRule ^[^/]*.$ /public/doc.php [L]
 </IfModule>
 # END

 2) Modify the above code setting the right location of doc.php (ie. /public/doc.php)
 
 3) Configure CAPTION_FIELD_NAME constant with the db column name you want to replace the filename with.
 
 3) Link the attachment on the html code the standard way.
 
 RESULT: the attachment filename will be rewritten with the name fetched from the database.
 
*/ 

define (CAPTION_FIELD_NAME, "title");

// ----------------------------------
// Below this, edit at your own risk!
// ----------------------------------

include($_SERVER["DOCUMENT_ROOT"]."/public/config/cfg.php");

function dl_file($file){

    //First, see if the file exists
    if (!is_file($file)) { die("<b>404 File not found!</b>"); }

    //Gather relevent info about file
    $len = filesize($file);
    $filename = basename($file);
    //$ctype=mime_content_type($file);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    //This will set the Content-Type to the appropriate setting for the file
    switch( $file_extension ) {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "mp3": $ctype="audio/mpeg"; break;
      case "wav": $ctype="audio/x-wav"; break;
      case "mpeg":
      case "mpg":
      case "mpe": $ctype="video/mpeg"; break;
      case "mov": $ctype="video/quicktime"; break;
      case "avi": $ctype="video/x-msvideo"; break;

      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
      case "php":
      case "htm":
      case "html":
      case "txt": 
      default: die("<b>Cannot be used for ".$file_extension." files!</b>"); break;
      //default: $ctype="application/force-download";
    }

   $nicefilename = getNiceFileName($filename,$file_extension);

   //Begin writing headers
   header("Pragma: public");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: public");
   header("Content-Description: File Transfer");

   //Use the switch-generated Content-Type
   header("Content-Type: {$ctype}");

   //Force the download
   header("Content-Disposition: attachment; filename={$nicefilename}.{$file_extension}");
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".$len);

   @readfile($file);
   exit;
}

function getNiceFileName($filename,$ext) {
  global $db;
  
  $t = multiTranslateDictionary(array('doc_riservato'));

  session_start();
  $userid    = $_COOKIE['web_user']['id'];
  $usergroup = $_COOKIE['web_user']['group'];

  if($usergroup != "") {
    $usergroup = explode('|', $usergroup);
  } else $usergroup = array();

  $arr=explode("_",$filename);
  if ($arr[0]=="aa") {$arr[0]="aa_page"; $arr[2]=$arr[3];}
  $qry="SELECT ".CAPTION_FIELD_NAME.", enabled_groups, status FROM ".$arr[0]." WHERE id=".intval(substr($arr[2],2));
  @$res=$db->Execute($qry);
  if ($res!==false) {
    $row    = $res->FetchRow();
    $titolo = translateSite($row[CAPTION_FIELD_NAME]);
    $status = $row["status"];
    if($row['enabled_groups']) $owner    = explode('|', $row['enabled_groups']);
    else $owner = Array(); 

    $intersect = array_intersect($usergroup, $owner);

    if(is_array($intersect) && count($intersect) > 0) $have_same_group = true;
    else $have_same_group = false;
   
    if( !isset($_SESSION['synUser']) and !(
      ($status == 'public') ||
      ($status == 'protected' && $userid != '') ||
      (($status == 'private' || $status == 'secret') && ($have_same_group && $userid != '') )   
    )){     
      die("<b>{$t['doc_riservato']}</b>");
      exit;
    }

    if ($titolo=="") $titolo=$filename; // if something has been found, use it as filename...
    else $titolo=str_replace(" ","-",trim(strtolower(ereg_replace("[^[:alpha:][:space:]0-9+]","",$titolo)))).".".$ext;
  } else $titolo=$filename; // ...otherwise keep the original filename
    return $titolo;
}

$file=$_SERVER["REQUEST_URI"];
$fullpath=$_SERVER["DOCUMENT_ROOT"].$file;
dl_file($fullpath)
?>
