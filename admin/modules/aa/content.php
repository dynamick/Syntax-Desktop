<?php
  if(!isset($_SESSION))
    session_start();
  include_once ("../../config/cfg.php");
  include_once ("../../includes/php/jslib.inc");
  include_once ("classes/synContainer.php");
  include_once ("classes/synHtml.php");

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/

  if ( isset($_GET['synSetLang']) ) {
    $langid = intval($_GET['synSetLang']);
    if ( isset($_SESSION['aa_CurrentLang']) && $_SESSION['aa_CurrentLang'] != $langid ) {
      setLang($langid);
      setAlert( sprintf('Lingua attiva: <b>%s</b>', getLangInfo($langid, 'lang') ) ) ;
    }
  } elseif( !isset($_SESSION['aa_CurrentLang']) || empty($_SESSION['aa_CurrentLang']) ) {
    setLang();
  }

  define ('MODIFY',         'modifyrow');
  define ('CHANGE',         'changerow');
  define ('ADD',            'addrow');
  define ('INSERT',         'insertrow');
  define ('DELETE',         'delrow');
  define ('MULTIPLEDELETE', 'delmultrow');
  define ('RPC',            'rpcfunction');
  define ('JSON',           'getjson');

  //check the authorization
  auth();

  //load the lang settings
  lang(getSynUser(), $str);

  if (isset($_REQUEST["aa_service"]))
    $_SESSION["aa_service"] = $_REQUEST["aa_service"];

  $res = $db->Execute("SELECT path FROM aa_services WHERE id=".$_SESSION["aa_service"]);
  list($targetFileName)=$res->FetchRow();

  if ($targetFileName=="")
    $targetFileName = "ihtml/auto_service.php";

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/
  //if (isset($_GET["synSetLang"])) $_SESSION["aa_CurrentLang"]=$_GET["synSetLang"];
  //elseif ($_SESSION["aa_CurrentLang"]=="") $_SESSION["aa_CurrentLang"]=1;
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Content Frame</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,300,400,600&amp;subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-switch.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-table.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/fontawesome-iconpicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/fileinput.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/typeahead.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />
    <link rel="stylesheet" type="text/css" href="plupload.css" />

    <script type="text/javascript" src="../../assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?=$synAdminPath?>/includes/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?=$synAdminPath?>/includes/js/ckeditor/adapters/jquery.js"></script>

    <script type="text/javascript" src="content.js?rand=<?=rand(0,1000)?>"></script>
    <script type="text/javascript">
    //<![CDATA[
      //RPC FUNCTION CALLER
      var g_remoteServer = '<?=$targetFileName?>'; //'ihtml/auto_service.php'
      var g_intervalID;
      var preview = new Array();

      function callServer(synPrimaryKey, field, value) {
      	var head = document.getElementsByTagName('head').item(0);
      	var old  = document.getElementById('lastLoadedCmds');
      	if (old) head.removeChild(old);

      	script = document.createElement('script');
      	script.src = g_remoteServer+"?synPrimaryKey="+synPrimaryKey+"&field="+field+"&value="+value+"&cmd=rpcfunction&aa_service=<?=$_SESSION["aa_service"]?>&rand="+Math.random();
        script.type = 'text/javascript';
      	script.defer = true;
      	script.id = 'lastLoadedCmds';
      	void(head.appendChild(script));
        debug("<b>RPC:</b> "+g_remoteServer+"?synPrimaryKey="+synPrimaryKey+"&field="+field+"&value="+value+"&cmd=rpcfunction&aa_service=<?=$_SESSION["aa_service"]?>");
      }

      //BUTTON FUNCTION
      action('newBtn',    'window.parent.content.document.location.href="content.php?cmd=<?=ADD?>";');
      action('backBtn',   'window.parent.content.history.back();');
      action('refreshBtn','window.parent.content.location.reload();');
      action('saveBtn',   'window.parent.content.document.forms[0].submit()');
      action('removeBtn', 'window.parent.content.confirmDeletion()');
      action('homeBtn',   'window.parent.content.location.href="<?=$PHP_SELF?>"');

    //]]>
    </script>
  </head>
  <body>
    <div id="content" class="container-fluid">
    <?php
      if (is_file($targetFileName))
        include($targetFileName);
      else
        echo '<p>Function not yet implemented...</p>';
    ?>
    </div>
    <script src="http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>

    <script type="text/javascript" src="../../assets/js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-table.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-table-it-IT.min.js"></script>

    <script type="text/javascript" src="../../assets/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="../../assets/js/fontawesome-iconpicker.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-maxlength.min.js"></script>
    <script type="text/javascript" src="../../assets/js/fileinput.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="../../assets/js/bootstrap-notify.min.js"></script>
    <script type="text/javascript" src="../../assets/js/jquery.quickPreview.js"></script>
    <script type="text/javascript" src="../../assets/js/bloodhound.min.js"></script>
    <script type="text/javascript" src="../../assets/js/typeahead.jquery.min.js"></script>
    <script type="text/javascript" src="../../assets/js/typeahead-addresspicker.js"></script>

    <script type="text/javascript">
      var $table = $('#mainTable');

      $.notifyDefaults({
        offset: {
          x: 15,
          y: 20
        },
        animate: {
          exit: 'animated lightSpeedOut'
        }
      });

      function deleteSelectedRows() {
        $ids = new Array();
        $rows = $table.bootstrapTable('getSelections');
        for (i in $rows)
          $ids.push( "`id`='" + $rows[i].id + "'" );

        $.ajax({
          method    : 'POST',
          url       : 'getData.php?cmd=<?= MULTIPLEDELETE ?>',
          data      : { 'checkrow[]' : $ids },
          dataType  : 'json'

        }).done(function( responseText ) {
          if ( typeof responseText.unauthorized != 'undefined' )
            $.notify({ icon: 'fa fa-exclamation-triangle', message: responseText.unauthorized },{ type: 'warning' });
          $table.bootstrapTable('refresh');

        }).fail(function( jqXHR, textStatus, errorThrown ) {
          var res = jqXHR.responseJSON;
          if ( typeof res.error != 'undefined' )
            $.notify({ icon: 'fa fa-exclamation-triangle', message: res.error },{ type: 'danger' });
          console.error( errorThrown );

        }).always(function( responseText ) {
          var res = responseText;
          if ( typeof res.responseJSON != 'undefined' ) // if the request fails
            res = responseText.responseJSON;
          if ( typeof res.status != 'undefined' )
            $.notify({ icon: 'fa fa-info-circle', message: res.status });
        });
      }

      //multidelete confirm
      function confirmDeletion(){
        bootbox.confirm( top.str['aa_confirmSelDel'], function(result) {
          if (result == true) {
            deleteSelectedRows();
          } else {
            return true;
          }
        });
      }

      function getLanLng(){
        var lat = 0;
        var lng = 0;
        if( $( '#address-data' ).length > 0 ) {
          var address = $('#address-data').val();
          var arr = address.split("|");
          lat = arr[1];
          lng = arr[2];
        }
        return new google.maps.LatLng(lat, lng);
      }


      $(function() {
        // init checkbox switches
        $('.syn-check').bootstrapSwitch();

        // init date/time picker
        $('.date').datetimepicker({
          locale: '<?= getLang(true); ?>',
          icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-crosshairs',
            clear: 'fa fa-trash'
          }
        });

        // init multi-select
        $('.multi-select').multiselect({
          enableClickableOptGroups: true,
          disableIfEmpty: true,
          selectedClass: 'multiselect-selected',
          includeSelectAllOption: true
        });

        $table.bootstrapTable({
          icons: {
            refresh: 'fa fa-refresh',
            toggle: 'fa fa-th-list',
            columns: 'fa fa-columns'
          }
        });

        // init icon-picker
        $('.icp').iconpicker();

        // init address picker
        if ($('#address-picker').length > 0){
          var addressPicker = new AddressPicker({
             map: {
              id: '#map',
              center: getLanLng(),
              zoom: 16
             }
             , marker: {
              draggable: true,
              visible: true
             }
             , autocompleteService: {
              types: ['geocode', 'establishment']
             }
          });
          $('#address-picker').typeahead(null, {
            displayKey: 'description',
            source: addressPicker.ttAdapter()
          });
          addressPicker.bindDefaultTypeaheadEvent($('#address-picker'))
          $(addressPicker).on('addresspicker:selected', function (event, result) {
            $('#address-picker').val(result.placeResult.formatted_address);
            $('#address-data').val($("#address-picker").val()+"|"+result.lat()+"|"+result.lng());
            $('#lat').html(result.lat());
            $('#lng').html(result.lng());
          });
        }

        // init file input
        $('.file-input-control').each( function(){
          var
            $this = $(this),
            name = $this.data('name'),
            //old_value = $('input[name="' + name + '_old"]').val(),
            initial = preview[ name ];
          $this.fileinput({
            showUpload: false,
            previewFileType: 'any',
            initialPreview: [ initial['src'] ],
            initialCaption: initial['label'],
            browseIcon: '<i class="fa fa-folder-open-o"></i> ',
            removeIcon: '<i class="fa fa-trash"></i> ',
            layoutTemplates: {
              icon: '<span class="fa fa-file kv-caption-icon"></span> '
            }
          }).on( 'fileloaded', function() {
            $this.attr('name', name);
          }).on('filecleared', function() {
            $this.attr('name', name);
          });
        });

        // init image preview
        $('.preview').quickPreview();

        // init tooltip
        $('[data-toggle="tooltip"]').tooltip({
          container: 'body'
        })

        // ajax delete
        $('body').on('click', '.ajax-delete', function(e){
          var $this = $(this);
          e.preventDefault();
          bootbox.confirm( '<?= $str["sure_delete"] ?>', function(result) {
            if (result == true) {
              $.ajax({
                method    : 'POST',
                url       : $this.attr('href'),
                dataType  : 'json'

              }).done(function( responseText ) {
                $table.bootstrapTable('refresh');

              }).fail(function( jqXHR, textStatus, errorThrown ) {
                console.error( errorThrown );

              }).always(function( responseText ) {
                if ( typeof responseText.status != 'undefined' )
                  $.notify({ icon: 'fa fa-info-circle', message: responseText.status });
              });
            }
          });
        });

        // syncronous delete
        $('.btn-delete').click(function(e){
          e.preventDefault();
          bootbox.confirm( '<?= $str["sure_delete"] ?>', function(result) {
            if (result == true) {
              $(e.currentTarget).unbind( 'click' ).trigger( 'click' );
            } else {
              return true;
            }
          });
        });

        // limited textarea
        $('.input-limited').maxlength({
          alwaysShow: true,
          twoCharLinebreak: true,
          //allowOverMax: true,
          validate: false
        });

        <?php echo getAlert(); ?>

        /*
        bootbox.alert("Hello world!", function() {
          console.log('alert closed');
        });
        */
        /*
        // rpc
        $('input.rpc').change(function(){
          var $this = $(this);
          var params = {
              aa_service: '<?= $synContainer ?>',
              cmd:'<?= RPC ?>',
              field: $this.attr('name'),
              value: $this.is(':checked') ? '1' : '',
              synPrimaryKey: $this.attr('rel')
          }
          $.getJSON('<?= $synAdminPath ?>/modules/aa/_content.php', params, function(ret){
            $('.page-header').after('<div class="alert '+ ret.type +'"><button data-dismiss="alert" class="close">×</button>'+ ret.message +'</div>');
          });
        });
        */
      });
    </script>
  </body>
</html>
