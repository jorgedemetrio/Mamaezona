<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') || die('Restricted access');

JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');

$editor = JFactory::getEditor();
$params = array(
    'smilies' => '0',
    'html' => '1',
    'style' => '1',
    'layer' => '0',
    'table' => '1',
    'clear_entities' => '0'
);

$documento = JFactory::getDocument();

$documento->addStyleSheet(JURI::base(true) . '/components/com_mamaezona/assets/css/form.css');
$documento->addScript(JURI::base(true) . '/components/com_mamaezona/assets/js/homepage_logado.js');
//$documento->addScript(JURI::base(true) . '/index.php?option=com_mamaezona&task=interessesJSON&tk=' . JFactory::getUser()->id);


$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$baseURL = $protocol . $_SERVER['SERVER_NAME'];
$urlLocal = $baseURL . $_SERVER['REQUEST_URI'];

$textoTwitter = ' [ Mamãzona para Youtubers ] em ' . $urlLocal;



$videos = JRequest::getVar("videos");



echo '<div id="fb-root"></div><div class="fb-like"
    data-href="' . $urlLocal . '"
    data-layout="standard"
    data-action="like"
    data-show-faces="true">
  </div><a class="twitter-share-button" target="_new"
  href="https://twitter.com/intent/tweet?text=' . $textoTwitter . '"
  data-size="large">
	Tweet</a><g:plusone></g:plusone><div class="g-plus" data-action="share"></div>';

$urlSalvar = JRoute::_('index.php?option=com_mamaezona&task=salvarUsuario&Itemid=' . JRequest::getVar('Itemid'), false);


$interesses = JRequest::getVar('interesse', array(), 'POST', 'array');
$assuntos = JRequest::getVar('assunto', array(), 'POST', 'array');
?>
<h1><?php echo JText::_('&Aacute;rea do gerador de conte&uacute;do'); ?></h1>















