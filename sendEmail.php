<?php
$filter = "pdf";
$folder = './';
$proses = new RecursiveDirectoryIterator("$folder");
foreach(new RecursiveIteratorIterator($proses) as $file)
{
  if (!((strpos(strtolower($file), $filter)) === false) || empty($filter))
  {
    $tampil[] = preg_replace("#/#", "/", $file);
  }
}
sort($tampil);
print_r($tampil);
?>