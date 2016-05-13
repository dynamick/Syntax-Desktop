<?php
/*
  V5.0 - VERSIONE PER LA PARTE PUBBLICA
  stampa i bottoni di navigazione. (imgPath,pageIndex,currentPage)
  echo $pager->renderPager("admin/modules/aa/",true,false);
*/

class synPager extends ADODB_Pager{
  var $id;   // unique id for pager (defaults to 'adodb')
  var $db;   // ADODB connection object
  var $sql;   // sql used
  var $rs;  // recordset generated
  var $curr_page;  // current page number before Render() called, calculated in constructor
  var $rows;    // number of rows per page
    var $linksPerPage=10; // number of links per page in navigation bar
    var $showPageLinks;

  var $gridAttributes = 'width=100% border=1 bgcolor=white';

  // Localize text strings here
  var $first = '<code>|&lt;</code>';
  var $prev = '<code>&lt;&lt;</code>';
  var $next = '<code>>></code>';
  var $last = '<code>>|</code>';

  var $moreLinks = '...';
  var $startLinks = '...';

  var $gridHeader = false;
  var $htmlSpecialChars = true;
  var $page = 'Pag';
  var $linkSelectedColor = 'black';
  var $cache = 0;  #secs to cache with CachePageExecute()

  var $targetFile;
  var $targetFrame;

  var $firstPage;
  var $prevPage;
  var $nextPage;
  var $lastPage;
  var $index;
  var $footer;

  //----------------------------------------------
  // constructor
  //
  // $db  adodb connection object
  // $sql  sql statement
  // $id  optional id to identify which pager,
  //    if you have multiple on 1 page.
  //    $id should be only be [a-z0-9]*
  // $targetFile the file to be pointed in the page index
  // $targetFrame the frame to be pointed in the page index
  function __construct(&$db, $id='adodb', $targetFile, $targetFrame, $showPageLinks=false, $use_session=false){
    global $_SERVER, $PHP_SELF, $_SESSION, $_GET;

    $curr_page = $id.'_curr_page';
    if (empty($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];

    //$this->sql = $sql;
    $this->id = $id;
    $this->db = $db;
    $this->showPageLinks = $showPageLinks;

    $this->targetFile=$targetFile;
    $this->targetFrame=$targetFrame;

    $next_page = $id.'_next_page';

    if($use_session){
      # if current page is to be kept in session
      if (isset($_GET[$next_page])) {
        $_SESSION[$curr_page] = $_GET[$next_page];
      }
      if(empty($_SESSION[$curr_page]))
        $_SESSION[$curr_page]=1;
      $this->curr_page = $_SESSION[$curr_page];

    } else {
      # current page comes from $_GET
      $this->curr_page = ($_GET[$next_page]) ? $_GET[$next_page] : 1;
    }
  }


  //---------------------------
  // Display link to first page
  function Render_First($anchor=true, $parameters="")  {
    if ($parameters!="") $parameters="?".$parameters;
    if ($anchor) {
      //if ($parameters!="") $parameters="&".$parameters;
      //$ret=$this->targetFile."?".$this->id."_next_page=1".$parameters;
      $ret="index.html".$parameters;
    } else {
      //$ret=$this->targetFile.$parameters;
      $ret=$parameters;
    }
    return $ret;
  }

  //--------------------------
  // Display link to next page
  function render_next($anchor=true, $parameters="") {
    if ($parameters!="") $parameters="?".$parameters;
    if ($anchor) {
      $ret="index".($this->rs->AbsolutePage() + 1).".html".$parameters;
    } else {
      $ret=$parameters;
    }
    return $ret;
  }

  // Link to previous page
  function render_prev($anchor=true, $parameters="") {
    if ($parameters!="") $parameters="?".$parameters;
    if ($anchor) {
      $ret="index".($this->rs->AbsolutePage() - 1).".html".$parameters;
    } else {
      $ret=$parameters;
    }
    return $ret;
  }

  //------------------
  // Link to last page
  //
  // for better performance with large recordsets, you can set
  // $this->db->pageExecuteCountRows = false, which disables
  // last page counting.
  function render_last($anchor=true, $parameters="") {
    if (!$this->db->pageExecuteCountRows) return;
    if ($parameters!="") $parameters="?".$parameters;
    if ($anchor) {
      $ret="index".$this->rs->LastPageNo().".html".$parameters;
    } else {
      $ret=$parameters;
    }
    return $ret;
  }

  //---------------------------------------------------
  // original code by "Pablo Costa" <pablo@cbsp.com.br>
function myRender_PageLinks($parameters="") {
  global $PHP_SELF;

  $pages        = $this->rs->LastPageNo();
  $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
  if($parameters) $parameters = "?".$parameters;

  for($i=1; $i <= $pages; $i+=$linksperpage)
    if($this->rs->AbsolutePage() >= $i) $start = $i;

  $numbers = array(); //= '';
  $end = $start+$linksperpage-1;
  $link = $this->id . "_next_page";
  if($end > $pages) $end = $pages;

  if ($this->startLinks && $start > 1) {
    $pos = $start - 1;
    $numbers[] = "<a href=\"index{$pos}.html{$parameters}\">{$this->startLinks}</a>";
  }

  for($i=$start; $i<=$end; $i++) {
    if ($this->rs->AbsolutePage() == $i) {
      $numbers[] = "<strong>$i</strong>";
    } else {
      $numbers[] = "<a href=\"index{$i}.html{$parameters}\">{$i}</a>";
      //$num = ($i==1 ? "" : $i);
      //$numbers[] = "<a href=\"index{$num}.html{$parameters}\">{$i}</a>";
    }
  }

  if ($this->moreLinks && $end < $pages)
    $numbers[] = "<a href=\"index{$i}.html{$parameters}\">{$this->moreLinks}</a>  ";

  return $numbers;
}


  //--------------------------------------------------------
  // Simply rendering of grid. You should override this for
  // better control over the format of the grid
  //
  // We use output buffering to keep code clean and readable.
  function RenderGrid() {
  global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
    include_once(ADODB_DIR.'/tohtml.inc.php');
    ob_start();
    $gSQLBlockRows = $this->rows;
    rs2html($this->rs,$this->gridAttributes,$this->gridHeader,$this->htmlSpecialChars);
    $s = ob_get_contents();
    ob_end_clean();
    return $s;
  }

  //-------------------------------------------------------
  // Navigation bar
  //
  // we use output buffering to keep the code easy to read.
  function RenderNav($parameters)  {
    $ret=array();
    if (!$this->rs->AtFirstPage()) {
      $ret[0]=$this->Render_First(true,$parameters);
      $ret[1]=$this->Render_Prev(true,$parameters);
    } else {
      $ret[0]=$this->Render_First(false,$parameters);
      $ret[1]=$this->Render_Prev(false,$parameters);
    }
    if ($this->showPageLinks){
      $ret[4]=$this->myRender_PageLinks($parameters);
    }
    if (!$this->rs->AtLastPage()) {
      $ret[2]=$this->Render_Next(true,$parameters);
      $ret[3]=$this->Render_Last(true,$parameters);
    } else {
      $ret[2]=$this->Render_Next(false,$parameters);
      $ret[3]=$this->Render_Last(false,$parameters);
    }

    return $ret;
  }

  //-------------------
  // This is the footer
  function RenderPageCount() {
    if (!$this->db->pageExecuteCountRows) return '';
    $lastPage = $this->rs->LastPageNo();
    if ($lastPage == -1) $lastPage = 1; // check for empty rs.
    return "$this->page ".$this->curr_page."/".$lastPage."";
  }

  //-----------------------------------
  // Call this class to draw everything.
  function Execute($sql,$rows=10,$parameters="") {
  global $ADODB_COUNTRECS;

    $this->sql = $sql;
    $this->rows = $rows;

    $savec = $ADODB_COUNTRECS;
    if ($this->db->pageExecuteCountRows) $ADODB_COUNTRECS = true;
    if ($this->cache)
      $rs = &$this->db->CachePageExecute($this->cache,$this->sql,$rows,$this->curr_page);
    else
      $rs = &$this->db->PageExecute($this->sql,$rows,$this->curr_page);
    $ADODB_COUNTRECS = $savec;

    $this->rs = &$rs;
    if (!$rs) {
      print "<h3>Query failed: $this->sql</h3>";
      return;
    }

    //if (!$rs->EOF && (!$rs->AtFirstPage() || !$rs->AtLastPage()))
      $header = $this->RenderNav($parameters);
    //else
    //  $header = "";

    //$grid = $this->RenderGrid();
    $footer = $this->RenderPageCount();
    //$rs->Close();
    //$this->rs = false;
    $this->RenderLayout($header,$grid,$footer);
    return $this->rs;
  }

  //------------------------------------------------------
  // override this to control overall layout and formating
  function RenderLayout($header,$grid,$footer,$attributes='border=1 bgcolor=beige')
  {
    $this->firstPage=$header[0];
    $this->prevPage=$header[1];
    $this->nextPage=$header[2];
    $this->lastPage=$header[3];
    $this->index=$header[4];
    $this->footer=$footer;
  }


  function renderPager() {
    $lastPage = $this->rs->LastPageNo();
    if ($lastPage > 1) {
      $txt .= "<p class=\"paginazione\">pagina ";
       if (!$this->rs->AtFirstPage()) {
        $txt .= "<a href=\"$this->prevPage\" class=\"prev\"><img src=\"/public/css/widgets/bullet_white_l.png\" alt=\"pagina precedente\" /></a>\n";
      }
      $txt .= $this->index;
      if (!$this->rs->AtLastPage()) {
        $txt .= "<a class=\"next\" href=\"$this->lastPage\"><img src=\"/public/css/widgets/bullet_white_r.png\" alt=\"pagina successiva\"/></a>\n";
      }
      $txt .= "</p>\n";
    }
    return $txt;
  }

  function renderPagerList() {
    $pagine = $this->index;
    $txt .= "<ul id=\"controls\">\n";
     if ($this->rs->AtFirstPage()) {
      $txt .= "<li><a class=\"prev\" href=\"javascript:void(0)\" rel=\"nofollow\">‹</a></li>\n";
    } else {
      $txt .= "<li><a class=\"prev\" href=\"$this->prevPage\" rel=\"nofollow\">‹</a></li>\n";
    }

    foreach($pagine as $v){
      $txt .= "<li class=\"goto\">{$v}</li>\n";
    }

     if ($this->rs->AtLastPage()) {
      $txt .= "<li><a class=\"next\" href=\"javascript:void(0)\" rel=\"nofollow\">›</a></li>\n";
    } else {
      $txt .= "<li><a class=\"next\" href=\"$this->nextPage\" rel=\"nofollow\">›</a></li>\n";
    }
    $txt .= "</ul>\n";

    return $txt;
  }

  // simple pager
  function renderPgr() {
    $pagine = $this->index;

    foreach($pagine as $v){
      $txt .= "$v ";
      //$txt .= str_replace('index', $layout, $v)." ";
    }
    return "Pagina: ".$txt;
  }
}
?>
