<?php

/*------------------------------------------------------------------------
# router.php - Mamaezona
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 3 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') || die('Restricted access');
function MamaezonaBuildRoute(&$query)
{
	$segments = array();
	foreach ($query as $key => $value){
		if($key =='id'){
			array_push($segments, 'video-'.$query['id']);
		}
		elseif($key =='task'){
			array_push($segments, 'youtube-'.$query['task']);
		}
		else{
			array_push($segments, $key.'-'.$value);
		}
	}
	return $segments;
}

function MamaezonaParseRoute($segments)
{
	$vars = array();
	foreach($segments as $segment){
		$val =  explode(':', $segment);
		if(!(strpos($segment,'youtube')===false)){
			$vars['task'] = $val[1];
		}
		elseif(!(strpos($segment,'video')===false)){
			$vars['id'] = (int) $val[0];
			JRequest::setVar('id',$vars['id']);
			if(isset($val[2]) && $val[2]!=''){
				JRequest::setVar('descricao',str_replace('-',' ',$val[2]));
			}
		}
		else{
			if(isset($val[0]) && $val[0]!=''){
				$vars[$val[0]] = $val[1];
			}
		}

	}
	return $vars;
}
