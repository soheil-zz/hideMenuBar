<?php

$plists1 = explode("\n", `ls /Applications/ | awk '{ print "/Applications/"$0"/Contents/Info.plist" }'`);
$plists2 = explode("\n", `ls /Applications/Utilities | awk '{ print "/Applications/Utilities/"$0"/Contents/Info.plist" }'`);
$plists = array_merge($plists1, $plists2);
$app = isset($argv[1]) ? $argv[1] : '';
foreach ($plists as $plist) {
  if ($app && stripos($plist, $app) === FALSE) continue;
  echo $plist . "\n";
  // if 2nd arg is not set this is a dry run
  if (!isset($argv[2])) continue;
  if (!$plist || strpos($plist, '.DS_Store') !== FALSE || strpos($plist, '.localized') !== FALSE || strpos($plist, 'Utilities') !== FALSE) continue;
  $c = file_get_contents($plist);
  if (strpos($c, 'LSUIPresentationMode') !== FALSE) continue;
  $c = preg_replace('/(plist.*?\n<dict>)/', "$0\n\t<key>LSUIPresentationMode</key>\n\t<integer>4</integer>", $c);
  `chmod +w "$plist"`;
  file_put_contents($plist, $c);
}

