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
$extrato = JRequest::get('extrato');
$pageNav = JRequest::get('pageNav',null);


?>
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo JText::_('Total de feij&otilde;es:');?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo $usuario->saldo_feijoes;?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo JText::_('Total de Tutus:');?></div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><?php  echo $usuario->saldo_tutu;?></div>
</div>
<form action="index.php" method="get" name="cadastroForm" id="cadastroForm"
	 class="form-validate" role="form" data-toggle="validator" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_mamaezona" />
    <input type="hidden" name="task" value="extratoFeijoes" />
     
     
	<div class="row">
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<label class="control-label"  for="inicio"><?php echo JText::_('Data inicio'); ?></label>
			<?php echo JHtml::calendar(JRequest::getVar('inicio',null)!=null? JRequest::getVar('inicio')->format('Y-m-d'):'', 'inicio', 'inicio', '%d/%m/%Y', 'class="form-control required validate-data"');?>
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
			<label class="control-label"  for="fim"><?php echo JText::_('Data fim'); ?></label>
			<?php echo JHtml::calendar(JRequest::getVar('fim',null)!=null? JRequest::getVar('fim')->format('Y-m-d'):'', 'fim', 'fim', '%d/%m/%Y', 'class="form-control validate-data"');?>
		</div>
	</div>
	
	
	
    <div class="btn-group pull-right" role="group">
    	<div class="btn-group" role="group">
	   		<button  class="btn btn-success cadastro1" type="submit" id="buscar"><?php echo JText::_('Buscar'); ?>
    			<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
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
    			<th><?php echo 'Total';?></th>
    		</tr>
    	</thead>
    	<tbody>
<?php 
    $qtdaTotal = 0;
    $valTotal = 0;
    $totalRegistros=0;
    if(isset($extrato) && !empty($extrato)){
        foreach($extrato as $reg){
            $url = JRoute::_ ( 'index.php?option=com_mamaezona&task=extratoDetalheFeijoes&data='.$reg->DATA_GERADO.'&tipo='.$reg->id.'&Itemid='.JRequest::getVar ( 'Itemid' ), false );
            $title = JText::printf('Clique aqui para ver detalhes sobre a trasa&cecil;&atilde;o "%s"',$reg->nome);
            $qtdaTotal += $reg->QUANTIDADE;
            $valTotal += $reg->TOTAL;
            $totalRegistros++;
    ?>	
    		<tr>
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>"><?php echo $reg->nome;?></a></td>	
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>"><?php echo $reg->DATA_GERADO;?></a></td>
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>"><?php echo $reg->QUANTIDADE;?></a></td>
    			<td><a href="<?php echo $url;?>" title="<?php echo $title;?>"><?php echo $reg->TOTAL;?></a></td>
    		</tr>
    <?php 
        }
    }
    // Cria instancia da classe JPagination
    $paginacao = new JPagination($totalRegistros , $totalRegistros, $registrosPorPagina );
    
    // Mostra o conte�do HTML

    ?>
    	</tbody>
 <?php     if(isset($extrato) && !empty($extrato)):?>
    	<tfoot>
    		<tr>
    			<th class="text-align: left"><?php echo isset($pageNav)? $pageNav->getListFooter():'';?></th>
    			<th class="text-align: right"><?php echo 'Total p&aacute;gina';?></th>
    			<th><?php echo $qtdaTotal;?></th>
    			<th><?php echo $valTotal;?></th>
    		</tr>
    	</tfoot>
<?php    endif;?>
     </table>
</div>