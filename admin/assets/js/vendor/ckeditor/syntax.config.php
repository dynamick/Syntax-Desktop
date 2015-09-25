<?php
  header("Content-Type: text/javascript");
  session_start();
  $lng = (isset($_SESSION['synSiteLangInitial'])) ? $_SESSION['synSiteLangInitial'] : 'it'; ?>

CKEDITOR.editorConfig = function(config)
{
  config.language = '<?php echo $lng; ?>';
  config.uiColor = '#f6f6f6';
  config.width = '100%';
  config.resize_enabled = false;
  config.toolbarCanCollapse = false;
  config.entities = false;
  config.entities_latin = false;
  
  
  config.extraPlugins = 'youtube,justify,colorbutton,magicline,showblocks';
  config.allowedContent = true;
  //http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar

  config.toolbar = 'Basic';
  config.toolbar_Basic = [
    ['Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink','-','SelectAll','RemoveFormat','ShowBlocks','Source','-','About']
  ] ;


  config.toolbar = 'Default';
  config.toolbar_Default = [
    ['Save','Preview'],
    ['Cut','Copy','Paste','PasteText'],
    ['Undo','Redo','-','SelectAll','RemoveFormat'],
    ['Image','Flash','Table','HorizontalRule','SpecialChar','Youtube'],
    ['Maximize','ShowBlocks','Source'],
    '/',
    ['Format','-','TextColor', 'BGColor'],
    ['Bold','Italic','Strike','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    ['Link','Unlink','Anchor'],
    ['About']
  ] ;


  config.toolbar = 'Deluxe';
  config.toolbar_Deluxe = [
    ['Source','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['BidiLtr', 'BidiRtl'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
    '/',
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
  ] ;

  // kcfinder integration
  var kcPath = '/admin/includes/js/kcfinder/';
  config.filebrowserBrowseUrl = kcPath+'browse.php?type=file';
  config.filebrowserUploadUrl = kcPath+'upload.php?type=file';
  config.filebrowserImageBrowseUrl = kcPath+'browse.php?type=image';
  config.filebrowserImageUploadUrl = kcPath+'upload.php?type=image';
  config.filebrowserFlashBrowseUrl = kcPath+'browse.php?type=flash';
  config.filebrowserFlashUploadUrl = kcPath+'upload.php?type=flash';
  
  config.youtube_width = '640';
  config.youtube_height = '480';
  config.youtube_related = true;
  config.youtube_older = false;
  config.youtube_privacy = false;
};
