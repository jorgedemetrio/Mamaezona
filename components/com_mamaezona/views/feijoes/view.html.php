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
class MamaezonaViewFeijoes extends JViewLegacy
{

    /**
     * Temas view display method
     *
     * @return void
     */
    function display($tpl = null)
    {

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        $document = JFactory::getDocument();
        $pathway = JFactory::getApplication()->getPathway();
        $document->setMetadata('APPLICATION-NAME', 'MomoSEO Sitemap');
        $descricao = 'Sitemap ' . $document->getTitle();
        $pathway->addItem($descricao, '');
        $document->setTitle($descricao);
        $document->setDescription($descricao);
        $document->setMetadata('Keywords', 'sitemap');
    }

    private function extrato()
    {
        $user = $this->getUsuarioLogado();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(array(
            'titulo',
            'token',
            'descricao',
            'id_grupo_assunto'
        )))
            ->from($db->quoteName('#__tipo_conteudo_mm'))
            ->order(array(
            'ordering',
            'titulo'
        ));
        $db->setQuery($query);
        $tipos = $db->loadObjectList();
    }

    private function getUsuarioLogado()
    {
        // Fonte cadastro normal
        $usuario = $_SESSION['usuario'];

        // Logado
        if (! isset($usuario) || is_null($usuario) || $usuario == null) {
            $usuario = JFactory::getUser();
        }

        return $usuario;
    }
}
?>