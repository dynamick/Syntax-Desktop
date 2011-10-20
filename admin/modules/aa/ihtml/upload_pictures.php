<?php
session_id($_GET['session_id']);
include_once('../../../config/cfg.php');
global $synAbsolutePath, $synPublicPath, $mat;

// Change: Allow this example file to be easily relocatable - as of version 1.11
$Toolkit_Dir = "../../../includes/php/exif/";     // Ensure dir name includes trailing slash

// Hide any unknown EXIF tags
$GLOBALS['HIDE_UNKNOWN_TAGS'] = TRUE;

include $Toolkit_Dir . 'Toolkit_Version.php';          // Change: added as of version 1.11
include $Toolkit_Dir . 'JPEG.php';                     // Change: Allow this example file to be easily relocatable - as of version 1.11
include $Toolkit_Dir . 'JFIF.php';
include $Toolkit_Dir . 'PictureInfo.php';
include $Toolkit_Dir . 'XMP.php';
include $Toolkit_Dir . 'Photoshop_IRB.php';
include $Toolkit_Dir . 'EXIF.php';
include $Toolkit_Dir . 'Photoshop_File_Info.php';


function get_exif_tag($filename) {
  if ($filename == "") return;
  $jpeg_header_data = get_jpeg_header_data( $filename );
  $exif=get_EXIF_JPEG( $filename );
  $xmp = read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) );
  $irb = get_Photoshop_IRB( $jpeg_header_data );
  $exif = get_photoshop_file_info($exif, $xmp ,$irb);
  return $exif;
}

function prepareDestinationFolder($basepath) {
  $year = date("Y");
  $month = date("m");
  if (!file_exists($basepath."/".$year)) {
    mkdir($basepath."/".$year);
  }
  if (!file_exists($basepath."/".$year."/".$month)) {
    mkdir($basepath."/".$year."/".$month);
  }
  return "/".$year."/".$month."/";
}

function index_picture ($file, $title, $caption, $author, $tags) {
  global $db;

  $media_id="";
  $filename=basename($file);
  $path=dirname($file);

  # Se è una figurina, allora il filename viene interpretato perchè contiene
  # il nome e il cognome del giocatore
  if (in_array("figurina",$tags) && $caption=="") {
    $path_parts = pathinfo($file);
    $filename_tmp=$path_parts["filename"];
    $token=strpos($filename_tmp,"_");
    $cognome=str_replace("-"," ",substr($filename_tmp, 0, $token));
    $nome=str_replace("-"," ",substr($filename_tmp, $token+1));
    $caption = $filename_tmp;
    $tags[] = $cognome;
    $tags[] = $nome;
  }

  #id  	 filename  	 path  	 title  	 caption  	 author  	 modified_at
  $qry = "INSERT INTO media (`filename`, `path`, `title`, `caption`, `author`) VALUES ('".addslashes($filename)."','".addslashes($path)."','".addslashes($title)."','".addslashes($caption)."','".addslashes($author)."')";
  $res=$db->Execute($qry);
  $media_id = $db->Insert_ID();

  if (is_array($tags) and $media_id!="") {
    foreach ($tags as $t) {
      $tag_id="";
      $qry="SELECT * FROM tags WHERE tag = '".addslashes($t)."'";
      $res=$db->Execute($qry);
      if ($arr=$res->FetchRow()) {
        $tag_id=$arr["id"];
      } else {
        $qry="INSERT INTO tags (tag) VALUES ('".addslashes($t)."')";
        $res=$db->Execute($qry);
        $tag_id = $db->Insert_ID();
      }
      if ($tag_id!="") {
        $qry="INSERT INTO tagged (media_id, tag_id) VALUES ($media_id, $tag_id)";
        $res = $db->Execute($qry);
      }
    } # end of foreach
  }
} #end of function


$bSuccess = true;
$save_path = $synPublicPath.$mat.'/';

$key = intval($_GET["key"]);
//$_SESSION['juvar.files'] = array(); // per after_upload.php

foreach ($_FILES as $file => $fileArray) {
	//echo("File key: $file\n");

  $path_parts = pathinfo($fileArray['name']);
  $filename   = $path_parts['filename'];
  $ext        = $path_parts['extension'];
  $data       = date("Y-m-d H:i:s");
  $token      = strpos($filename,"_");
  $cognome    = substr($filename, 0, $token);
  $nome       = substr($filename, $token+1);

  # Prevent Filename Duplication
  $duplication = 0;
  $fix_duplication = "";
  $date_folder = prepareDestinationFolder($synAbsolutePath.$save_path);

  do {
    if ($duplication>0) $fix_duplication = "-".$duplication;
    $newFilename      = strtolower($filename).$fix_duplication;
    $newPath          = $synAbsolutePath.$save_path.$date_folder;
    $newFilenamePath  = $newPath.$newFilename.".".strtolower($ext);
    $duplication     += 1;
  } while (file_exists($newFilenamePath));

  if(move_uploaded_file($fileArray['tmp_name'], $newFilenamePath)){
    chmod($newFilenamePath, 0777);

    if (strtolower($ext)=='eps') {
      system('convert "'.$newFilenamePath.'" "'.$newPath.$newFilename.'.jpg"');
      $newFilenamePath = $newPath.$newFilename.".jpg";
    }

    # Extract EXIF Informations
    $exif = get_exif_tag($newFilenamePath);

    # Save Picture to Database
    $relative_path_filename = $save_path.$date_folder.$newFilename.".jpg";
    index_picture($relative_path_filename, $exif["title"], $exif["caption"], $exif["author"], $exif["keywords"]);

    $bSuccess += true;

  } else {
    $bSuccess += false;
  }
}

//Let's say to the applet that it's a success or a failure:
echo("\n");
if ($bSuccess) {
	echo "SUCCESS\n";
} else {
	echo "ERROR: $error\n";
}
echo "End of upload.php script\n";
?>
