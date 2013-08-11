Dieses Addon ermöglicht passwortgeschützte Medienpool-Dateien.<br />
Dafür wird in den Metainfos das Feld "med_password" angelegt.<br />
Außerdem muss in der Konfiguration des Addons die Artikel-ID des Artikels, in dem das Downloadformular eingebunden ist, eingetragen werden.<br />
Dateien, die passwortgeschützt sind, können ganz normal verlinkt werden. Beim Zugriff auf die Datei wird jedoch auf den konfigurierten Artikel weitergeleitet.<br />
Das Addon übergibt an diesen Artikel über den URL-Parameter <em>xmediapool_password_filename</em> den Dateinamen der passwortgeschützten Datei. Anhand diesem kann ein Formular eingebunden werden, welches das Passwort für die Datei überprüft und die Datei zum Download anbietet.<br />
<br />
Ein Beispielmodul findet man in der Datei "module.php" im Verzeichnis des Addons.<br />