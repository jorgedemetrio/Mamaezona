<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$mainframes = JFactory::getApplication ();
	$mainframes->redirect ( JRoute::_ ( 'index.php?option=com_mamaezona&task=cadastro&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
	exit ();
}


JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');


$editor = JFactory::getEditor();
$params = array('smilies'=> '0', 'html' => '1', 'style'  => '1', 'layer'  => '0', 'table'  => '1', 'clear_entities'=>'0');

$this->item = JRequest::getVar('usuario');

$ufs = JRequest::getVar('ufs');

JFactory::getDocument()->addStyleSheet(JURI::base( true ).'/components/com_mamaezona/assets/css/form.css');
JFactory::getDocument()->addScript(JURI::base( true ).'/components/com_mamaezona/assets/js/cadastro.js');
JFactory::getDocument()->addScript(JURI::base( true ).'/components/com_mamaezona/assets/google/auth.js');
//JFactory::getDocument()->addScript(JURI::base( true ).'/components/com_mamaezona/assets/google/my_uploads.js');
JFactory::getDocument()->addScript('https://apis.google.com/js/client.js?onload=googleApiClientReady');

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


echo $script.'<div class="fb-like"
    data-href="'.$urlLocal.'"
    data-layout="standard"
    data-action="like"
    data-show-faces="true">
  </div><a class="twitter-share-button" target="_new"
  href="https://twitter.com/intent/tweet?text='.$textoTwitter.'"
  data-size="large">
	Tweet</a><g:plusone></g:plusone><div class="g-plus" data-action="share"></div>';


$urlSalvar = JRoute::_ ( 'index.php?option=com_mamaezona&task=salvarUsuarioDetalhes&Itemid='.JRequest::getVar ( 'Itemid' ), false );

$estados = Jrequest::getVar('estados');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm"
	 class="form-validate" role="form" data-toggle="validator" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_mamaezona" />
    <input type="hidden" name="task" value="salvarUsuarioDetalhes" />
	<input type="hidden" name="youtube" value="<?php echo JRequest::getVar('youtube');?>" />
	<input type="hidden" name="access_token" value="<?php echo JRequest::getVar('access_token');?>" />
	<input type="hidden" name="expira" value="<?php echo JRequest::getVar('expira');?>" />
	<input type="hidden" name="issued" value="<?php echo JRequest::getVar('issued');?>" />
	<input type="hidden" name="login_hint" value="<?php echo JRequest::getVar('login_hint');?>" />
	<input type="hidden" name="data" value="<?php echo JRequest::getVar('data');?>" />
	
	<input type="hidden" name="pais_canal" value="<?php echo JRequest::getVar('pais_canal');?>" />
	<input type="hidden" name="customUrl" value="<?php echo JRequest::getVar('customUrl');?>" />
	<input type="hidden" name="descricao_canal" value="<?php echo JRequest::getVar('descricao_canal');?>" />
	<input type="hidden" name="publicado_canal" value="<?php echo JRequest::getVar('publicado_canal');?>" />
	<input type="hidden" name="thumb_default_canal" value="<?php echo JRequest::getVar('thumb_default_canal');?>" />
	<input type="hidden" name="thumb_high_canal" value="<?php echo JRequest::getVar('thumb_high_canal');?>" />
	<input type="hidden" name="thumb_medium_canal" value="<?php echo JRequest::getVar('thumb_medium_canal');?>" />
	<input type="hidden" name="titulo" value="<?php echo JRequest::getVar('titulo');?>" />
	<input type="hidden" name="pl_favorites_canal" value="<?php echo JRequest::getVar('pl_favorites_canal');?>" />
	<input type="hidden" name="pl_likes_canal" value="<?php echo JRequest::getVar('pl_likes_canal');?>" />
	<input type="hidden" name="pl_uploads_canal" value="<?php echo JRequest::getVar('pl_uploads_canal');?>" />
	
	
	<?php echo JHtml::_('form.token'); ?>




	<div class="btn-group pull-right" role="group">
		<div class="btn-group" role="group">
			<a  class="btn btn-default ajuda"  href="<?php echo $ajuda;?>" target="_black">
				Dicas e Sujest&otilde;es <span class="glyphicon glyphicon-question-sign"></span>
			</a>
			<a  class="btn btn-default modal" href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component" rel="{handler: 'iframe', size: {x:800, y:500}}" target="_black">
				Termos e Condi&ccedil;&otilde;es <span class="glyphicon glyphicon-paperclip"></span>
			</a>
		</div>
	
		<div class="btn-group" role="group">
			<button  class="btn btn-danger" type="button" onclick="JavaScript:window.history.back(-1);"><?php echo JText::_('Cancelar'); ?>
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
			<button  class="btn btn-success" type="submit"><?php echo JText::_('Finalizar cadastro'); ?>
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			</button>
		</div>
	</div>
	<h1><?php echo JText::_('Dados B&aacute;sicos'); ?></h1>


	<br/>
	<br/>
    <div class="clr"></div>
	<ul class="nav nav-tabs nav-justified" id="myTabTabs" role="tablist" style="margin-bottom: 0;">
		<li class="active" role="presentation">
			<a href="#general" data-toggle="tab" aria-controls="profile" role="tab">Dados Detalhados
			<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
			</a>
		</li>
	</ul>
	<div class="tab-content" style="overflow: auto;">
		<div id="general" class="tab-pane fade in active">
			<h2><?php echo JText::_('Youtuber'); ?></h2>

			<div>
				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<label class="control-label"  for="apelido"><?php echo JText::_('Apelido'); ?></label>
					<input class="form-control required" style="width: 90%;" type="text" name="apelido"  id="apelido" size="32" maxlength="25" value="<?php echo JRequest::getVar('apelido');?>" 
						placeholder="<?php echo JText::_('Apelido'); ?>" title="Apelido"/>
				</div>
				
				
				
				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<label class="control-label"  for="celular"><?php echo JText::_('Celular'); ?></label>
					<input class="form-control required" style="width: 90%;" type="text" name="ddd_cel"  id="ddd_cel" size="5" maxlength="3" value="<?php echo JRequest::getVar('ddd_cel');?>" 
						placeholder="<?php echo JText::_('011'); ?>"  pattern="[0]{1}[0-9]{2}" title="DDD"/>
					<input class="form-control required" style="width: 90%;" type="text" name="celular"  id="celular" size="32" maxlength="10" value="<?php echo JRequest::getVar('celular');?>" 
						placeholder="<?php echo JText::_('9999-9999'); ?>" pattern="[0-9]{4}-[0-9]{4}" title="Celular"/>
				</div>

				
				
				
				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<label class="control-label"  for="genero"><?php echo JText::_('Genero'); ?></label>
					<select class="form-control required" style="width: 90%;" name="genero"  id="genero" size="32" title="Genero sexual">
						<option></option>
						<option value="F">FEMINO</option>
						<option value="M">MASCULINO</option>
					</select>
				</div>
				
				
				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<label class="control-label"  for="nascimento"><?php echo JText::_('Data nascimento'); ?></label>
					<input class="form-control data-validation required" style="width: 90%;" type="test" name="nascimento"  id="nascimento" size="32" maxlength="11" placeholder="<?php echo JText::_('21/04/2012 Deve ser mairo que 18 anos'); ?>"
					title="nascimento"/>
				</div>
				
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label class="control-label"  for="uf"><?php echo JText::_('UF/Estado'); ?></label>
					<select class="form-control required estado" data-carregar="cidade" style="width: 90%;" name="uf"  id="uf" size="32" title="UF [Estado/Unidade Federativa]">
						<option></option>
						<?php
						if(isset($estados)){
							foreach($estados as $estado){
						?>
						<option value="<?php echo $estado->uf;?>"<?php echo (JRequest::getVar('uf')==$estado->uf?' selected ':'');?>><?php echo $estado->nome;?></option>
<?php
							}
						}
?>
					</select>
					
				</div>		
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label class="control-label"  for="cidade"><?php echo JText::_('Cidade'); ?></label>
					<select class="form-control required" data-value="<?php echo JRequest::getVar('cidade'); ?>" style="width: 90%;" name="cidade"  id="cidade" size="32" title="Cidade">
						<option></option>
					</select>
				</div>	
			</div>
		</div>
	</div>
	
	
		<div class="btn-group" role="group">
			<a  class="btn btn-default ajuda"  href="<?php echo $ajuda ;?>" target="_black">
				Dicas e Sujest&otilde;es <span class="glyphicon glyphicon-question-sign"></span>
			</a>
			<a  class="btn btn-default modal" href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component" rel="{handler: 'iframe', size: {x:800, y:500}}" target="_black">
				Termos e Condi&ccedil;&otilde;es <span class="glyphicon glyphicon-paperclip"></span>
			</a>
		</div>
	
		<div class="btn-group" role="group">
			<button  class="btn btn-danger" type="button" onclick="JavaScript:window.history.back(-1);"><?php echo JText::_('Cancelar'); ?>
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
			<button  class="btn btn-success" type="submit"><?php echo JText::_('Finalizar cadastro'); ?>
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</form>













