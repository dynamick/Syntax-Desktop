function deleteSelectedRows() {
  var
    $ids = new Array(),
    $rows = $table.bootstrapTable('getSelections');

  for (i in $rows)
    $ids.push( "`id`='" + $rows[i].id + "'" );

  $.ajax({
    method    : 'POST',
    url       : 'getData.php?cmd=' + syntax.cmd.multidel,
    data      : { 'checkrow[]' : $ids },
    dataType  : 'json'

  }).done(function( responseText ) {
    if ( typeof responseText.unauthorized != 'undefined' )
      sendNotify({ type:2, message: responseText.unauthorized });
    $table.bootstrapTable('refresh');

  }).fail(function( jqXHR, textStatus, errorThrown ) {
    var res = jqXHR.responseJSON;
    if ( typeof res.error != 'undefined' )
      sendNotify({ type:1, message: res.error });

  }).always(function( responseText ) {
    var res = responseText;
    if ( typeof res.responseJSON != 'undefined' ) // if the request fails
      res = responseText.responseJSON;
    if ( typeof res.status != 'undefined' )
      sendNotify({ type:8, message: res.status });
  });
}

//multidelete confirm
function confirmDeletion(){
  bootbox.confirm( syntax.str.confirm, function(result) {
    if (result == true) {
      deleteSelectedRows();
    } else {
      return true;
    }
  });
}

var $table = $('#mainTable');

$(function() {
  $table.bootstrapTable({
    icons: {
      refresh: 'fa fa-refresh',
      toggle: 'fa fa-th-list',
      columns: 'fa fa-columns'
    },
    url: 'getData.php?cmd=getjson',
    clickToSelect: false,
    showFilter: true,
    showRefresh: true,
    showToggle: true,
    showColumns: true,
    search: true,
    sidePagination: 'server',
    pagination: true,
    pageList: [10, 20, 50, 100],
    iconsPrefix: 'fa',
    cookie: true,
    cookieIdTable: 'service-' + syntax.service.id,
    cookieExpire: '6h',
    cookiesEnabled: ['bs.table.sortorder', 'bs.table.sortname', 'bs.table.pagenumber', 'bs.table.pagelist', 'bs.table.columns', 'bs.table.searchtext', 'bs.table.filtercontrol'], // must set it in lowercase otherwise the plugin messes it up
    showExport: true,
    exportTypes: ['sql','xml','csv','excel'],
    exportOptions: {
      tableName: syntax.service.table,
      fileName: syntax.appname + '.' + syntax.service.table
    },
    onLoadSuccess: function (data) {
      if ( typeof data.error != 'undefined' )
        sendNotify( data.error );
      var
        $cb = $table.find('td.bs-checkbox'),
        $bt = $table.find('td').has('.btn');
      // ignore the checkbox and button cells from table export
      $cb.add($bt).data('tableexportDisplay', 'none');
    },
    onLoadError: function (status) {
      if ( typeof status != 'undefined' )
        sendNotify({ type:1, message:'Event: onLoadError, data: ' + status });
    }
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
    bootbox.confirm( syntax.str.confirm, function(result) {
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
          if ( typeof responseText != 'undefined' )
            sendNotify( responseText );
        });
      }
    });
  });

  // RPC functions
  $('#content').on( 'change', 'input.rpc', function() {
    var
      $this = $(this),
      params = {
        aa_service: syntax.service.id,
        cmd: syntax.cmd.rpc,
        field: $this.attr('name'),
        value: $this.is(':checked') ? '1' : '',
        synPrimaryKey: $this.data('key')
      };
    $.getJSON(
      'getData.php',
      params
    ).fail( function( jqXHR, textStatus, errorThrown ) {
      var res = jqXHR.responseJSON;
      if ( typeof res.error != 'undefined' )
        sendNotify({ type:1, message: res.error });
    });
  });
});
