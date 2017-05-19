CKEDITOR.editorConfig = function( config ) {
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config
    // http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
    config.toolbar = 'Basic';
    config.toolbar_Basic = [
        ['Bold','Italic','-','NumberedList','BulletedList','-','Link','Unlink','-','SelectAll','RemoveFormat','ShowBlocks','Source','-','About']
    ] ;

    config.toolbar = 'Default';

    config.toolbar_Default = [
        ['Save','Preview'],
        ['Cut','Copy','Paste','PasteText'],
        ['Undo','Redo','-','SelectAll','RemoveFormat'],
        ['Image','Table','HorizontalRule','SpecialChar','Youtube'],
        ['Maximize','ShowBlocks','Source'],
        '/',
        ['Format','-','TextColor', 'BGColor'],
        ['Bold','Italic','Strike','-','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Youtube','ckawesome'],
        ['About']
    ];
    config.toolbar = 'Deluxe';
    config.toolbar_Deluxe = [
        { name: 'document',     items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'clipboard',    items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',      items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms',        items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles',  items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',    items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links',        items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',       items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
        '/',
        { name: 'styles',       items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',       items : [ 'TextColor','BGColor' ] },
        { name: 'tools',        items : [ 'Maximize', 'ShowBlocks','-','About' ] }
    ];

    config.width = '100%';
    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    //config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    //config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.extraPlugins = 'youtube,ckawesome,justify';
    config.fontawesomePath = '/admin/assets/css/font-awesome.min.css';

    config.youtube_width = '640';
    config.youtube_height = '480';
    config.youtube_responsive = false;
    config.youtube_related = false;
    config.youtube_older = false;
    config.youtube_autoplay = false;

    // kcfinder integration
    var kcPath = '/admin/assets/js/vendor/kcfinder/';
    config.filebrowserBrowseUrl      = kcPath + 'browse.php?type=file';
    config.filebrowserUploadUrl      = kcPath + 'upload.php?type=file';
    config.filebrowserImageBrowseUrl = kcPath + 'browse.php?type=image';
    config.filebrowserImageUploadUrl = kcPath + 'upload.php?type=image';
};