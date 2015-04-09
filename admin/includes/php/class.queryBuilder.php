<?php
/**
 * queryBuilder Class
 * Dynamically generates SQL queries
 * inspired by David Regla's SqlQueryBuilder class
 *
 * @author Marco Pozzato <marco@kleis.it>
 */
class queryBuilder {
  public $command;

  protected $_tables = array();
  protected $_joins = array();
  protected $orderBy = array();
  protected $whereClause  = array();
  protected $orClause = array();
  protected $groupBy;
  protected $having;
  protected $limit;

  private $_padlength = 10;
  private $_padtype = STR_PAD_LEFT;
  private $_tablecounter = 1;

  public function __construct($command='SELECT') {
    $this->command = strtoupper($command);
  }

  public function getQuery() {
    if (sizeof($this->_tables)==0) {
      throw new Exception('No tables selected!');
    }

    $columns = $tables = $joins = array();
    $sql = '';

    switch ($this->command) {
      case 'SELECT':

        foreach($this->_tables AS $t){
          $columns[] = $t->getFields();
          $tables[] = $t->getFullName();
        }

        foreach($this->_joins AS $j){
          $columns[] = $j->getFields();
          $joins[] = $this->_pad($j->getMode().' JOIN').$j->getFullName().$j->getOn();
        }

        $sql .= $this->_pad($this->command);
        $sql .= implode(', '.$this->_pad(''), $columns);
        $sql .= $this->_pad('FROM');
        $sql .= implode(', '.$this->_pad(''), $tables);
        $sql .= implode(' ', $joins);
        $sql .= $this->getWhere();
        $sql .= $this->getGroupBy();
        $sql .= $this->getHaving();
        $sql .= $this->getOrderBy();
        $sql .= $this->getLimit();

        break;
      case 'INSERT':
        // to do
        break;
      case 'UPDATE':
        // to do
        break;
      case 'DELETE':
        // to do
        break;
      default:
        throw new Exception('No SQL command!');
        break;
    }

    return $sql;
  }

  public function getCountQuery() {
    if (sizeof($this->_tables) == 0)
      throw new Exception('No tables selected!');

    $columns = $tables = $joins = array();
    $sql = '';

    foreach($this->_tables AS $t){
      $columns[] = $t->getFields();
      $tables[] = $t->getFullName();
    }

    foreach($this->_joins AS $j){
      $columns[] = $j->getFields();
      $joins[] = $this->_pad($j->getMode().' JOIN').$j->getFullName().$j->getOn();
    }

    $sql .= $this->_pad('SELECT COUNT(*) AS count');
    $sql .= $this->_pad('FROM');
    $sql .= implode(', '.$this->_pad(''), $tables);
    $sql .= implode(' ', $joins);
    $sql .= $this->getWhere();
    $sql .= $this->getGroupBy();
    $sql .= $this->getHaving();
    $sql .= $this->getOrderBy();

    return $sql;
  }

  public function addTable($name, $alias='') {
    $alias = $this->_setAlias($alias);
    $table = new tableEntity($name, $alias);
    $this->_tables[] = $table;
    return $table;
  }

  public function addJoin($name, $alias='') {
    $alias = $this->_setAlias($alias);
    $table = new tableJoinEntity($name, $alias);
    $this->_joins[] = $table;
    return $table;
  }

  public function addSearchClause( $needle ) {
    $columns = array();
    foreach( $this->_tables AS $t ){
      $fields = explode(', ', $t->getFields(false));
      foreach( $fields as $f )
        $columns[] = $f;
    }
    foreach($this->_joins AS $j){
      $fields = explode(', ', $j->getFields(false));
      foreach( $fields as $f )
        $columns[] = $f;
    }
    $clause = array();
    foreach( $columns as $column)
      $clause[] = "{$column} LIKE '%{$needle}%'";

    $this->addWhere( '('.implode(' OR ', $clause).')' );
    //echo '<pre>', print_r($columns), '</pre>'; die();
  }

  public function addWhereClause($field, $value='', $operator='=') {
    if (is_array($value)){
      $operator = 'IN';
      $value = '('.implode(', ', $value).')';
    }
    $this->whereClause[] = $field.' '.strtoupper($operator).' '.$value;
  }

  public function addWhere($clause) {
    $this->whereClause[] = $clause;
  }

  public function addOrClause($field, $value, $operator='=') {
    if(is_array($value)){
      $operator = 'IN';
      $value = '('.implode(', ', $value).')';
    }
    if($this->whereClause){
      $this->orClause[] = $field.' '.strtoupper($operator).' '.$value;
    } else {
      $this->whereClause[] = $field.' '.strtoupper($operator).' '.$value;
    }
  }

  public function setGroupBy($groupBy) {
    $this->groupBy = $groupBy;
  }

  public function setHaving($having) {
    $this->having = $having;
  }

  public function setLimit($limit, $offset=0) {
    $this->limit = "{$offset}, {$limit}";
  }

  public function addOrderBy($field, $dir='ASC', $table=null) {
    $prefix = '';
    // prevent ambigous fields by prepending table alias
    if (!empty($table)) {
      foreach( $this->_tables AS $t ) {
        if ($table == $t->getName())
          $prefix = $t->getAlias().'.';
      }
    }
    $this->orderBy[] = "{$prefix}`{$field}` {$dir}";
  }

  public function debug(){
    echo '<pre>', $this->getQuery(), '</pre>', PHP_EOL;
  }


  public function getWhere(){
    $where = '';
    array_filter($this->whereClause);
    array_filter($this->orClause);

    if(sizeof($this->whereClause)>0){
      $where = $this->_pad('WHERE').'('.implode(' AND ', $this->whereClause).')';
      if(sizeof($this->orClause)>0){
        $where .= $this->_pad('OR').'('.implode(' OR ', $this->orClause).')';
      }
    }
    return $where;
  }

  public function getOrderBy(){
    $order = '';
    if(sizeof($this->orderBy)>0){
      $order = $this->_pad('ORDER BY').implode(', ', $this->orderBy);
    }
    return $order;
  }

  public function getGroupBy(){
    if ($this->groupBy)
      return $this->_pad('GROUP BY').$this->groupBy;
  }

  public function getHaving(){
    if ($this->having)
      return $this->_pad('HAVING').$this->having;
  }

  //force an unique alias, if it isn't provided
  private function _setAlias($alias){
    if($alias=='') {
      $alias = 't'.$this->_tablecounter;
      $this->_tablecounter ++;
    }
    return $alias;
  }

  public function getLimit(){
    if ($this->limit)
      return $this->_pad('LIMIT').$this->limit;
  }

  private function _pad($str){
    return PHP_EOL.str_pad($str, $this->_padlength, ' ', $this->_padtype).' ';
  }
}


class tableEntity {
  protected $name;
  protected $alias;
  protected $on;
  private $_fields = array();
  private $_clean_fields = array();

  public function __construct($name, $alias='') {
    $this->name = $name;
    $this->alias = $alias;
  }

  public function getName() {
    return $this->name;
  }

  public function getFullName() {
    return "`{$this->name}` {$this->alias}";
  }

  public function getAlias() {
    if($this->alias){
      $alias = $this->alias;
    } else {
      $alias = $this->name;
    }
    return $alias;
  }

  public function addField($field, $alias='') {
    $column = $this->getAlias().(($alias) ? ".`{$field}` AS {$alias}" : ".`{$field}`");
    $this->_fields[] = $column;
    // fields without alias are needed for 'where' clauses
    $clean_column = $this->getAlias().".`{$field}`";
    $this->_clean_fields[] = $clean_column;
  }

  public function setField($field){
    return $this->getAlias().".`{$field}`";
  }

  public function getFields($alias = true){
    if (sizeof($this->_fields)>0) {
      if ($alias)
        $fields = implode(', ', $this->_fields);
      else
        $fields = implode(', ', $this->_clean_fields);
    } else {
      $fields = $this->getAlias().'.*';
    }
    return $fields;
  }
}

class tableJoinEntity extends tableEntity {
  protected $on;
  protected $mode;
  private $_allowed_modes = array('LEFT','RIGHT','INNER');

  public function setOn($field1, $field2){
    $this->on[] = "$field1 = $field2";
  }

  public function getOn(){
    return ' ON '.implode(', ', $this->on);
  }

  public function setMode($mode){
    $mode = strtoupper($mode);
    if(in_array($mode, $this->_allowed_modes)) $this->mode = $mode;
  }

  public function getMode(){
    return $this->mode;
  }
}
