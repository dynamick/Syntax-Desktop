<?php

/*
  MySQL database restore class, version 1.0.0
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  Released under GNU Public license
*/


define('MSR_VERSION', '1.0.0');

define('MSR_STRING', 0);
define('MSR_FILE', 1);


class MySQL_Restore
{

  var $server = 'localhost';
  var $port = 3306;
  var $username = 'root';
  var $password = '';
  var $database = '';
  var $link_id = -1;
  var $connected = false;
  var $queries = array();
  var $error = '';


  function Execute($param, $mode = MSR_STRING, $is_compressed = false, $split_only = false)
  {
    if ($mode == MSR_FILE)
    {
      if (!($sql = $this->_ReadFromFile($param, $is_compressed)))
      {
        $this->error = 'Can\'t read backup file.';
        return false;
      }
    }
    else
    {
      $sql = ($is_compressed ? gzuncompress($param) : $param);
    }
    return $this->_Restore($sql, $split_only);
  }


  function _Connect()
  {
    $value = false;
    if (!$this->connected)
    {
      $this->link_id = mysqli_connect($this->server, $this->username, $this->password);
    }
    if ($this->link_id)
    {
      if (empty($this->database))
      {
        $value = true;
      }
      elseif ($this->link_id !== -1)
      {
        $value = mysqli_select_db($this->link_id, $this->database);
      }
      else
      {
        $value = mysqli_select_db($this->link_id, $this->database);
      }

      // setto la connessione in UTF8
      mysqli_set_charset($this->link_id, 'utf8');
    }
    if (!$value)
    {
      $this->error = mysqli_error($this->link_id);
    }
    return $value;
  }


  function _Query($sql)
  {
    if ($this->link_id !== -1)
    {
      $result = mysqli_query($this->link_id, $sql);
    }
    else
    {
      $result = mysqli_query($this->link_id, $sql);
    }
    if (!$result)
    {
      $this->error = mysqli_error($this->link_id);
    }
    return $result;
  }


  // The logic from phpMyAdmin source
  function _Decompose(&$ret, $sql)
  {
    $sql = rtrim($sql, "\n\r");
    $sql_len = strlen($sql);
    $char = '';
    $string_start = '';
    $in_string = false;
    $nothing = true;
    $time0 = time();
    for ($i = 0; $i < $sql_len; ++$i)
    {
      $char = $sql[$i];
      if ($in_string)
      {
        for (;;)
        {
          $i = strpos($sql, $string_start, $i);
          if (!$i)
          {
            $ret[] = $sql;
            return true;
          }
          elseif ($string_start == '`' || $sql[$i - 1] != '\\')
          {
            $string_start = '';
            $in_string = false;
            break;
          }
          else
          {
            $j = 2;
            $escaped_backslash = false;
            while ($i - $j > 0 && $sql[$i - $j] == '\\')
            {
              $escaped_backslash = !$escaped_backslash;
              $j++;
            }
            if ($escaped_backslash)
            {
              $string_start = '';
              $in_string = false;
              break;
            }
            else
            {
              $i++;
            }
          }
        }
      }
      else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' &&
        $sql[$i + 2] <= ' ') || $char == '#' ||
        ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*'))
      {
        $comm_end = ($char == '/' ? '*/' : "\n");
        $i = strpos($sql, $comm_end, $i);
        if ($i === false)
        {
          break;
        }
        if ($char == '/')
        {
          $i++;
        }
      }
      else if ($char == ';')
      {
        $ret[] = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
        $nothing = true;
        $sql = ltrim(substr($sql, min($i + 1, $sql_len)));
        $sql_len = strlen($sql);
        if ($sql_len)
        {
          $i = -1;
        }
        else
        {
          return true;
        }
      }
      else if (($char == '"') || ($char == '\'') || ($char == '`'))
      {
        $in_string = true;
        $nothing = false;
        $string_start = $char;
      }
      elseif ($nothing)
      {
        $nothing = false;
      }
      $time1 = time();
      if ($time1 >= $time0 + 30)
      {
        $time0 = $time1;
        @header('X-pmaPing: Pong');
      }
    }
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql))
    {
      $ret[] = array('query' => $sql, 'empty' => $nothing);
    }
    return true;
  }


  function _Restore($sql, $split_only)
  {
    if (!$this->_Connect())
    {
      return false;
    }
    if (!$this->_Decompose($queries, $sql))
    {
      return false;
    }
    $errors = '';
    $this->queries = array();
    foreach ($queries as $query)
    {
      $this->queries[] = $query['query'];
      if (!$split_only)
      {
        if (!$this->_Query($query['query']))
        {
          $errors .= $this->error . '<br /><br />';
        }
      }
    }
    if (!empty($errors))
    {
      $this_error = $errors;
      return false;
    }
    return true;
  }


  function _ReadFromFile($fname, $is_compressed)
  {
    if ($is_compressed)
    {
      $sql = gzfile($fname);
    }
    else
    {
      $sql = file($fname);
    }
    if ($sql === false)
    {
      return false;
    }
    return implode('', $sql);
  }


}

?>
