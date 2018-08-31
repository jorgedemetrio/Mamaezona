<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );

if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '' || JRequest::getVar ( 'v' ) == null || JRequest::getVar ( 'v' ) == '') {
    $mainframes = JFactory::getApplication ();
    $mainframes->redirect ( JRoute::_ ( 'index.php?option=com_mamaezona&task=home&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
    exit ();
}



JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');

$this->item = JRequest::getVar('conteudo');

$documento = JFactory::getDocument();



$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
$baseURL = $protocol.$_SERVER['SERVER_NAME'];
$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];


$textoTwitter = ' Cadastro de youtubers de seu canal [ Mamãzona para Youtubers ] em ' . $urlLocal;

$document->addScriptDeclaration("$(document).ready(function(){
	MAMAEZONA.carregarTipo($('#interesse1'),false);
	MAMAEZONA.carregarTipo($('#interesse2'),false);
	MAMAEZONA.carregarTipo($('#interesse3'),false);

	$('.btn-success').click(function(){
		enviarCadastro1();
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
?>






<h1><?php echo $this->item->titulo;?></h1>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 centered center-block">
    	<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $this->item->token_provedor;?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
    	</iframe>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
		<strong><?php echo JText::_('Link');?></strong> <a href="https://youtu.be/<?php echo $this->item->token_provedor;?>" title="<?php echo $this->item->titulo;?>">https://youtu.be/<?php echo $this->item->token_provedor;?></a>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		<strong><?php echo JText::_('Visualiza&ccedil;&otilde;es');?></strong> <?php echo $this->item->estat_view;?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		<strong><?php echo JText::_('Curtidas');?></strong> <?php echo $this->item->estat_lks;?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		<strong><?php echo JText::_('DesCurtidas');?></strong> <?php echo $this->item->estat_desliks;?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
		<strong><?php echo JText::_('Coment&aacute;rios');?>:</strong> <?php echo $this->item->estat_comments;?>
	</div>		
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		 <?php echo $this->item->descricao;?>
	</div>		

	
</div>





