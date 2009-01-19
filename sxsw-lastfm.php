<?php

require 'config.inc.php';

$in = fopen('sxsw.csv', 'r');
$out = fopen('sxsw-lastfm.csv', 'w');
while ($data = fgetcsv($in, 0, "\t", '"')){
  list($artist, $url) = $data;
  $params = array(
    'api_key' => $config['lastfm-api-key'],
    'method' => 'artist.getinfo',
    'artist' => $artist,
    );
  
  $url = 'http://ws.audioscrobbler.com/2.0/?' . http_build_query($params);
  $xml = simplexml_load_string(get($url));
  $xml = $xml->artist[0];

  $data = array(
    'name' => (string) $xml->name,
    'mbid' => (string) $xml->mbid,
    'listeners' => (int) $xml->stats->listeners,
    'playcount' => (int) $xml->stats->playcount,
    'url' => (string) $xml->url,
    'image' => (string) $xml->image[2],
    'streamable' => (int) $xml->streamable,
    'similar' => implode('|', stringify($xml->similar->artist, 'name')),
    );
  fputcsv($out, $data, "\t", '"');
}

fclose($in);
fclose($out);

function stringify($xml, $node){
  if (empty($xml))
    return array();
    
  $items = array();
  foreach ($xml as $item)
    $items[] = (string) $item->{$node};
  
  print_r($items);
  return $items; 
}

function get($url){
  print $url . "\n";
  $file = sprintf('cache/%s.xml', md5($url));
  if (file_exists($file))
    return file_get_contents($file);
  
  $data = file_get_contents($url);
  if ($data)
    file_put_contents($file, $data);
  
  sleep(1);
  
  return $data;
}