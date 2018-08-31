<?php
/*------------------------------------------------------------------------
 # Mamaezona.php - Mamaezona Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');




require_once 'Google/Client.php';
//require_once 'Google/Service/YouTube.php';
//require_once 'recaptcha/recaptchalib.php';

//require_once 'controllers/usuarioController.php';


//session_start();



// Added for Joomla 3.0
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

if(!defined('VERSAO_MAMAEZONA')){
	define('VERSAO_MAMAEZONA','2017.20.28');
}

define('IDFACEBOOK', '2736072396620216');




/*
 *
 *
 * ID do cliente	734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com
 * Chave secreta do cliente WwgB6MvmkplfQNaD6t7rcK7L
 *
 *
 * ID do cliente	734115126533-7nk16mlpmh1f9odgcebfp3ee0ejs7kek.apps.googleusercontent.com
 * Chave secreta do cliente	 juwg10nez58i63Wje41NawXy
 *
 *
 *
 */
define('OAUTH2_CLIENT_ID','734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com');
define('OAUTH2_CLIENT_SECRET','WwgB6MvmkplfQNaD6t7rcK7L');
define('SHORTLINK','AIzaSyCWYKcpB6cFdn_AB8TYz_8B_5LLS9wQ3ng');

// Set the component css/js
$document = JFactory::getDocument();


$document->addScriptDeclaration("  window.fbAsyncInit = function() {
    FB.init({
      appId      : '".IDFACEBOOK."',
      xfbml      : true,
      version    : 'v2.11'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = 'https://connect.facebook.net/pt_BR/sdk.js';
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = '//connect.facebook.com/pt_BR/messenger.Extensions.js';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'Messenger'));");


JHtml::_('jquery.framework', false);
JHtml::_('jquery.ui');
//$document->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/jquery-ui.min.js?VERSAO='.VERSAO_MAMAEZONA);
$document->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/jquery.mask.min.js');

$document->addScript('https://apis.google.com/js/client.js?onload=googleApiClientReady');
$document->addCustomTag('<link rel="me" href="https://twitter.com/twitterdev" />');
$document->addCustomTag('<script src="https://apis.google.com/js/platform.js" async defer> {lang: "pt-BR"} </script>');
$document->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/mamaezona.js?VERSAO='.VERSAO_MAMAEZONA);


$document->addStyleSheet(JURI::base( true ).'/components/com_mamaezona/assets/css/bootstrap.min.css');
$document->addStyleSheet(JURI::base( true ).'/components/com_mamaezona/assets/css/bootstrap-theme.min.css');
$document->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/bootstrap.min.js');

// Require helper file
JLoader::register('MamaezonaHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'mamaezona.php');



// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Mamaezona
$controller = JControllerLegacy::getInstance('Mamaezona');

// Perform the request task
$controller->execute(JRequest::getCmd('task'));

echo '
<div id="fb-root"></div>
  <div class="modalwindow fade" id="modalWindow" style="position: absolute;z-index: 9999999999999999999999999999999999999999;margin: auto; top:300px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="height: 35px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalWindowtitle"></h4>
      </div>
      <div class="modal-body alert" id="modalWindowbody" style="padding-left:25px">

      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <a class="btn btn-primary" id="modalWindowok" href="'.JRoute::_('index.php?option=com_users&view=login',false).'"></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade carregando" id="pleaseWaitDialog" class="display:none" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1>Processando...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div></div>';




// Redirect if set by the controller
$controller->redirect();
///templates/protostar/css/template.css

unset($document->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);


