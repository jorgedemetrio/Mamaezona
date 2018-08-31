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
        $document->setMetadata('APPLICATION-NAME', 'Mam&atilde;ezona para youtubers');
        $descricao = 'Cadastro de youtubers de seu canal [ Mam&atilde;zona para Youtubers ]';
        $pathway->addItem($descricao, '');
        $document->setTitle($descricao);
        $document->setDescription($descricao);
        $document->setMetadata('Keywords', 'Youtubers,cadastro,divulgar meucanal,como divulgar meu canal,como divulgar meu canal do youtube');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $user = null;
        try {
            if (isset($_SESSION['usuario'])) {
                $user = $_SESSION['usuario'];
            }
        } catch (Exception $e) {}
        try {
            if (! isset($user) || is_null($user)) {
                $user = JFactory::getUser();
            }
        } catch (Exception $e) {}

        if (! isset($user) || is_null($user)) {
            $user = JFactory::getUser();
        }

        $layout = JRequest::getString('layout', 'default');

        switch ($layout) {
            case 'default':

                break;
            case 'dados':
                $query->select($db->quoteName(array(
                    'uf',
                    'nome'
                )))
                    ->from($db->quoteName('ceps') . '.' . $db->quoteName('uf'))
                    ->order('nome ASC');
                $db->setQuery($query);
                JRequest::setVar('estados', $db->loadObjectList());

                break;
            case 'videos':
                if (! isset($user) || is_null($user) || $user == null) {
                    die('Erro sess&atilde;o inspirada');
                }
                $query->select($db->quoteName(array(
                    'id_usuario',
                    'titulo',
                    'token',
                    'token_provedor',
                    'url',
                    'descricao',
                    'tipo',
                    'created_by_ip',
                    'modified_by_ip',
                    'id_tipo_conteudo',
                    'alias',
                    'status',
                    'catid',
                    'created',
                    'created_by',
                    'created_by_alias',
                    'modified',
                    'modified_by',
                    'checked_out',
                    'checked_out_time',
                    'publish_up',
                    'publish_down',
                    'images',
                    'urls',
                    'attribs',
                    'version',
                    'ordering',
                    'metakey',
                    'metadesc',
                    'access',
                    'hits',
                    'metadata',
                    'language',
                    'xreference'
                ))
                    ->from($db->quoteName('#__conteudo_mm'))
                    ->where(array(
                    $db->quoteName('id_usuario') . ' = ' . $db->quote($user->id)
                )))
                    ->order('ordering ASC');
                $db->setQuery($query);
                JRequest::setVar('videos', $db->loadObjectList());

                break;
            default:
        }

        /*
         * $stylelink='
         * <meta property="og:locale" content="pt-BR" />
         * <meta property="og:title" content="Cadastro de yutubers" />
         * <meta property="og:url" content="'.$urlLocal.'" />
         * <meta property="og:description" content="Cadastro de youtubers no mam&atilde;ezona" />
         * <meta property="article:section" content="Cadastro" />
         * <meta property="article:author" content="Mam&atilde;ezona" />
         * <link rel="canonical" href="'.$urlLocal.'"/>
         * <meta property="article:tag" content="cadastro de youtubers,cadastro para divulgar o canal" />';
         * $document->addCustomTag($stylelink);
         */
    }
}
?>