<?php
do {

$filename = rex_request('xmediapool_password_filename');
$filepath = rtrim($REX['MEDIAFOLDER'], '/\\').'/'.$filename;

// Wenn die Datei nicht existiert oder keine Dateiname übergeben wurde, Abbruch
if(empty($filename) OR !file_exists($filepath))
{
  echo rex_warning('Die angegebene Datei wurde nicht gefunden.');
  break;
}

$m = OOMedia::getMediaByFilename($filename);
if(empty($m) OR !is_object($m))
{
  echo rex_warning('Die angegebene Datei wurde nicht gefunden.');
  break;
}

$title = $m->getTitle();
if(empty($title))
  $title = $filename;

$description = $m->getDescription();
if(OOAddon::isActivated('textile') AND function_exists('rex_a79_textile'))
  $description = rex_a79_textile($description);
else
  $description = nl2br($description);

if(!empty($description))
  $description = '<div class="description">'.$description.'</div>';

echo '
<form id="xmediapool-download-form" action="redaxo://REX_ARTICLE_ID" method="post">
  <fieldset>
    <input type="hidden" name="xmediapool_password_filename" value="'.htmlspecialchars($filename).'" />
    
    <legend>Download <em>'.$title.'</em></legend>';

// Falls das Formular abgeschickt wurde
if($_SERVER['REQUEST_METHOD'] == 'POST' OR rex_get('download', 'bool'))
{
  // Passwort überprüfen
  if(rex_request('xmediapool_password') != $m->getValue('med_password'))
  {
    echo rex_warning('Das Passwort ist falsch.');
    break;
  }
  
  if(rex_get('download', 'bool'))
  {
    // Outputbuffer leeren
    while(ob_end_clean());
    
    // Passenden Datentyp erzeugen.
    header('Content-Type: application/octet-stream');
     
    // Datei senden
    header('Content-Disposition: attachment; filename="'.$filename.'"');
     
    // Datei ausgeben.
    readfile($filepath);
    exit;
  }
  else
  {
    echo '<p>
  <strong>Glückwunsch! </strong></p>
  <p>Sie haben sich erfolgreich eingeloggt. Die angeforderte Datei wurde heruntergeladen.</p>
<script type="text/javascript">
(function($)
{
  $(document).ready(function()
  {
    document.location.href=("'.rex_geturl('', '', array('xmediapool_password_filename' => $filename, 'xmediapool_password' => rex_request('xmediapool_password'), 'download' => 1), '&').'");
  });
}(jQuery));
</script>';
  }
}
else
{
  echo $description;
}
?>
    <p><label for="xmediapool_password">Passwort:</label> <input type="password" id="xmediapool_password" name="xmediapool_password" /></p>
    <input type="submit" value="Datei herunterladen" />
  </fieldset>
</form><?php
} while(false);
}
?>