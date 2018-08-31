<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$mainframes = JFactory::getApplication ();
	$mainframes->redirect ( JRoute::_ ( 'index.php?option=com_mamaezona&task=carregarVideos&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
	exit ();
}


JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');


$editor = JFactory::getEditor();
$params = array('smilies'=> '0', 'html' => '1', 'style'  => '1', 'layer'  => '0', 'table'  => '1', 'clear_entities'=>'0');

$this->item = JRequest::getVar('usuario');

$ufs = JRequest::getVar('ufs');

$imagemRosto =  JURI::base( true ) . '/components/com_mamaezona/no_image.png';
$imagemPerfil = JURI::base( true ) . '/components/com_mamaezona/perfil.png';

JFactory::getDocument()->addStyleSheet(JURI::base( true ).'/components/com_mamaezona/assets/css/form.css');
$documento->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/videos.js');



JFactory::getDocument()->addStyleDeclaration('
.validate-numeric{
	text-align: right;
}
.validate-inteiro{
	text-align: right;
}
input[type=\'file\']{
	opacity: 0;
	-moz-opacity: 0;
	filter: alpha(opacity = 0);
	position: absolute;
	z-index: -1;
}');

$document = JFactory::getDocument();

$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$baseURL = $protocol.$_SERVER['SERVER_NAME'];
$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];
$script ='<div id="fb-root"></div>';

$textoTwitter = ' Cadastro de youtubers de seu canal [ Mamãzona para Youtubers ] em ' . $urlLocal;

$document->addScriptDeclaration("$(document).ready(function(){
	$('.btn-success').click(function(){
		enviarCadastro1();
	});
});");

echo $script.'<div class="fb-like"
    data-href="'.$urlLocal.'"
    data-layout="standard"
    data-action="like"
    data-show-faces="true">
  </div><a class="twitter-share-button" target="_new"
  href="https://twitter.com/intent/tweet?text='.$textoTwitter.'"
  data-size="large">
	Tweet</a><g:plusone></g:plusone><div class="g-plus" data-action="share"></div>';


$urlSalvar = JRoute::_ ( 'index.php?option=com_mamaezona&task=salvarUsuario&Itemid='.JRequest::getVar ( 'Itemid' ), false );
?>

<form action="index.php" method="post" name="cadastroForm" id="cadastroForm"
	 class="form-validate" role="form" data-toggle="validator" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_mamaezona" />
    <input type="hidden" name="task" value="gravarVideos" />
	<?php echo JHtml::_('form.token'); ?>



<?php 
//echo recaptcha_get_html(MamaezonaController::PUBLICKEY_RECAPTCHA);

JPluginHelper::importPlugin('captcha', $reCaptchaName); // will load the plugin selected, not all of them - we need to know what plugin's events we need to trigger
// $dispatcher = JDispatcher::getInstance(); // Joomla 2.5
$dispatcher = JEventDispatcher::getInstance(); // Joomla 3
$dispatcher->trigger('onInit', 'dynamic_recaptcha_1');
//echo $dispatcher->trigger('onDisplay', array(MamaezonaController::PRIVATEKEY_RECAPTCHAR, 'dynamic_recaptcha_1', 'class="some_class"'));

?>
	<h1><?php echo JText::_('Dados B&aacute;sicos'); ?></h1>


	<br/>
	<br/>
    <div class="clr"></div>
	<ul class="nav nav-tabs nav-justified" id="myTabTabs" role="tablist" style="margin-bottom: 0;">
		<li class="active" role="presentation">
			<a href="#general" data-toggle="tab" aria-controls="profile" role="tab">Dados B&aacute;sico
			<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
			</a>
		</li>
	</ul>
	<div class="tab-content" style="overflow: auto;">
		<div id="general" class="tab-pane fade in active">
			<h2><?php echo JText::_('Vídeos'); ?></h2>

			<div class="row">
				<div class="col-xs-4 col-sm-6 col-md-12 col-lg-12 video">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 opcoesVideos" id="opcoesVideos">
							<div id="video" class="videoSelector">
								<img>
								titulo
							</div>
						</div>
					</div>
					<div class="row form-group">
						
					</div>
				</div>

			</div>
		</div>
	</div>
	<script>
		function addVideo(idVideo, titulo, thumbs){
			$('#opcoesVideos')
			$html = $('<div id="'+idVideo+'" class="videoSelector" idVideo="'+idVideo+'"><img src="'+thumbs[0]+'" alt="'+titulo+'" title="'+titulo+'"><br/>'+titulo+'</div>');
			
		}
	</script>
	
	

	<div class="btn-group pull-right" role="group">
		<div class="btn-group" role="group">
			<button  class="btn btn-danger" type="button" onclick="JavaScript:window.history.back(-1);"><?php echo JText::_('Cancelar'); ?>
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
			<button  class="btn btn-success" type="button"><?php echo JText::_('Salvar vídeo'); ?>
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			</button>
		</div>
	</div>
	
	
	<div class="videos row" id="videos">
	</div>
</form>













