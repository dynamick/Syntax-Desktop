<?php
function smarty_function_rss($params, &$smarty) {
  global $db, $synWebsiteTitle;

  // crea un nuovo oggetto ed istanzia le caratteristiche del feed
  $rss = new UniversalFeedCreator();
  $rss->useCached();
  $rss->title          = $synWebsiteTitle." News";
  $rss->description    = "daily news from the ".$synWebsiteTitle." world";
  $rss->link           = "http://".$_SERVER["SERVER_NAME"]."/news";
  $rss->syndicationURL = "http://".$_SERVER["SERVER_NAME"]."/".$PHP_SELF;

  $qry="SELECT * FROM news ORDER BY `date` DESC LIMIT 0,10";
  $res=$db->Execute($qry);

  while ($arr=$res->FetchRow()) {
    $data = $arr["date"];
  	if (!strpos($data,":")) {
		  $ar_data = explode("-",$data);
      $ar_ora = array('0','0','0');
		} else {
		  $dd = explode(" ",$data);
		  $ar_data = explode("-",$dd[0]);
		  $ar_ora = explode(":",$dd[1]);
    }

    $item              = new FeedItem();
    $item->title       = translateSite($arr["title"]);
    $item->link        = "http://".$_SERVER["SERVER_NAME"]."/news/?id=".$arr["id"];
    $item->description = translateSite($arr["text"]);
    // data in formato "Thu, 28 Aug 1975 15:30:00 +0100"
    $item->date        = date("D, d M Y H:i:s O", mktime($ar_ora[0], $ar_ora[1], $ar_ora[2], $ar_data[1], $ar_data[2], $ar_data[0]));
    $item->source      = "http://".$_SERVER["SERVER_NAME"];
    // aggiunge la news alla lista di elementi del feed
    $rss->addItem($item);
  }

  $rss->outputFeed("RSS1.0");
  return;
}

?>
