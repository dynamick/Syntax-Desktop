<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.trad.php
 * Type:     function
 * Name:     trad
 * Purpose:  recupera il valore di una o piu' etichette dal Vocabolario
 * Author:   Marco
 * -------------------------------------------------------------
 */
 
/*
 ESEMPI DI UTILIZZO:
 * Etichetta singola
   <h3>{trad label='titolo_produzione'}</h3>

 * Etichette multiple
   {trad label='titolo_produzione|news_eventi|ultimi_progetti' varname='t'}
   <h3>{$t.titolo_produzione}</h3>
   <h3>{$t.news_eventi}</h3>
*/

function smarty_function_trad($params, &$smarty){
  $txt = $params['label'];

  if(substr_count($txt, '|')>0 && $params['varname']){
    # array
    $t = multiTranslateDictionary(explode('|', $txt));
    $smarty->assign($params['varname'], $t);

  } else {
    # stringa
    return translateDictionary($txt);
  }
}
?>
