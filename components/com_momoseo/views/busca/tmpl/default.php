<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );

$url = JRoute::_('index.php?option=com_momoseo&task=buscar&Itemid='.JRequest::getVar ( 'Itemid' ), false);
?>
<form action="<?php echo $url;?>" id="cse-search-box">
  <div>
    <input type="hidden" name="lang" value="<?php echo JRequest::get("lang");?>" />
    <input type="hidden" name="cx" value="<?php echo JRequest::get("cx");?>"/>
    <input type="hidden" name="cof" value="<?php echo JRequest::get("cof");?>"/>
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="20"  value="<?php echo $_GET["q"];?>"/>
    <input class="button" name="sa" <?php 
		if($lblImagem!=null && $lblImagem!=''){
			echo ' src="' . $lblImagem . '" type="image" ';
		}
		else{ 
			echo ' type="submit" ';
		}?>value="<?php echo $_GET["sa"];?>" />
  </div>
</form>
<script type="text/javascript" src="//www.google.com.br/coop/cse/brand?form=cse-search-box&amp;lang=<?php echo JRequest::get("lang");?>"></script>
    

<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 800;
  var googleSearchDomain = "www.google.com.br";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="//www.google.com/afsonline/show_afs_search.js"></script>
