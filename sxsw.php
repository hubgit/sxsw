<?php

require 'Zend/Dom/Query.php';

$dom = @DOMDocument::loadHTMLFile('http://sxsw.com/music/shows/bands');
$dom = new Zend_Dom_Query($dom->saveHTML());

$items = $dom->query("#node-794 .content p a");

$artists = array();
$out = fopen('sxsw.csv', 'w');
foreach ($items as $item){
  $xml = simplexml_import_dom($item);
  $data = array(
    trim((string) $xml),
    (string) $xml['href'],
    );
  
  fputcsv($out, $data, "\t", '"');
}
fclose($out);

