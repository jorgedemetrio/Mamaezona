<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );



if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$mainframes = JFactory::getApplication ();
	$mainframes->redirect ( JRoute::_ ( 'index.php?option=com_momoseo&task=websitemap&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
	exit ();
}

?>
<div class="row">
	<div id="conteudo" class="col col-xs-12 col-sm-9 col-md-9 col-lg-10">
		<h3>Coment&aacute;rios</h3>
		<div class="fb-comments" data-href="http://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" data-width="100%" style="margin: 0 auto;"></div>
	</div>
</div>
