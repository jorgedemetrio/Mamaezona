<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$mainframes = JFactory::getApplication ();
	$mainframes->redirect ( JRoute::_ ( 'index.php?option=com_mamaezona&task=video&v='.JRequest::getVar ( 'v' ).'&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
	exit ();
}

//https://youtuber.mamaezona.com.br/index.php?option=com_mamaezona&task=retornoYoutube
//Este é seu ID do cliente
//734115126533-mgmfbslavau9eta4v3p81rgp0b3aj77j.apps.googleusercontent.com
//Esta é sua chave secreta do cliente
//WwgB6MvmkplfQNaD6t7rcK7L


/*
ID do cliente	
734115126533-7nk16mlpmh1f9odgcebfp3ee0ejs7kek.apps.googleusercontent.com
Chave secreta do cliente	
juwg10nez58i63Wje41NawXy*/



?>
<div class="row">
	<div id="conteudo" class="col col-xs-12 col-sm-9 col-md-9 col-lg-10">
		<h3>Coment&aacute;rios</h3>
		<div class="fb-comments" data-href="http://<?php echo($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); ?>" data-width="100%" style="margin: 0 auto;"></div>
	</div>
</div>
