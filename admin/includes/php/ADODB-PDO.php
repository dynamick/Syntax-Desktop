<?php
#ADODB-PDO.php: PDO wrapper to provide an ADODB interface
#http://imrannazar.com/Interfacing-ADODB-to-PDO

define('ADODB_FETCH_NUM', PDO::FETCH_NUM);
define('ADODB_FETCH_ASSOC', PDO::FETCH_ASSOC);
define('ADODB_FETCH_BOTH', PDO::FETCH_BOTH);

/**
* Connection and query wrapper
*/
class ADODB_PDO
{
	/** PDO connection to wrap */
	private $_db;

	/** Connection information (database name is public) */
	private $connector;
	private $dsn;
	private $host;
	private $user;
	private $pass;
  public $database;

  /** Debug flag, publically accessible */
  public $debug;
  private $error;

  /** PDO demands fetchmodes on each resultset, so define a default */
  private $fetchmode;

  /** Number of rows affected by the last Execute */
  private $affected_rows;

  private $maxRecordCount;
  public $pageExecuteCountRows;

  /**
  * Constructor: Initialise connector
  * @param connector String denoting type of database
  */
  public function __construct($connector='mysql')
  {
    $this->connector = $connector;
  }

  /**
  * Connect: Establish connection to a database
  * @param host String
  * @param user String [optional]
  * @param pass String [optional]
  * @param database String [optional]
  */
  public function Connect($host, $user='', $pass='', $database='')
  {
    $this->host = $host;
    $this->user = $user;
    $this->pass = $pass;
    $this->database = $database;
    $this->pageExecuteCountRows = true;
    $this->maxRecordCount = 0;

    switch($this->connector)
    {
      case 'mysql':
        $this->dsn = sprintf('%s:host=%s;dbname=%s',
          $this->connector,
          $this->host,
          $this->database);
        try {
          $this->_db = new PDO($this->dsn, $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
          $this->error = $e->getMessage();
          $this->debug();
        }
        $this->_db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->fetchmode = ADODB_FETCH_BOTH;
        break;
    }
  }

  /**
  * SetFetchMode: Change the fetch mode of future resultsets
  * @param fm Integer specified by constant
  */
  public function SetFetchMode($fm)
  {
    $this->fetchmode = $fm;
  }

  /**
  * Insert_ID: Retrieve the ID of the last insert operation
  * @return String containing last insert ID
  */
  public function Insert_ID()
  {
    return $this->_db->lastInsertId();
  }


  /**
  * Execute: Retrieve a resultset from a query
  * @param sql String query to execute
  * @param vars Array of variables to bind [optional]
  * @return ADODB_PDO_ResultSet object
  */
  public function Execute($sql, $vars=null)
  {
    
    $st = $this->DoQuery($sql, $vars);
    $this->affected_rows = $st->rowCount();
    return $st?new ADODB_PDO_ResultSet($st):false;
  }


  /**
  * Affected_Rows: Retrieve the number of rows affected by Execute
  * @return The number of affected rows
  */
  public function Affected_Rows()
  {
    return $this->affected_rows;
  }

  /**
  * GetOne: Retrieve the first value in the first row of a query
  * @param sql String query to execute
  * @param vars Array of variables to bind [optional]
  * @return String data of the requested value
  */
  public function GetOne($sql, $vars=null)
  {
    $st = $this->DoQuery($sql, $vars);
    return $st?$st->fetchColumn():false;
  }

  /**
  * MetaColumns: Retrieve information about a table's columns
  * @param table String name of table to find out about
  * @return Array of ADODB_PDO_FieldData objects
  */
  public function MetaColumns($table)
  {
    $out = array();

    $st = $this->DoQuery('select * from '.$table);
    for($i=0; $i<$st->columnCount(); $i++){
      $column = $st->getColumnMeta($i);
      $out[strtoupper($column['name'])] = new ADODB_PDO_FieldData($column);
    }
    return $out;
  }

  /**
  * MetaTables: Returns an array of tables and views for the current database as an array.
  * @return Array of ADODB_PDO_FieldData objects
  */
  public function MetaTables($ttype=false, $showSchema=false)
  {
    $out = array();

    $st = $this->DoQuery('SHOW TABLES FROM '.$this->database);
    //SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA='yourdatabasename';
    while ($row = $st->fetch(PDO::FETCH_NUM)) {
      $out[] = $row[0];
    }
    return $out;
  }

  /**
  * DoQuery: Private helper function for Get*
  * @param sql String query to execute
  * @param vars Array of variables to bind [optional]
  * @return PDOStatement object of results, or false on fail
  */
  private function DoQuery($sql, $vars=null)
  {
    try {
      $statement = $this->_db->prepare($sql);
      if(!$statement) {
        $errorInfo = $this->_db->errorInfo();
        throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is $errorInfo[1]");
      }

      $statement->setFetchMode($this->fetchmode);
      if(!is_array($vars)) $vars = array($vars);

      if((!$statement->execute($vars)) || ($statement->errorCode() != '00000')) {
        $errorInfo = $statement->errorInfo();
        throw new PDOException("Database error [{$errorInfo[0]}]: {$errorInfo[2]}, driver error code is $errorInfo[1]");
      } 

    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      $this->debug($sql, $vars);
    }
    return $statement;
  }


  /**
  * Will select, getting rows from $offset (1-based), for $nrows.
  * This simulates the MySQL "select * from table limit $offset,$nrows" , and
  * the PostgreSQL "select * from table limit $nrows offset $offset". Note that
  * MySQL and PostgreSQL parameter ordering is the opposite of the other.
  * eg.
  *  SelectLimit('select * from table',3); will return rows 1 to 3 (1-based)
  *  SelectLimit('select * from table',3,2); will return rows 3 to 5 (1-based)
  *
  * Uses SELECT TOP for Microsoft databases (when $this->hasTop is set)
  * BUG: Currently SelectLimit fails with $sql with LIMIT or TOP clause already set
  *
  * @param sql
  * @param [offset]  is the row to start calculations from (1-based)
  * @param [nrows]    is the number of rows to get
  * @param [inputarr]  array of bind variables
  * @param [secs2cache]    is a private parameter only used by jlim
  * @return    the recordset ($rs->databaseType == 'array')
   */
  private function selectLimit($sql, $nrows=-1, $offset=-1, $vars=null)
  {
    //check che non sia già presente
    $sql = preg_replace('/LIMIT[0-9, ]*/', '', $sql);
    $sql = $sql.' LIMIT '.$offset.', '.$nrows;

    $st = $this->DoQuery($sql, $vars);
    $this->affected_rows = $st->rowCount();
    return $st?new ADODB_PDO_ResultSet($st):false;
  }


  /**
  * Will select the supplied $page number from a recordset, given that it is paginated in pages of
  * $nrows rows per page. It also saves two boolean values saying if the given page is the first
  * and/or last one of the recordset. Added by Iván Oliva to provide recordset pagination.
  *
  * See readme.htm#ex8 for an example of usage.
  *
  * @param sql
  * @param nrows    is the number of rows per page to get
  * @param page    is the page number to get (1-based)
  * @param [inputarr]  array of bind variables
  * @param [secs2cache]    is a private parameter only used by jlim
  * @return    the recordset ($rs->databaseType == 'array')
  *
  * NOTE: phpLens uses a different algorithm and does not use PageExecute().
  *
  */
  public function PageExecute($sql, $nrows, $page, $vars=null)
  {
    #if ($this->pageExecuteCountRows) $rs =& _adodb_pageexecute_all_rows($this, $sql, $nrows, $page, $inputarr, $secs2cache);
    #else $rs =& _adodb_pageexecute_no_last_page($this, $sql, $nrows, $page, $inputarr, $secs2cache);

    return $this->_pageexecute_all_rows($sql, $nrows, $page, $vars);
  }

  /*
     Code originally from "Cornel G" <conyg@fx.ro>

    This code might not work with SQL that has UNION in it

    Also if you are using CachePageExecute(), there is a strong possibility that
    data will get out of synch. use CachePageExecute() only with tables that
    rarely change.
  */
  private function _pageexecute_all_rows($sql, $nrows, $page, $vars=null)
  {
    $atfirstpage = false;
    $atlastpage = false;
    $lastpageno = 1;

    // If an invalid nrows is supplied,
    // we assume a default value of 10 rows per page
    if (!isset($nrows) || $nrows <= 0) $nrows = 10;

    $qryRecs = false; //count records for no offset

    $qryRecs = $this->_getcount($sql, $vars=null, $secs2cache);
    $lastpageno = (int) ceil($qryRecs / $nrows);
    $this->maxRecordCount = $qryRecs;

    // ***** Here we check whether $page is the last page or
    // whether we are trying to retrieve
    // a page number greater than the last page number.
    if ($page >= $lastpageno) {
      $page = $lastpageno;
      $atlastpage = true;
    }

    // If page number <= 1, then we are at the first page
    if (empty($page) || $page <= 1) {
      $page = 1;
      $atfirstpage = true;
    }

    // We get the data we want
    $offset = $nrows * ($page-1);

    $st = $this->SelectLimit($sql, $nrows, $offset, $vars=null);

    // Before returning the RecordSet, we set the pagination properties we need
    if ($st) {
      $st->_maxRecordCount = $qryRecs;
      $st->rowsPerPage = $nrows;
      $st->AbsolutePage($page);
      $st->AtFirstPage($atfirstpage);
      $st->AtLastPage($atlastpage);
      $st->LastPageNo($lastpageno);
    }
    return $st;
  }


  private function _strip_order_by($sql)
  {
    $rez = preg_match('/(\sORDER\s+BY\s[^)]*)/is',$sql,$arr);
    if ($arr) {
      if (strpos($arr[0],'(') !== false) {
        $at = strpos($sql,$arr[0]);
        $cntin = 0;
        for ($i=$at, $max=strlen($sql); $i < $max; $i++) {
          $ch = $sql[$i];
          if ($ch == '(') {
            $cntin += 1;
          } elseif($ch == ')') {
            $cntin -= 1;
            if ($cntin < 0) {
              break;
            }
          }
        }
        $sql = substr($sql,0,$at).substr($sql,$i);
      } else {
        $sql = str_replace($arr[0], '', $sql);
      }
    }
    return $sql;
  }


  private function _getcount($sql, $vars=null)
  {
    $qryRecs = 0;

     if ( preg_match("/^\s*SELECT\s+DISTINCT/is", $sql)
       || preg_match('/\s+GROUP\s+BY\s+/is',$sql)
       || preg_match('/\s+UNION\s+/is',$sql)) {

       $rewritesql = $this->_strip_order_by($sql);
       $rewritesql = "SELECT COUNT(*) FROM ($rewritesql) AS adodbpdocount ";

    } else {
      // now replace SELECT ... FROM with SELECT COUNT(*) FROM
      $rewritesql = preg_replace('/^\s*SELECT\s.*\s+FROM\s/Uis','SELECT COUNT(*) FROM ',$sql);
      $rewritesql = $this->_strip_order_by($rewritesql);
    }

    if (isset($rewritesql) && $rewritesql != $sql) {
      if (preg_match('/\sLIMIT\s+[0-9]+/i', $sql, $limitarr)) $rewritesql .= $limitarr[0];
      $qryRecs = $this->GetOne($rewritesql, $inputarr);

      if ($qryRecs !== false) return $qryRecs;
    }
    //--------------------------------------------
    // query rewrite failed - so try slower way...


    // strip off unneeded ORDER BY if no UNION
    if (preg_match('/\s*UNION\s*/is', $sql)) $rewritesql = $sql;
    else $rewritesql = $rewritesql = $this->_strip_order_by($sql);

    if (preg_match('/\sLIMIT\s+[0-9]+/i', $sql, $limitarr)) $rewritesql .= $limitarr[0];
    $rstest = $this->Execute($rewritesql, $inputarr);
    if (!$rstest) $rstest = $this->db->Execute($sql, $inputarr);

    if ($rstest) {
      $qryRecs = $rstest->db->RecordCount();
      if ($qryRecs == -1) {
        while(!$rstest->EOF) {
          $rstest->MoveNext();
        }
        $qryRecs = $rstest->_currentRow;
      }
      $rstest->Close();
      if ($qryRecs == -1) return 0;
    }
    return $qryRecs;
  }


  private function debug($sql=null, $vars=null) {
    $error = array("Error" => $this->error);
    if(!empty($sql))
      $error["SQL Statement"] = $sql;
    if(!empty($vars) && count(array_filter($vars))>0)
      $error["Bind Parameters"] = trim(print_r($vars, true));

    $backtrace = debug_backtrace(); //(false);
    if(!empty($backtrace)) {
/*
      $error["Backtrace"] = "<ol>\n";
      foreach($backtrace as $v){
        if($v['function'] == "include" || $v['function'] == "include_once" || $v['function'] == "require_once" || $v['function'] == "require"){
          $error["Backtrace"] .= "<li><b>".$v['function']."(".$v['args'][0].")</b> called at [".$v['file'].":".$v['line']."]</li>";
        } else {
          $error["Backtrace"] .= "<li><b>".$v['function']."()</b> called at [".$v['file'].":".$v['line']."]<br />";
        }
      }
      $error["Backtrace"] .= "</ol>\n";
*/
      foreach($backtrace as $info) {
        if($info["function"] == 'Execute')
          $error["Backtrace"] .= $info["function"]."() called at ".$info["file"]." at line ".$info["line"]."<br />\n";
      }
    }

    if(!empty($error["Bind Parameters"]))
      $error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
    $css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
    $msg .= "<style type=\"text/css\">\n".$css."\n</style>";
    $msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
    foreach($error as $key => $val){
      $msg .= "\n\t<label>".$key.":</label>".$val;
    }
    $msg .= "\n\t</div>\n";

    echo $msg;
  }
}

/**
* Resultset wrapper
*/
class ADODB_PDO_ResultSet
{
  /** PDO resultset to wrap */
  private $_st;

  /** One-time resultset information */
  private $results;
  private $rowcount;
  private $cursor;

  /** Publically accessible row values */
  public $fields;

  /** Public end-of-resultset flag */
  public $EOF;

  /** recordset pagination **/
  private $_currentPage;  
  private $_atFirstPage;
  private $_atLastPage;
  private $_lastPageNo;
  public $rowsPerPage;
  public $_maxRecordCount;
  

  /**
  * Constructor: Initialise resultset and first results
  * @param st PDOStatement object to wrap
  */
  public function __construct($st)
  {
    $this->_st = $st;
    $this->results = $st->fetchAll();
    $this->rowcount = count($this->results);
    $this->cursor = 0;
    $this->_currentPage = -1;
    $this->_atFirstPage = false;
    $this->_atLastPage = false;
    $this->_lastPageNo = -1;
    $this->_maxRecordCount = 0;
    $this->rowsPerPage = 0;
    $this->MoveNext();
  }

  /**
  * RecordCount: Retrieve number of records in this RS
  * @return Integer number of records
  */
  public function RecordCount()
  {
    return $this->rowcount;
  }


  /**
  * MoveNext: Fetch next row and check if we're at the end
  */
  public function MoveNext()
  {
    $this->fields = $this->results[$this->cursor++];
    $this->EOF = ($this->cursor == $this->rowcount) ? 1 : 0;
  }


  public function FetchRow()
  {
    $res = $this->fields;
    $this->MoveNext();

    return $res;
  }
  

  /**
   * If we are using PageExecute(), this will return the maximum possible rows
   * that can be returned when paging a recordset.
   */
  function MaxRecordCount()
  {
    return ($this->_maxRecordCount) ? $this->_maxRecordCount : $this->RecordCount();
  }

  /**
   * set/returns the current recordset page when paginating
   */
  function AbsolutePage($page=-1)
  {
    if ($page != -1) $this->_currentPage = $page;
    return $this->_currentPage;
  }

  /**
   * set/returns the status of the atFirstPage flag when paginating
   */
  function AtFirstPage($status=false)
  {
    if ($status != false) $this->_atFirstPage = $status;
    return $this->_atFirstPage;
  }

  function LastPageNo($page = false)
  {
    if ($page != false) $this->_lastPageNo = $page;
    return $this->_lastPageNo;
  }

  /**
   * set/returns the status of the atLastPage flag when paginating
   */
  function AtLastPage($status=false)
  {
    if ($status != false) $this->_atLastPage = $status;
    return $this->_atLastPage;
  }
}

/**
 * Table field information wrapper
 */
class ADODB_PDO_FieldData
{
  public $name;
  public $max_length;
  public $type;
  public $not_null;
  public $primary_key;
  public $auto_increment;
  public $binary;
  public $unsigned;
  public $zerofill;
  public $has_default;
  
  public $debug;

  /**
  * Constructor: Map PDO meta information to object field data
  * @param meta Array from PDOStatement::getColumnMeta
  */
  public function __construct($meta)
  {
    #echo '<pre>', print_r($meta), '</pre>';
    $lut = array(
      'LONG' => 'int',
      'VAR_STRING' => 'varchar',
      'BLOB' => 'text'
    );

    $this->debug = print_r($meta, 1);
    $this->name = $meta['name'];
    $this->max_length = $meta['len'];
    $this->type = $lut[$meta['native_type']];
    $this->not_null = in_array('not_null', $meta['flags']) ? 1 : null;
    $this->primary_key = in_array('primary_key', $meta['flags']) ? 1 : null;
/*
    $this->auto_increment = $meta['auto_increment'];
    $this->binary = $meta['binary'];
    $this->unsigned = $meta['unsigned'];
    $this->zerofill = $meta['zerofill'];
    $this->has_default = $meta['has_default'];
*/
  }
}

/**
 * Class for pagination handling
 */
class synPager {

  public $rs;
  public $index;
  public $curr_page;
  private $sql;
  private $count;
  private $db;
  private $id;
  private $showPageLinks;
  private $linksPerPage;
  private $next_page;
  private $targetFile;
  private $targetFrame;


  public function __construct($db, $id='adodb', $targetFile, $targetFrame, $showPageLinks=false, $use_session=false)
  {
    $this->db = $db;
    $this->id = $id;
    $this->linksPerPage = 10;
    $this->showPageLinks = true;
    $this->next_page = $this->id.'_next_page';
    $this->curr_page = $this->id.'_curr_page';
    $this->targetFile = $targetFile;
    $this->targetFrame = $targetFrame;

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
      $this->curr_page = ($_GET[$this->next_page]) ? intval($_GET[$this->next_page]) : 1;
    }
  }


  public function Execute($sql, $rows=10,$parameters=null)
  {
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


  private function renderPageLinks($parameters=null)
  {
    $pages        = $this->rs->LastPageNo();
    $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
    $link         = $this->id.'_next_page';
    $targetframe  = ($this->targetFrame!='') ? " target=\"{$this->targetFrame}\"" : '';
    $targetfile   = ($this->targetFile!='') ?  "{$this->targetFile}?{$link}=%d" : 'index%d.html';

    if($parameters) $parameters = '?'.$parameters;

    for($i=1; $i<=$pages; $i+=$linksperpage)
      if($this->rs->AbsolutePage() >= $i) $start = $i;

    $numbers = array(); //= '';
    $end     = $start+$linksperpage-1;
    if($end > $pages)
        $end = $pages;

    if ($this->startLinks && $start > 1) {
      $url = sprintf($targetfile, $pos);
      $pos = $start - 1;
      $numbers[] = "<a{$targetframe} href=\"{$url}{$parameters}\">{$this->startLinks}</a>";
    }

    for($i=$start; $i<=$end; $i++) {
      if ($this->rs->AbsolutePage() == $i) {
        $numbers[] = "<strong>$i</strong>";
      } else {
        $url = sprintf($targetfile, $i);
        $numbers[] = "<a{$targetframe} href=\"{$url}{$parameters}\">{$i}</a>";
      }
    }

    if ($this->moreLinks && $end < $pages){
      $url = sprintf($targetfile, $i);
      $numbers[] = "<a{$targetframe} href=\"{$url}{$parameters}\">{$this->moreLinks}</a>  ";
    }
    return $numbers;
  }

  //------------------------------------------------------
  // override this to control overall layout and formating
  function renderLayout($parameters='')
  {
    $header = $this->renderNav($parameters);
    $this->firstPage = $header[0];
    $this->prevPage = $header[1];
    $this->nextPage = $header[2];
    $this->lastPage = $header[3];
    $this->index = implode('  ', $header[4]);
    $this->footer = $this->renderPageCount();
  }


  private function renderNav($parameters='')
  {
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

  private function renderFirst($anchor=true, $parameters="")
  {
    if ($anchor) {
      if ($parameters!="") $parameters="&".$parameters;
      $ret=$this->targetFile."?".$this->id."_next_page=1".$parameters;
    } else {
      if ($parameters!="") $parameters="?".$parameters;
      $ret=$this->targetFile.$parameters;
    }
    return $ret;
  }

  //--------------------------
  // Display link to next page
  private function renderNext($anchor=true, $parameters="")
  {
    if ($anchor) {
      if ($parameters!="") $parameters="&".$parameters;
      $ret=$this->targetFile."?".$this->id."_next_page=".($this->rs->AbsolutePage() + 1).$parameters;
    } else {
      if ($parameters!="") $parameters="?".$parameters;
      $ret=$this->targetFile.$parameters;
    }
    return $ret;
  }

  // Link to previous page
  private function renderPrev($anchor=true, $parameters="")
  {
    if ($anchor) {
      if ($parameters!="") $parameters="&".$parameters;
      $ret=$this->targetFile."?".$this->id."_next_page=".($this->rs->AbsolutePage() - 1).$parameters;
    } else {
      if ($parameters!="") $parameters="?".$parameters;
      $ret=$this->targetFile.$parameters;
    }
    return $ret;
  }

  //------------------
  // Link to last page
  //
  // for better performance with large recordsets, you can set
  // $this->db->pageExecuteCountRows = false, which disables
  // last page counting.
  private function renderLast($anchor=true, $parameters="")
  {
    if (!$this->db->pageExecuteCountRows) return;

    if ($anchor) {
      if ($parameters!="") $parameters="&".$parameters;
      $ret=$this->targetFile."?".$this->id."_next_page=".$this->rs->LastPageNo().$parameters;
    } else {
      if ($parameters!="") $parameters="?".$parameters;
      $ret=$this->targetFile.$parameters;
    }
    return $ret;
  }

  //-------------------
  // This is the footer
  private function renderPageCount()
  {
    if (!$this->db->pageExecuteCountRows) return '';
    $lastPage = $this->rs->LastPageNo();
    if ($lastPage == -1) $lastPage = 1; // check for empty rs.
    return "$this->page ".$this->curr_page."/".$lastPage."";
  }


  // simple pager
  public function renderPgr()
  {
    $pagine = explode('  ', $this->index);

    foreach($pagine as $v){
      $txt .= "$v ";
      //$txt .= str_replace('index', $layout, $v)." ";
    }
    return "Pagina: ".$txt;
  }

}


/**
* NewADOConnection: Thin wrapper to generate a new ADODB_PDO object
* @param connector String denoting type of database
* @return ADODB_PDO object
*/
function NewADOConnection($connector)
{
  return new ADODB_PDO($connector);
}
