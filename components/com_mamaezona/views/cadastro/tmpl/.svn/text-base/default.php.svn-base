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

$this->item = JRequest::getVar('usuario');

$ufs = JRequest::getVar('ufs');

$documento = JFactory::getDocument();

$documento->addStyleSheet(JURI::base(true) . '/components/com_mamaezona/assets/css/form.css');
$documento->addScript(JURI::base(true) . '/components/com_mamaezona/assets/js/cadastro.js');
$documento->addScript(JURI::base(true) . '/index.php?option=com_mamaezona&task=interessesJSON&tk=' . JFactory::getUser()->id);

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

$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$baseURL = $protocol . $_SERVER['SERVER_NAME'];
$urlLocal = $baseURL . $_SERVER['REQUEST_URI'];

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

<form action="index.php" method="post" name="cadastroForm" id="cadastroForm"
	 class="form-validate" role="form" data-toggle="validator" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_mamaezona" />
    <input type="hidden" name="task" value="salvarUsuario" />
	<input type="hidden" name="youtube" id="youtube" value="" />
	<?php

echo JHtml::_('form.token');
?>
	<input type="hidden" name="access_token" id="access_token"  value="<?php

echo JRequest::getVar('access_token');
?>" />
	<input type="hidden" name="expira" id="expira" value="<?php

echo JRequest::getVar('expira');
?>" />
	<input type="hidden" name="issued" id="issued" value="<?php

echo JRequest::getVar('issued');
?>" />
	<input type="hidden" name="login_hint" id="login_hint" value="<?php

echo JRequest::getVar('login_hint');
?>" />
	<input type="hidden" name="data" id="data" value="<?php

echo JRequest::getVar('data');
?>" />
	<input type="hidden" name="pais_canal" id="pais_canal" value="<?php

echo JRequest::getVar('pais_canal');
?>" />
	<input type="hidden" name="customUrl" id="customUrl" value="<?php

echo JRequest::getVar('customUrl');
?>" />
	<input type="hidden" name="descricao_canal" id="descricao_canal" value="<?php

echo JRequest::getVar('descricao_canal');
?>" />
	<input type="hidden" name="publicado_canal" id="publicado_canal" value="<?php

echo JRequest::getVar('publicado_canal');
?>" />
	<input type="hidden" name="thumb_default_canal" id="thumb_default_canal" value="<?php

echo JRequest::getVar('thumb_default_canal');
?>" />
	<input type="hidden" name="thumb_high_canal" id="thumb_high_canal" value="<?php

echo JRequest::getVar('thumb_high_canal');
?>" />
	<input type="hidden" name="thumb_medium_canal" id="thumb_medium_canal" value="<?php

echo JRequest::getVar('thumb_medium_canal');
?>" />
	<input type="hidden" name="titulo" id="titulo" value="<?php

echo JRequest::getVar('titulo');
?>" />
	<input type="hidden" name="pl_favorites_canal" id="pl_favorites_canal" value="<?php

echo JRequest::getVar('pl_favorites_canal');
?>" />
	<input type="hidden" name="pl_likes_canal" id="pl_likes_canal" value="<?php

echo JRequest::getVar('pl_likes_canal');
?>" />
	<input type="hidden" name="pl_uploads_canal" id="pl_uploads_canal" value="<?php

echo JRequest::getVar('pl_uploads_canal');
?>" />


	<h1><?php

echo JText::_('Cadastro de novos usu&aacute;rios');
?></h1>

	<div class="btn-group pull-right" role="group">
		<div class="btn-group" role="group">
			<a  class="btn btn-default ajuda"  href="/pt/manual-ajuda-como-usar.html" target="_black">
				Dicas e Sujest&otilde;es <span class="glyphicon glyphicon-question-sign"></span>
			</a>
			<a  class="btn btn-default modal" href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component" rel="{handler: 'iframe', size: {x:800, y:500}}" target="_black">
				Termos e Condi&ccedil;&otilde;es <span class="glyphicon glyphicon-paperclip"></span>
			</a>
		</div>

		<div class="btn-group" role="group">
			<button  class="btn btn-danger" type="button" onclick="JavaScript:window.history.back(-1);"><?php

echo JText::_('Cancelar');
?>
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
			<button  class="btn btn-success cadastro1" type="button" id="cadastro"><?php

echo JText::_('Continuar o cadastro');
?>
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			</button>
		</div>
	</div>




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
			<h2><?php

echo JText::_('Youtuber');
?></h2>
			<div id="dynamic_recaptcha_1"></div>
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label class="control-label"  for="termos"><?php

    echo JText::_('Declaro que li e concordo com todos os termos e condi&ccedil;&otilde;es para realizar o cadastro.');
    ?><a href="/pt/manual-ajuda-como-usar/54-termos-de-uso-para-mamaezona.html?tmpl=component"><small>Clique aqui para ler os termos e condi&ccedil;&otilde;es.</small></a></label>
				<input type="checkbox" value="S" name="termos" id="termos" class="form-control required"/>
			</div>
			<div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label" ><?php

    echo JText::_('Primiero nome');
    ?></label>

<?php
JPluginHelper::importPlugin('captcha');
$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onInit', 'dynamic_recaptcha_1');

echo $dispatcher->trigger('onDisplay', array(
    null,
    'dynamic_recaptcha_1',
    'class="some_class"'
))[0];

?>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="name"><?php

echo JText::_('Primiero nome');
    ?></label>
					<input class="form-control required" style="width: 90%;" type="text" name="name"  id="name" size="32" maxlength="200" value="<?php

echo JRequest::getVar('name');
    ?>"
						placeholder="<?php

echo JText::_('Nome');
    ?>" title="Nome" min="2"/>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="lname"><?php

echo JText::_('Sobrenome');
    ?></label>
					<input class="form-control required" style="width: 90%;" type="text" name="lname"  id="lname" size="32" maxlength="200" value="<?php

echo JRequest::getVar('lname');
    ?>" placeholder="<?php
    echo JText::_('Sobrenome');
    ?>" title="Sobrenome" min="3"/>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="password"><?php

echo JText::_('Senha');
    ?></label>
					<input class="form-control required validate-password" style="width: 90%;" type="password" name="password"  id="password" size="32" maxlength="25"
						placeholder="<?php

echo JText::_('Senha');
    ?>" title="Senha"  minlength="8" min="8"/>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="password1"><?php

echo JText::_('Confirmar Senha');
    ?></label>
					<input class="form-control required validate-password validate-passverify" style="width: 90%;" type="password" name="password1"  id="password1" size="32" maxlength="25"
						placeholder="<?php

echo JText::_('Confirma&ccedil;&atilde;o de Senha');
    ?>" title="Confirmar senha" minlength="8" min="8"/>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="email"> <?php

echo JText::_('E-mail Principal');
    ?></label>
					<input class="form-control required validate-email" style="width: 90%;" type="email" name="email"  id="email" size="32" maxlength="100" value="<?php

echo JRequest::getVar('email');
    ?>"
						placeholder="<?php

echo JText::_('email@mail.com');
    ?>" title="E-mail"/>
				</div>
				<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<label class="control-label"  for="email1"> <?php

echo JText::_('Confirmacao e-mail');
    ?></label>
					<input class="form-control required validate-email  validate-emailverify" style="width: 90%;" type="email" name="email1"  id="email1" size="32" maxlength="100"  value="<?php

echo JRequest::getVar('email1');
    ?>"
						placeholder="<?php

echo JText::_('email@mail.com');
    ?>" title="Confirmação e-mail"/>
				</div>
				<div>
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h2><?php

    echo JText::_('Tipos de conteúdo que gosta?');
    ?></h2>
						<?php

    echo JText::_('Selecione 3 assuntos de interesse para que a mamãezona possa sugerir alguns cotneúdos para você.');
    ?>
					</div>
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="table-responsive">
						  <table class="table" id="tblInteresse">
							<thead>
								<tr>
									<th><?php

        echo JText::_('Interesse');
        ?></th>
									<th><?php

        echo JText::_('Assunto');
        ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select name="interesse[]" id="interesse1" class="form-control required tipos"
											tabela="tblInteresse" assunto="assunto1"<?php
        if (isset($interesses) && sizeof($interesses) >= 3 && isset($interesses[0])) {
            echo ' valor="' . $interesses[0] . '"';
        }
        ?>>
										</select>
									</td>
									<td>
										<select name="assunto[]" id="assunto1" class="form-control required assuntos"
											tabela="tblInteresse" tipo="interesse1"<?php
        if (isset($assuntos) && sizeof($assuntos) >= 3 && isset($assuntos[0])) {
            echo ' valor="' . $assuntos[0] . '"';
        }
        ?>>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="interesse[]" id="interesse2" class="form-control required tipos"
											tabela="tblInteresse" assunto="assunto2"<?php
        if (isset($interesses) && sizeof($interesses) >= 3 && isset($interesses[1])) {
            echo ' valor="' . $interesses[1] . '"';
        }
        ?>>
										</select>
									</td>
									<td>
										<select name="assunto[]" id="assunto2" class="form-control required assuntos"
											tabela="tblInteresse" tipo="interesse2"<?php
        if (isset($assuntos) && sizeof($assuntos) >= 3 && isset($assuntos[1])) {
            echo ' valor="' . $assuntos[1] . '"';
        }
        ?>>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="interesse[]" id="interesse3" class="form-control required tipos"
											tabela="tblInteresse" assunto="assunto3"<?php
        if (isset($interesses) && sizeof($interesses) >= 3 && isset($interesses[2])) {
            echo ' valor="' . $interesses[2] . '"';
        }
        ?>>
										</select>
									</td>
									<td>
										<select name="assunto[]" id="assunto3" class="form-control required assuntos"
											tabela="tblInteresse" tipo="interesse3"<?php
        if (isset($assuntos) && sizeof($assuntos) >= 3 && isset($assuntos[2])) {
            echo ' valor="' . $assuntos[2] . '"';
        }
        ?>>
										</select>
									</td>
								</tr>
							</tbody>
						  </table>
						</div>
					</div>
				</div>
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

		<div class="btn-group" role="group">
			<button  class="btn btn-danger" type="button" onclick="JavaScript:window.history.back(-1);"><?php

echo JText::_('Cancelar');
?>
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</button>
			<button  class="btn btn-success cadastro1" type="button"><?php

echo JText::_('Continuar o cadastro');
?>
				<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</form>













