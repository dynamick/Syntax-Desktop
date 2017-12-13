<?php
class synDocumentSelector extends synElement {

  var $mat;

  //constructor(name, value, label, size, help)
  function __construct( $n = '', $v = null , $l = null, $s = 255, $h = '' ) {
    if ( empty($n) )
      $n = 'text' . date('his');
    if ( empty($l) )
      $l = ucfirst($n);

    $this->type  = 'text';
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      $this->value = $$n;
    } else {
      $this->value = $v;
    }
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = ' VARCHAR(' . $this->size . ') NOT NULL';

    $this->configuration();
  }

  //private function
  function _html() {
    global $db, ${$this->name};

    $fieldname  = $this->name;
    $value      = $this->getValue();
    $session_id = urlencode( session_id() );

    $html = <<<EOHTML
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Cerca un documento</h3>
          </div>
          <div class="panel-heading">
            <div class="input-group input-group-sm">
              <input type="text" id="tagsearch_{$fieldname}"
                class="form-control" placeholder="Inserisci una chiave di ricerca...">
              <span class="input-group-btn">
                <button id="ajaxSearch_{$fieldname}" class="btn btn-default" type="button">Cerca</button>
              </span>
            </div>
          </div>
          <div id="choose_{$fieldname}" class="list-group multi-file"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div id="sc_cart" class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Documenti selezionati</h3>
          </div>
          <div class="panel-footer">
            <input type="text" name="{$fieldname}" id="{$fieldname}" value="{$value}"
              class="form-control input-sm" placeholder="Nessun documento selezionato" readonly>
          </div>
          <div id="select_{$fieldname}" class="list-group multi-file list-group-draggable"></div>
        </div>
      </div>
    </div>
EOHTML;

    $script = <<<EOC
    var _field = null,
        _button = null,
        _search = null,
        _select_from = null,
        _select_to = null,
        remove  = '<i class="fa fa-times"></i>',
        session = '{$session_id}';
    $(function() {
      _field  = $('#{$fieldname}');
      _button = $('#ajaxSearch_{$fieldname}');
      _search = $('#tagsearch_{$fieldname}');
      _select_from = $('#choose_{$fieldname}');
      _select_to = $('#select_{$fieldname}');
      // supporto drag'n drop
      $.getScript( '/admin/assets/js/vendor/Sortable.min.js', function( data, textStatus, jqxhr ) {
        makeSortable();
        action();
      });
      // proibisce invio del form premendo Enter
      _search.on('keypress', function (e) {
        if (e.which === 13) {
          _button.trigger('click');
          e.preventDefault();
        }
      });
      
      function action(){
        _button.click( function(e) {
          var tags = _search.val(), ar_selected = _field.val().split('|');
          $.ajax({
            url: 'ihtml/file_dispatch_multiple.php',
            data: { session_id: session, tags: tags },
            success: function(data) {
              _select_from.html( data );
            }, beforeSend: function() {
              setButton('wait');
            }, complete: function() {
              setButton();
              _select_from.find('.scBtn').each( function() {
                var _this = $(this), _item = _this.parent(), itemid = _this.data('item');
                if (ar_selected.indexOf( String(itemid) ) > -1) {
                  _this.attr('disabled', true);
                  _item.addClass('disabled');
                }
              }).click( function() {
                var _this = $(this), _item = _this.parent(), _new = _item.clone();
                $(_new)
                  .appendTo( _select_to )
                  .find('.scBtn')
                  .html(remove)
                  .addClass('btn-warning')
                  .click( function() {
                    _this.attr('disabled', false);
                    _item.removeClass('disabled');
                    _new.remove();
                    _field.val( updateValue() );
                  });
                _this.attr('disabled', true);
                _item.addClass('disabled');
                _field.val( updateValue() );
              })
            }
          });
          e.preventDefault();
        });

        // INIT: populate the selected list
        if (_field.val() != '') {
          $.ajax({
            url: 'ihtml/file_dispatch_multiple.php',
            data: { session_id: session, selected: _field.val() },
            success: function(data) {
              _select_to.html(data);
              makeSortable();
            }, beforeSend: function() {
              setButton('wait');
            }, complete: function() {
              setButton();
              _select_to
                .find('.scBtn')
                .html(remove)
                .addClass('btn-warning')
                .click(function() {
                  var _this = $(this), _item = _this.data('item');
                  _this.parent().remove();
                  _select_from
                    .find('.scBtn[data-item="' + _item + '"]')
                    .attr('disabled', false)
                    .parent().removeClass('disabled');
                  _field.val( updateValue() );
                });
            } // complete
          }); // fine ajax
        }
      }
      
    });
    function setButton(status){
      if (status == 'wait') {
        _button.attr('disabled', true).html('caricamento...');
      } else {
        _button.attr('disabled', false).html('Cerca');
      }
    }
    function updateValue() {
      var value = '';
      _select_to.find('.pic_id').each( function() {
        var pic = $(this);
        if (value==''){
          value = pic.val();
        } else {
          value = value + '|' + pic.val();
        }
      })
      return value;
    }
    function makeSortable() {
      Sortable.create( select_{$fieldname}, {
        group: 'sorting',
        sort: true,
        onUpdate: function() {
          _field.val( updateValue() );
        }
      });
    }
EOC;
    enqueue_js($script);

    return $html;
  }

  //get the label of the element
  function getCell() {
    global $synAbsolutePath, $db;
    $value = $this->value;

    if ( !empty($value) ) {
      $ret = str_replace('|', ', ', $value);
    } else {
      $ret = '<span class="text-muted">Empty</span>';
    }
    return $ret;
  }


  //function for the auto-configuration
  function configuration( $i = '', $k = 99 ) {
    global
      $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp,
      $synElmSize, $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;

    $synHtml = new synHtml();

    if (!isset( $synElmSize[$i] ) or empty( $synElmSize[$i] ) )
      $synElmSize[$i] = $this->size;
    $this->configuration[4] = "Dimensione: " . $synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\"");

    //enable or disable the 3 check at the last configuration step

    $_SESSION["synChkKey"][$i] = 1;
    $_SESSION["synChkVisible"][$i] = 1;
    $_SESSION["synChkEditable"][$i] = 1;
    $_SESSION["synChkMultilang"][$i] = 1;

    if ( $k == 99 )
      return $this->configuration;
    else
      return $this->configuration[$k];
  }


} //end of class inputfile

?>
