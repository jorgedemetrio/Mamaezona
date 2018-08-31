<?php
/*
 * ------------------------------------------------------------------------
 * # view.html.php - MomoSEO Component
 * # ------------------------------------------------------------------------
 * # author Jorge Demetrio
 * # copyright Copyright (C) 2015. All Rights Reserved
 * # license GNU/GPL Version 3 or later - http://www.gnu.org/licenses/gpl-3.0.html
 * # website www.alldreams.com.br
 * -------------------------------------------------------------------------
 */

// No direct access to this file
defined('_JEXEC') || die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Temas View
 */
class MamaezonaViewVideo extends JViewLegacy
{

    /**
     * Temas view display method
     *
     * @return void
     */
    function display($tpl = null)
    {
        // Set the toolbar
        $this->carregarDados();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function carregarDados()
    {
        $document = JFactory::getDocument();
        $pathway = JFactory::getApplication()->getPathway();
        $layout = JRequest::getVar(MamaezonaController::LAYOUT);
        $document->setMetadata('APPLICATION-NAME', 'Mamãezona');
        $tipoId = $this->item->params->get('redeSocial') . $tipoRede = $tipoId == '0' ? 'Youtube' : $tipoId == '2' ? 'Instagram' : $tipoId == '3' ? 'Facebok' : 'Twitter';
        $tipoConteudo = $tipoId == '0' ? 'Vídeo' : $tipoId == '2' ? 'Instagram Foto/Vídeo' : 'Post';

        if ($layout == MamaezonaController::VIEW_LAYOUT_CONFIRMACA_SALVO_CONTEUDO) {
            $pathway->addItem('Conteúdo salvo', '');
            $document->setTitle('Conteúdo salvo com sucesso ' . $document->getTitle());
            $document->setDescription('Vídeo salvo com sucesso');
            $document->setMetadata('Keywords', 'Salvar conteúdo,' . $tipoRede . ',' . $tipoConteudo . ',');
        } elseif ($layout == MamaezonaController::VIEW_LAYOUT_CONTEUDO || $layout == MamaezonaController::VIEW_LAYOUT_CARREGAR_YOUTUBE) {
            $conteudo = JRequest::getVar('CONTEUDO');
            $tipoDesc = $conteudo->tipo == 'Y' ? 'Vìdeo do Youtube' : $conteudo->tipo == 'F' ? 'Poste do Facebook' : $conteudo->tipo == 'I' ? 'Instagram' : 'Poste do twitter';
            $pathway->addItem($tipoDesc, '');
            $document->setTitle($tipoDesc . ' : ' . $conteudo->titulo);
            $document->setDescription($tipoDesc . ' ' . $conteudo->titulo . ' ' . $conteudo->metadesc);
            $document->setMetadata('Keywords', $tipoRede . ',' . $tipoConteudo . ',' . $conteudo->metakey);
        } elseif ($layout == MamaezonaController::VIEW_LAYOUT_CADASTRO) {

            $desc = 'Cadastrar conteúdo para ' . $tipoRede;
            $pathway->addItem($desc, '');
            $document->setTitle($desc);
            $document->setDescription('Vídeo salvo');
            $document->setMetadata('Keywords', 'Cadastrar conteúdo,' . $tipoRede . ',' . $tipoConteudo . ',cadastrar ' . $tipoConteudo . ',divulgar seu ' . $tipoConteudo);
        }
    }
}
?>