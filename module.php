<?php
do {

$showform = true;
$filename = rex_request('xmediapool_password_filename');
$filepath = rtrim($REX['MEDIAFOLDER'], '/\\').'/'.$filename;

// Wenn die Datei nicht existiert oder keine Dateiname übergeben wurde, Abbruch
if(empty($filename) OR !file_exists($filepath))
{
  echo rex_warning('Die angegebene Datei wurde nicht gefunden.');
  $showform = false;
  break;
}

$m = OOMedia::getMediaByFilename($filename);
if(empty($m) OR !is_object($m))
{
  echo rex_warning('Die angegebene Datei wurde nicht gefunden.');
  $showform = false;
  break;
}

echo '
<form id="xmediapool-download-form" action="redaxo://REX_ARTICLE_ID" method="post">
  <fieldset>
    <input type="hidden" name="xmediapool_password_filename" value="'.htmlspecialchars($filename).'" />
    
    <legend>Geschützte Datei herunterladen</legend>';

// Falls das Formular abgeschickt wurde
if($_SERVER['REQUEST_METHOD'] == 'POST' OR rex_get('download', 'bool'))
{
  // Passwort überprüfen
  if(rex_request('xmediapool_password') != $m->getValue('med_password'))
  {
    echo rex_warning('Das Passwort ist falsch.');
    $showform = true;
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

} while(false);

if($showform)
{
?>
    <p><label for="xmediapool_password">Passwort:</label> <input type="password" id="xmediapool_password" name="xmediapool_password" /></p>
    <input type="submit" value="Datei herunterladen" />
  </fieldset>
</form><?php
}
?>