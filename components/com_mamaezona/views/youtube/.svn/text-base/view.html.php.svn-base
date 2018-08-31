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
JHTML::_('behavior.formvalidation');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

/**
 * Temas View
 */
class MamaezonaViewCadastro extends JViewLegacy
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

        $user = $_SESSION['usuario'];
        if (! isset($user) || is_null($user)) {
            $user = JFactory::getUser();
        }

        $layout = JRequest::getString('layout', 'default');

        switch ($layout) {
            case 'default':

                break;
            case 'video':
                $conteudo = JRequest::getVar('conteudo');
                if (isset($conteudo) || ! is_null($conteudo)) {
                    $document->setMetadata('APPLICATION-NAME', 'Mam&atilde;ezona para youtubers');
                    $descricao = $conteudo->titulo;
                    $pathway->addItem($descricao, '');
                    $document->setTitle($descricao);
                    $document->setDescription($conteudo->metadesc);
                    $document->setMetadata('Keywords', $conteudo->metakey);

                    $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                    $baseURL = $protocol . $_SERVER['SERVER_NAME'];
                    $urlLocal = $baseURL . $_SERVER['REQUEST_URI'];

                    $stylelink = '
            		 <meta property="og:locale" content="pt-BR" />
            		 <meta property="og:title" content="' . $descricao . '" />
            		 <meta property="og:url" content="' . $urlLocal . '" />
            		 <meta property="og:description" content="' . $conteudo->metadesc . '" />
            		 <meta property="article:section" content="Vídeos" />
            		 <meta property="article:author" content="Mamãezona" />
            		 <link rel="canonical" href="' . $urlLocal . '"/>
            		 <meta property="article:tag" content="' . $conteudo->metakey . '" />';
                    $document->addCustomTag($stylelink);
                }

                break;
            default:
        }
    }
}
?>