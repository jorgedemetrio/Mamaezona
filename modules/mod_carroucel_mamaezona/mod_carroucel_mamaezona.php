<?php
// No direct access
defined('_JEXEC') || die;

require_once dirname(__FILE__) . '/helper.php';


if(!defined('VERSAO_MAMAEZONA')){
    JHtml::_('bootstrap.framework');
    JHtml::_('jquery.framework', false);
    JHtml::_('jquery.ui');
    //unset (JFactory::getDocument()->_scripts['/jomaatcms/media/system/js/mootools-core.js']);
    //unset (JFactory::getDocument()->_scripts['/jomaatcms/media/system/js/mootools-more.js']);
}




$qtda = $params->get('qtda', 'qtda');





$titulo1=$params->get('titulo1', 'titulo1');
$descricao1=$params->get('descricao1', 'descricao1');
$image1=$params->get('image1', 'image1');
$url1=$params->get('url1', 'url1');

$titulo2=$params->get('titulo2', 'titulo2');
$descricao2=$params->get('descricao2', 'descricao2');
$image2=$params->get('image2', 'image2');
$url2=$params->get('url2', 'url2');

$titulo3=$params->get('titulo3', 'titulo3');
$descricao3=$params->get('descricao3', 'descricao3');
$image3=$params->get('image3', 'image3');
$url3=$params->get('url3', 'url3');

$titulo4=$params->get('titulo4', 'titulo4');
$descricao4=$params->get('descricao4', 'descricao4');
$image4=$params->get('image4', 'image4');
$url4=$params->get('url4', 'url4');



require JModuleHelper::getLayoutPath('mod_carroucel_mamaezona');
