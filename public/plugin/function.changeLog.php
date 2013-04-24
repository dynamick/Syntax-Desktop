<?php
// return last Git commit comments.
function smarty_function_changeLog($params, &$smarty){
  global $synAbsolutePath;

  $max = $params['max'] ? $params['max'] : 4;
  $html = '';
  $output = array();
  $history = array();
  
  chdir($synAbsolutePath);
  exec("git log --date=short --max-count={$max}", $output);

  foreach($output as $line){
    if(strpos($line, 'commit')===0){
      if(!empty($commit)){
        array_push($history, $commit);	
        unset($commit);
      }
      $commit['hash'] = substr($line, strlen('commit'));
    } else if(strpos($line, 'Author')===0){
      $commit['author'] = substr($line, strlen('Author:'));
    } else if(strpos($line, 'Date')===0){
      $commit['date'] = substr($line, strlen('Date:'));
    } else {	
      if(isset($commit['message']))
        $commit['message'] .= $line;
      else
        $commit['message'] = $line;    
    }
  }
  
  if(!empty($commit)) {
    array_push($history, $commit);
  }
  
  foreach($history as $commit){
    $commit = array_map('trim', $commit);
    $data   = sql2human($commit['date'], '%b <strong>%d</strong>');
    $html  .= <<<EOCOMMIT
    
    <article class="fourth">
      <a href="https://github.com/dynamick/Syntax-Desktop/commit/{$commit['hash']}" target="_blank">
        <header>
          <time class="cal-icon" time="{$commit['date']}">
            {$data}
          </time>
          <h4>{$commit['author']}</h4>
        </header>
        <p>{$commit['message']}</p>
      </a>
    </article>
    
EOCOMMIT;
  }

  return $html;
}

// EOF function.changeLog.php
