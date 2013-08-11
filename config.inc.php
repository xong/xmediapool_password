<?php

$myself = 'xmediapool_password';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself;

$REX['ADDON']['rxid'][$myself]        = '000';
$REX['ADDON']['name'][$myself]        = 'Secure Mediapool';
$REX['ADDON']['version'][$myself]     = '0.1';
$REX['ADDON']['author'][$myself]      = 'Robert Rupf (Xong)';
$REX['ADDON']['supportpage'][$myself] = 'http://www.redaxo.org/de/forum/';
$REX['ADDON']['perm'][$myself]        = $myself.'[]';

// --- DYN
$REX['ADDON']['DOWNLOAD_FORM_ARTICLE_ID']['xmediapool_password'] = 2;
// --- /DYN

function securefile($_params)
{
  global $REX;
  $myself = 'xmediapool_password';
  
  $m = OOMedia::getMediaByFilename($_params['filename']);
  $password = $m->getValue('med_'.$myself.'_password');
  
  // htaccess-Datei auslesen
  $htaccess_path = rtrim($REX['MEDIAFOLDER'], '/\\').'/.htaccess';
  $htaccess = '';
  if(file_exists($htaccess_path))
    $htaccess = file_get_contents($htaccess_path);
  
  // RewriteBase ermitteln
  $base = trim(str_replace('\\', '/', substr(realpath($REX['MEDIAFOLDER']), strlen(realpath($_SERVER['DOCUMENT_ROOT'])))), '/');
  $frontend = str_replace('//', '/', '/'.trim(str_replace('\\', '/', substr(realpath($REX['FRONTEND_PATH']), strlen(realpath($_SERVER['DOCUMENT_ROOT'])))), '/').'/');
  $lines = array();
  $lines[] = "RewriteEngine On\nRewriteBase /".$base;
  
  // vorhandene Passwort geschützte Dateien auslesen
  $already_secured = false;
  if(preg_match_all('~^RewriteRule \^(.*)\$\s.*$~im', $htaccess, $matches, PREG_SET_ORDER))
  {
    foreach($matches as $match)
    {
      // Wenn bei einer Datei ein Passwort gelöscht wurde, dann diese Datei nicht mehr schützen
      if($match[1] == preg_quote($_params['filename'], '~'))
      {
        if(!strlen($password))
          continue;
        else
          $already_secured = true;
      }
      
      $lines[] = sprintf('RewriteRule ^%s$ http://%%{HTTP_HOST}%s%s [R=302,L]', $match[1], $frontend, ltrim(rex_geturl($REX['ADDON']['DOWNLOAD_FORM_ARTICLE_ID'][$myself], '', array($myself.'_filename' => stripslashes($match[1])), '&'), '/'));
    }
  }
  
  // neue passwortgeschützte Datei hinzufügen
  if(!$already_secured AND strlen($password))
    $lines[] = sprintf('RewriteRule ^%s$ http://%%{HTTP_HOST}/%s%s [R=302,L]', preg_quote($_params['filename'], '~'), $frontend, ltrim(rex_geturl($REX['ADDON']['DOWNLOAD_FORM_ARTICLE_ID'][$myself], '', array($myself.'_filename' => $_params['filename']), '&'), '/'));
  
  // Daten in die htaccess-Datei schreiben
  file_put_contents($htaccess_path, implode("\n", $lines));
}

rex_register_extension(
  'PAGE_CHECKED',
  create_function(
    '',
    '
      rex_register_extension("MEDIA_ADDED", "securefile");
      rex_register_extension("MEDIA_UPDATED", "securefile");
    '
  )
);

//rex_register_extension('MEDIA_DELETED', 'securefile'); // EP leider nicht vorhanden

if($REX['REDAXO'])
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$myself.'/lang/');

?>