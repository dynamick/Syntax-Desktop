<?php
global $cmd;

switch($cmd) {
  case ADD:
    break;
  case MODIFY:
    break;
  case CHANGE:
    break;
  case INSERT:
    break;
  case DELETE:
    break;
  case MULTIPLEDELETE:
    break;
  case RPC:
    break;
  case JSON:
    break;
  case '':
    // this creates a box in the list view's sidebar
    $script = <<<EOSCRIPT
    <script type="text/javascript">
      var txt = [
        "<div class='panel-heading'>",
        "<h3 class='panel-title'>SERVICE</h3>",
        "</div>",
        "<div class='panel-body'>",
        "<p>Hello world</p>",
        "</div>"
      ];
      window.parent.content.addBox( 'custom', txt.join('') );
    </script>
EOSCRIPT;
    echo $script;  
    break;
 }

if ($cmd != RPC && $cmd != JSON) {
  echo "<script>";
  echo "window.parent.content.initToolbar (true,false,true,true,true,true,false);";
  echo "</script>\n";
}

// EOF