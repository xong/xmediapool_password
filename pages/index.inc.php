<?php

$page = 'xmediapool_password';

include $REX["INCLUDE_PATH"]."/layout/top.php";

rex_title($REX['ADDON']['name'][$page]);

$config_file = $REX['INCLUDE_PATH'].'/addons/'.$page.'/config.inc.php';
$download_form_article_id = rex_request('download_form_article_id', 'int');

if(rex_request('func') == 'update')
{
  $REX['ADDON']['DOWNLOAD_FORM_ARTICLE_ID'][$myself] = $download_form_article_id;

  $content = '
$REX[\'ADDON\'][\'DOWNLOAD_FORM_ARTICLE_ID\'][\''.$myself.'\'] = '.$download_form_article_id.';
';

  if(rex_replace_dynamic_contents($config_file, $content) !== false)
    echo rex_info($I18N->msg($page.'_config_saved'));
  else
    echo rex_warning($I18N->msg($page.'_config_not_saved'));
}

if(!is_writable($config_file))
  echo rex_warning($I18N->msg($page.'_config_not_writable', $config_file));

$linkbutton = new rex_input_linkbutton();
$linkbutton->setAttribute('name', 'download_form_article_id');
$linkbutton->setButtonId('download_form_article_id');
$linkbutton->setCategoryId(0);
$linkbutton->setValue($REX['ADDON']['DOWNLOAD_FORM_ARTICLE_ID'][$myself]);

echo '

<div class="rex-addon-output">



  <div class="rex-form">

<form action="index.php" method="post">

    <fieldset class="rex-form-col-1">
      <div class="rex-form-wrapper">
      <input type="hidden" name="page" value="'.$page.'" />
      <input type="hidden" name="func" value="update" />

      <div class="rex-form-row rex-form-element-v2">
        <div class="rex-form-col rex-form-widget">
          <label for="download_form_article_id">'. $I18N->msg($page.'_download_form_article_id') .'</label>
          '.$linkbutton->getHTML().'
        </div>
      </div>

      <div class="rex-form-row rex-form-element-v2">
        <p class="rex-form-submit">
          <input type="submit" class="rex-form-submit" name="sendit" value="'.$I18N->msg('update').'" />
        </p>
      </div>
    </div>
      </fieldset>
  </form>
  </div>

</div>
<a href="index.php?page=addon&subpage=help&addonname=xmediapool_password">Kurze Erklärung des Addons</a>
  ';

include $REX["INCLUDE_PATH"]."/layout/bottom.php";
