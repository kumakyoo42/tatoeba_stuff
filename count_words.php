<?php

if (count($argv)!=3 && count($argv)!=4)
  die('Usage: php count_words.php <sentences.csv> <lang> [<max words>]');

$filename = $argv[1];
$lang = $argv[2];
$max = intval($argv[3]??PHP_INT_MAX);

$symbols = array('.',',',':','!','?',';','-','"','[',']','@','_','$','+','%','»','«',
                 '(',')','/','0','1','2','3','4','5','6','7','8','9','—','=','–');

$tab = array();
$c=0;
$res = fopen($filename,'r');
while (true)
{
    $c++;
    if ($c%100000==0)
      fwrite(STDERR, $c."  \r");
    $line = fgets($res);
    if ($line===false) break;
    list($nr,$l,$satz) = explode("\t",trim($line),3);
    if ($l==$lang)
      countWords($satz);
}

arsort($tab);

echo '<!DOCTYPE html>'.PHP_EOL;
echo '<ol>'.PHP_EOL;
$c = 0;
foreach ($tab as $word=>$cnt)
{
    $c++;
    if ($c>$max) break;
    echo '<li><a href="https://tatoeba.org/en/sentences/search?from='.$lang.'&query='.htmlspecialchars($word).'">'.htmlspecialchars($word).'</a> ('.$cnt.')'.PHP_EOL;
}
echo '</ol>'.PHP_EOL;

function countWords($s)
{
    global $tab, $symbols;

    $s = mb_strtolower(str_replace($symbols,' ',$s));
    $h = explode(' ',$s);
    foreach ($h as $w)
      if (strlen($w)>0)
        @$tab[$w]++;
}

?>
