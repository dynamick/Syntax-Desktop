<?php
  session_start();
  $uploadPHP = "../ihtml/upload_pictures.php?session_id=".session_id();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head profile="http://www.w3.org/2005/10/profile">
    <title>Syntax Desktop content frame</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="msthemecompatible" content="yes" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <link rel="stylesheet" type="text/css" href="../content.css" />
    <script type="text/javascript" src="../content.js?rand=934"></script>
  </head>
  <body>
    <div id="content">
      <div id="formHeader">
        <h4 style="padding-left: 30px;">Caricamento Fotografie</h4>
        <div style="padding: 0 30px;">Trascina dentro il campo di testo le fotografie che desideri vengano caricate sul server</div>
      </div>
      <form style='margin: 0px;' action="/admin/modules/aa/content.php" method="post" enctype="multipart/form-data" autocomplete="off" >
        <table style='margin: 20px 0 0 20px;width: 95%'>
          <tr style="background-color: #FAFAFA">
            <td style='width: 20%;font: menu; border-bottom: 1px solid #DDD; padding-left: 5px;vertical-align: top;'><div class="label" tooltip="Inserisci qui le fotografie da caricare">Media Upload</a><br /> <span class="help">Inserisci qui i tuoi file da caricare</span></td>
            <td style='font: menu;border-bottom: 1px solid #DDD;'>
              <div>
                <object width="640" height="500" name="JUpload" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#Version=5,0,0,3" classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93">
                  <param value="<?=$uploadPHP?>" name="postURL">
                  <param value="2147483648" name="maxFileSize">
                  <param value="../wjhk.jupload.jar" name="archive">
                  <param value="true" name="sendMD5Sum">
                  <param value="0" name="debugLevel">
                  <param value="JUpload" name="name">
                  <param value="wjhk.jupload2.JUploadApplet" name="code">
                  <param value="false" name="showLogWindow">
                  <param value="true" name="mayscript">
                  <param value="false" name="scriptable">
                  <param value="SUCCESS" name="stringUploadSuccess">
                  <param value="ERROR: (.*)" name="stringUploadError">
                  <param value="500000" name="maxChunkSize">
                  <param value="1024" name="maxPicHeight">
                  <param value="1024" name="maxPicWidth">
                  <param value="true" name="pictureTransmitMetadata">
                  <param value="fileChooserIconSize" name="80">
                  <param value="lookAndFeel" name="system">
                  <param value="showStatusBar" name="false">
                  <param value="jpg/jpeg/eps" name="allowedFileExtensions">
                  <comment>
                    <embed width="700" height="500" pluginspage="http://java.sun.com/products/plugin/index.html#download"
                      maxchunksize="500000"
                      stringuploaderror="ERROR: (.*)"
                      stringuploadsuccess="SUCCESS"
                      scriptable="false"
                      mayscript="true"
                      showlogwindow="false"
                      code="wjhk.jupload2.JUploadApplet"
                      name="JUpload"
                      sendmd5sum="true"
                      postURL="<?=$uploadPHP?>"
                      uploadPolicy="PictureUploadPolicy"
                      maxPicHeight="1024"
                      maxPicWidth="1024"
                      fileChooserIconSize="80"
                      lookAndFeel="system"
                      showStatusBar="false"
                      pictureTransmitMetadata="true"
                      archive="../wjhk.jupload.jar"
                      maxfilesize="2147483648"
                      allowedFileExtensions="jpg/jpeg/eps"
                      type="application/x-java-applet;version=1.5">
                      <noembed>Java 1.5 or higher plugin required.</noembed>
                  </comment>
                </object>
          		</div>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </body>
</html>
