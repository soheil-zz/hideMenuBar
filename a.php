<?php

$plists = explode("\n", `ls /Applications/ | awk '{ print "/Applications/"$0"/Contents/Info.plist" }'`);
print_r($plists);exit;
foreach ($plists as $plist) {
 if (!$plist || strpos($plist, '.DS_Store') !== FALSE || strpos($plist, '.localized') !== FALSE || strpos($plist, 'Utilities') !== FALSE) continue;
 $c = file_get_contents($plist);
 if (strpos($c, 'LSUIPresentationMode') !== FALSE) continue;
 $c = preg_replace('/(plist.*?\n<dict>)/', "$0\n\t<key>LSUIPresentationMode</key>\n\t<integer>4</integer>", $c);
 file_put_contents($plist, $c);
}

?>
