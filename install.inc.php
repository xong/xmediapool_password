<?php
$myself = 'xmediapool_password';

// add metainfo field
if(OOAddon::isActivated('metainfo'))
{
  $sql = rex_sql::factory();
  $fieldInfos = $sql->setQuery('SELECT id FROM '. $REX['TABLE_PREFIX']. '62_params WHERE name = med_'.$myself.'_password LIMIT 2');

  if($sql->getRows() != 1)
    a62_add_field('Passwort', 'med_'.$myself.'_password', 3, '', REX_A62_FIELD_TEXT, '');

  $REX['ADDON']['install'][$myself] = 1;
}
else
{
  $REX['ADDON']['installmsg'][$myself] = $I18N->msg('xmediapool_password_install_error_metainfo_not_activated');
  $REX['ADDON']['install'][$myself] = 0;
}
