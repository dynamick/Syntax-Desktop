<?php
/*
  V5.0 - VERSIONE PER LA PARTE PUBBLICA
  stampa i bottoni di navigazione. (imgPath,pageIndex,currentPage)
  echo $pager->renderPager("admin/modules/aa/",true,false);
*/

class synPagerPublic {

  public $rs;
  public $index;
  public $curr_page;
  public $current_template;
  public $link_template;

  private $sql;
  private $count;
  private $db;
  private $id;
  private $showPageLinks;
  private $linksPerPage;
  private $next_page;
  private $targetFile;
  private $targetFrame;
  private $startLinks;
  private $moreLinks;
  private $page;

  public function __construct($db, $id='adodb', $targetFile, $targetFrame, $showPageLinks=false, $use_session=false){
    $this->db = $db;
    $this->id = $id;
    $this->linksPerPage = 10;
    $this->showPageLinks = true;
    $this->next_page = $this->id.'_next_page';
    $this->curr_page = $this->id.'_curr_page';
    $this->targetFile = $targetFile;
    $this->targetFrame = $targetFrame;

    $this->current_template = '<strong>%s</strong>';
    $this->link_template = '<a href="%s">%s</a>';


    if($use_session==true){
      # if current page is to be kept in session
      if (isset($_GET[$this->next_page])) {
        $_SESSION[$this->curr_page] = intval($_GET[$this->next_page]);
      }
      if(empty($_SESSION[$this->curr_page]))
        $_SESSION[$this->curr_page]=1;
      $this->curr_page = $_SESSION[$this->curr_page];

    } else {
      # current page comes from $_GET
      if(isset($_GET[$this->next_page]) && $_GET[$this->next_page]!=false)
        $this->curr_page = intval($_GET[$this->next_page]);
      else
        $this->curr_page = 1;
    }
  }


  public function Execute($sql, $rows=10,$parameters=null){
    $this->sql = $sql;
    $this->rows = $rows;

    $this->rs = $this->db->PageExecute($this->sql, $rows, $this->curr_page);
    if (!$this->rs) {
      throw new Exception('Errore: esecuzione query in SynPager');
    }

    //$this->index = $this->renderPageLinks($parameters);
    $this->RenderLayout($parameters);
    return $this->rs;
  }

  function renderPageLinks($parameters=null) {
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
      $numbers[] = sprintf($this->link_template, "index{$pos}.html{$parameters}", $this->startLinks);
    }

    for($i=$start; $i<=$end; $i++) {
      if ($this->rs->AbsolutePage() == $i) {
        $numbers[] = sprintf($this->current_template, $i);
      } else {
        $numbers[] = sprintf($this->link_template, "index{$i}.html{$parameters}", $i);
      }
    }

    if ($this->moreLinks && $end < $pages)
      $numbers[] = sprintf($this->link_template, "index{$i}.html{$parameters}", $this->moreLinks);

    return $numbers;
  }


  //------------------------------------------------------
  // override this to control overall layout and formating
  function renderLayout($parameters='') {
    $header = $this->renderNav($parameters);
    $this->firstPage = $header[0];
    $this->prevPage = $header[1];
    $this->nextPage = $header[2];
    $this->lastPage = $header[3];
    //$this->index = implode('  ', $header[4]);
    $this->index = $header[4];
    $this->footer = $this->renderPageCount();
  }


  private function renderNav($parameters='') {
    $ret = array();
    if (!$this->rs->AtFirstPage()) {
      $ret[0]=$this->RenderFirst(true,$parameters);
      $ret[1]=$this->RenderPrev(true,$parameters);
    } else {
      $ret[0]=$this->RenderFirst(false,$parameters);
      $ret[1]=$this->RenderPrev(false,$parameters);
    }
    if ($this->showPageLinks){
      $ret[4]=$this->renderPageLinks($parameters);
    }
    if (!$this->rs->AtLastPage()) {
      $ret[2]=$this->RenderNext(true,$parameters);
      $ret[3]=$this->RenderLast(true,$parameters);
    } else {
      $ret[2]=$this->RenderNext(false,$parameters);
      $ret[3]=$this->RenderLast(false,$parameters);
    }
    return $ret;
  }


  //---------------------------
  // Display link to first page
  function renderFirst($anchor=true, $parameters="")  {
    if ($parameters!="")
      $parameters = "?".$parameters;
    if ($anchor) {
      $ret = "index.html".$parameters;
    } else {
      $ret = $parameters;
    }
    return $ret;
  }

  //--------------------------
  // Display link to next page
  function renderNext($anchor=true, $parameters="") {
    if ($parameters!="")
      $parameters = "?".$parameters;
    if ($anchor) {
      $ret = "index".($this->rs->AbsolutePage() + 1).".html".$parameters;
    } else {
      $ret = $parameters;
    }
    return $ret;
  }

  // Link to previous page
  function renderPrev($anchor=true, $parameters="") {
    if ($parameters!="")
      $parameters = "?".$parameters;
    if ($anchor) {
      $ret = "index".($this->rs->AbsolutePage() - 1).".html".$parameters;
    } else {
      $ret = $parameters;
    }
    return $ret;
  }

  //------------------
  // Link to last page
  //
  // for better performance with large recordsets, you can set
  // $this->db->pageExecuteCountRows = false, which disables
  // last page counting.
  function renderLast($anchor=true, $parameters="") {
    if (!$this->db->pageExecuteCountRows)
      return;
    if ($parameters!="")
      $parameters = "?".$parameters;
    if ($anchor) {
      $ret = "index".$this->rs->LastPageNo().".html".$parameters;
    } else {
      $ret = $parameters;
    }
    return $ret;
  }


  //-------------------
  // This is the footer
  private function renderPageCount() {
    if (!$this->db->pageExecuteCountRows) return '';
    $lastPage = $this->rs->LastPageNo();
    if ($lastPage == -1) $lastPage = 1; // check for empty rs.
    return "$this->page ".$this->curr_page."/".$lastPage."";
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
    $txt = '';
     if ($this->rs->AtFirstPage()) {
      $txt .= "<li><a class=\"prev\" href=\"javascript:void(0)\" rel=\"nofollow\">‹</a></li>\n";
    } else {
      $txt .= "<li><a class=\"prev\" href=\"$this->prevPage\" rel=\"nofollow\">‹</a></li>\n";
    }

    foreach($pagine as $v){
      $txt .= "<li>{$v}</li>\n";
    }

     if ($this->rs->AtLastPage()) {
      $txt .= "<li><a class=\"next\" href=\"javascript:void(0)\" rel=\"nofollow\">›</a></li>\n";
    } else {
      $txt .= "<li><a class=\"next\" href=\"$this->nextPage\" rel=\"nofollow\">›</a></li>\n";
    }
    //$txt .= "</ul>\n";
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

  function pagerArrList() {
    $ret = array();

    if (!$this->rs->AtFirstPage()) {
      $ret[] = sprintf($this->link_template, $this->prevPage, '‹');
    }

    foreach ($this->index AS $v) {
      $ret[] = $v;
    }

    if (!$this->rs->AtLastPage()) {
      $ret[] = sprintf($this->link_template, $this->nextPage, '›');
    }
    return $ret;
  }

}

// EOF class.synPagerPublic.php
