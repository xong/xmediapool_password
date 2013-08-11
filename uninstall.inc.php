<?php
$myself = 'xmediapool_password';

// add metainfo field
if(OOAddon::isActivated('metainfo'))
  a62_delete_field('med_'.$myself.'_password');

$REX['ADDON']['install'][$myself] = 0;
?>