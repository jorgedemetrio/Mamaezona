<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) || die ( 'Restricted access' );
JHTML::_('behavior.calendar');
JHtml::_('dropdown.init');


if (JRequest::getVar ( 'task' ) == null || JRequest::getVar ( 'task' ) == '') {
	$mainframes = JFactory::getApplication ();
	$mainframes->redirect ( JRoute::_ ( 'index.php?option=com_mamaezona&task=extratoFeijoes&Itemid='.JRequest::getVar ( 'Itemid' ), false ), "" );
	exit ();
}

$usuario = JRequest::getVar('usuario');
$extrato = JRequest::getVar('extrato');
$pageNav = JRequest::getVar('pageNav',null);

?>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo JText::_('Total de feij&otilde;es:');?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo $usuario->saldo_feijoes;?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo JText::_('Total de Tutus:');?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo $usuario->saldo_tutu;?></div>
</div>
<form action="index.php" method="post" name="cadastroForm" id="cadastroForm"
	 class="form-validate" role="form" data-toggle="validator" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_mamaezona" />
    <input type="hidden" name="task" value="extratoFeijoes" />
    <input type="hidden" name="p" value="0" />
     
	
    <div class="btn-group pull-right" role="group">
    	<div class="btn-group" role="group">
    		<button  class="btn btn-cancel" type="submit" id="voltar"><?php echo JText::_('Voltar para extrato'); ?>
    			<span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
    		</button>
    	</div>
    </div>
</form>

<div class="table-responsive">
    <table summary="Extrato" class="table table-hover  table-striped">
    	<thead>
    		<tr>
    			<th><?php echo 'Desc.';?></th>
    			<th><?php echo 'Data';?></th>
    			<th><?php echo 'Qtda.';?></th>
    			<th><?php echo 'Detalhes';?></th>
    		</tr>
    	</thead>
    	<tbody>
<?php 
    $qtdaTotal = 0;
    $valTotal = 0;
    $totalRegistros = 0;
    if(isset($extrato)){
        foreach($extrato as $reg){
            $url = 'https://www.youtube.com/watch?v='.$reg->token;
            $title = JText::printf('Clique aqui para ver detalhes sobre a trasação "%s"',$reg->nome);
            $qtdaTotal += $reg->QUANTIDADE;
            $hasVideo = isset($reg->titulo) && !is_null(titulo);
            $totalRegistros++;
    ?>	
    		<tr>
    		<?php if($hasVideo): ?>
    		    <td><a href="<?php echo $url;?>" title="<?php echo $title;?>" target="_new"><?php echo $title;?></a></td>	
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>" target="_new"><?php echo $reg->DATA_GERADO;?></a></td>
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>" target="_new"><?php echo $reg->QUANTIDADE;?></a></td>
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>" target="_new">
    				<img src="<?php echo $reg->thumb;?>" style="width:100px" title="<?php echo $title;?>"  alt="<?php echo $title;?>" />
    			</a></td>
    		<?php else: ?>
    			<td></td>
    		<?php endif; ?>
    		</tr>
    <?php 
        }
    }
    
    $paginacao = new JPagination( $totalRegistros , $totalRegistros, $registrosPorPagina );
    
    // Mostra o conte�do HTML
    echo $paginacao->getListFooter();
    ?>
    	</tbody>
    		<tr>
    			<th class="text-align: left"><?php echo isset($pageNav)? $pageNav->getListFooter():'';?></th>
    			<th class="text-align: right"><?php echo 'Total p&aacute;gina';?></th>
    			<th><?php echo $qtdaTotal;?></th>
    		</tr>
    	<tfoot>
     </table>
</div>