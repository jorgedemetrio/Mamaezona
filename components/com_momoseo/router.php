<?php

/*------------------------------------------------------------------------
# router.php - MomoSEO
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 3 || later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');



function MomoseoBuildRoute(&$query)
{


	$segments = array();

	if(isset($query['task'])){
		array_push($segments, 'mm-'.$query['task']);
		unset($query['task']);
	}

	if(isset($query['view'])){
		array_push($segments,  $query['view']);
		unset($query['view']);
	}


	return $segments;
}

function MomoseoParseRoute($segments)
{
	$vars = array();
	if(!(strpos($segments[0],'mm')===false)){
		$vars['task'] = substr($segments[0],3);
		$valor = array();
		$countador = 0;
		foreach($segments as $val){
			if($countador>0){
				$valor[] = $val;
			}
			++$countador;
		}
		$segments = $valor;
	}
	if(sizeof($segments)){
		$vars['view'] = $segments[0];
	}
	return $vars;
}
