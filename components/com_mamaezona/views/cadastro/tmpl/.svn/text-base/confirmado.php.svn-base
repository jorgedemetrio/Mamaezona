<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');


$editor = JFactory::getEditor();
$params = array('smilies'=> '0', 'html' => '1', 'style'  => '1', 'layer'  => '0', 'table'  => '1', 'clear_entities'=>'0');

$this->item = JRequest::getVar('usuario');

$ufs = JRequest::getVar('ufs');

$documento = JFactory::getDocument();

$documento->addStyleSheet(JURI::base( true ).'/components/com_mamaezona/assets/css/form.css');
$documento->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/cadastro.js');
$documento->addScript(JURI::base( true ).'/index.php?option=com_mamaezona&task=interessesJSON&tk='.JFactory::getUser()->id);



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


$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$baseURL = $protocol.$_SERVER['SERVER_NAME'];
$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];


$textoTwitter = ' Cadastro de youtubers de seu canal [ Mamãzona para Youtubers ] em ' . $urlLocal;

$documento->addScriptDeclaration("$(document).ready(function(){
	MAMAEZONA.carregarTipo($('#interesse1'),false);
	MAMAEZONA.carregarTipo($('#interesse2'),false);
	MAMAEZONA.carregarTipo($('#interesse3'),false);

	$('.btn-success').click(function(){
		MAMAEZONA.enviarCadastro1();
	});
});");


echo '<div id="fb-root"></div><div class="fb-like"
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
    <input type="hidden" name="task" value="salvarUsuario" />
	<input type="hidden" name="youtube" id="youtube" value="" />
	<?php echo JHtml::_('form.token'); ?>



	<div class="btn-group pull-right" role="group">
		<div class="btn-group" role="group">
			<a  class="btn btn-default ajuda"  href="/pt/manual-ajuda-como-usar.html" target="_black">
				Dicas e Sujest&otilde;es <span class="glyphicon glyphicon-question-sign"></span>
			</a>
			<a  class="btn btn-default modal" href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component" rel="{handler: 'iframe', size: {x:800, y:500}}" target="_black">
				Termos e Condi&ccedil;&otilde;es <span class="glyphicon glyphicon-paperclip"></span>
			</a>
		</div>

	</div>
	<h1><?php echo JText::_('Cadastro confirmado'); ?></h1>



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
			<div>
				Obrigado por se cadastrar, seu e-mail j&aacute; já foi validado.<br/> 
			</div>
		</div>
	</div>
	
	<div class="btn-group pull-right" role="group">
		<div class="btn-group" role="group">
			<a  class="btn btn-default ajuda"  href="/pt/manual-ajuda-como-usar.html" target="_black">
				Dicas e Sujest&otilde;es <span class="glyphicon glyphicon-question-sign"></span>
			</a>
			<a  class="btn btn-default modal" href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component" rel="{handler: 'iframe', size: {x:800, y:500}}" target="_black">
				Termos e Condi&ccedil;&otilde;es <span class="glyphicon glyphicon-paperclip"></span>
			</a>
		</div>

	</div>
</form>













