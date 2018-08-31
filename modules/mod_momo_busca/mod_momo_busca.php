<?php
// No direct access
defined('_JEXEC') || die;

require_once dirname(__FILE__) . '/helper.php';

$cx = $params->get('cx', 'partner-pub-2442632731348664:3010243382');
$lblBotaoBusca = $params->get('lblBusca', 'Busca');
$lblImagem = $params->get('lblImagem');
$lblClass = $params->get('lblClass', '');
$cof = $params->get('cof', 'FORID:10');
$lang = $params->get('lang', 'en');

require JModuleHelper::getLayoutPath('mod_momo_busca');