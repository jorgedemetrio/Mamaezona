<?php

/*
 * ------------------------------------------------------------------------
 * # controller.php - Mamaezona Component
 * # ------------------------------------------------------------------------
 * # author Jorge Demetrio
 * # copyright Copyright (C) 2015. All Rights Reserved
 * # license GNU/GPL Version 3 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * # website www.alldreams.com.br
 * -------------------------------------------------------------------------
 */
// No direct access to this file
defined('_JEXEC') || die('Restricted access');
// import Joomla controller library

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');
include_once JPATH_BASE . DS . 'components/com_content/models/article.php';
require_once JPATH_BASE . DS . 'components/com_content/helpers/route.php';
require_once JPATH_BASE . DS . 'components/com_content/helpers/query.php';
jimport('joomla.application.module.helper');
jimport('joomla.mail.mail');
jimport('joomla.log.log');

JLoader::import('joomla.application.component.model');

class GrupoAcesso
{

    const PUBLICO = 1;

    const GUEST = 9;

    const REGISTRED = 2;

    const YOUTUBERS = 10;

    const FACEBOOKER = 12;

    const INSTAGRAMER = 11;

    const TWITTEIRO = 11;
}

/**
 * STATUS PADRÃO
 * 0 = CADASTRADO PARA VALIDACAO
 * 1 = ATIVADO OU PUBLICADO
 * 2 = BLOQUEADO
 * 3 = REMOVIDO
 *
 * @author jorgedemetrio
 *
 */
class Status
{

    const CADASTRADO = 0;

    const ATIVO = 1;

    const BLOQUEADO = 2;

    const REMOVIDO = 3;
}

/**
 * MamaezonaSEO Component Controller
 */
class MamaezonaController extends JControllerLegacy
{

    const VIEW_LAYOUT_CONFIRMACA_SALVO_CONTEUDO = 'salvo';

    const VIEW_LAYOUT_CONTEUDO = 'default';

    const VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1 = 'default';

    const VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA2 = 'dados';

    const VIEW_LAYOUT_CADASTRO_YOUTUBER_VIDEO = 'videos';

    const VIEW_LAYOUT_CADASTRO_CONFIRMACAO = 'confirmado';

    const VIEW_LAYOUT_VIDEO = 'video';

    const VIEW_LAYOUT_EXTRATO_FEIJAO = 'feijao';

    const VIEW_LAYOUT_EXTRATO_FEIJAO_DETALHE = 'feijao_detalhe';

    const VIEW_EXTRATO = 'extrato';

    const VIEW_VER_VIDEOS_YOUTUBERS = 'youtube';

    const VIEW_CADASTRO_YOUTUBERS = 'cadastro';

    const VIEW = 'view';

    const LAYOUT = 'layout';

    const PUBLICKEY_RECAPTCHA = '6LeiDjMUAAAAAE8C4OXCyNAp4poHXDvfsJ3NJicx';

    const PRIVATEKEY_RECAPTCHAR = '6LeiDjMUAAAAACO05Ay2A4wrTfaLJCRmecJvObWo';

    const ITENS_POR_PAGINA = 50;

    function display($cachable = false, $urlparams = false)
    {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'Mamaezona'));

        // call parent behavior
        parent::display($cachable);

        // set view
        $view = strtolower(JRequest::getVar('view'));
    }

    /**
     * Responsável por gravar os dados para o Mamaezona
     *
     * @param string $url
     * @param string $tipo
     * @param double $prioridade
     */
    private function addItemSiteMap($url, $tipo = 'PAGE', $prioridade = 0.1)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $values = array(
            $db->quote($url),
            $db->quote($tipo),
            'GETDATE()',
            $prioridade
        );
        $query->insert($db->quoteName('#__mom_dyna_page'))
            ->columns($db->quoteName(array(
            'url',
            'data_alteracao',
            'prioridade',
            'tipo'
        )))
            ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Salvar conteádo, Youtube, Instagram
     */
    private function conteudo()
    {
        $id = JRequest::getVar('id', null, 'get', 'string');

        if (! isset($id) || empty($id) || $id == null) {
            die('Sem permiss&atilde;o');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(array(
            'id',
            'id_usuario',
            'titulo',
            'token',
            'url',
            'descricao',
            'tipo',
            'created_by_ip',
            'modified_by_ip',
            'alias',
            'state',
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
        )))
            ->from($db->quoteName('#__conteudo_mm'))
            ->where($db->quoteName('token') . ' = ' . $db->quote($id))
            ->order('ordering ASC');
        $db->setQuery($query);

        JRequest::setVar('conteudo', $db->loadObject());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CONTEUDO);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBE);
        parent::display();
    }

    /**
     * Carregar página com vídeo do Youtube
     */
    public function youtube()
    {
        $this->conteudo();

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_VER_VIDEOS_YOUTUBERS);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
        parent::display();
    }

    /**
     */
    public function home()
    {
        $usuario = $this->getUsuarioLogado();
        if (! isset($usuario) || is_null($usuario) || ! isset($usuario->id) || $usuario->id == 0) {
            $this->homeNaoLogado();
        } else {
            $this->homeLogado();
        }
    }

    /**
     */
    private function homeNaoLogado()
    {
        // 1 Vídeos MENOS vizualiados com maior nível, maior crádio
        // 1 Vídeos MAIS vizualiados com maior nível, maior crádio
        // 1 Vídeos MENOS vizualiados com menor nível, maior crádio
        // 1 Vídeos MAIS vizualiados com menor nível, maior crádio
        // 4 Carregar canais com maior nível, maior crádio
        // 2 Banners localização HOME
        // 2 Canais prêmios
        // Se primceiro acesso cookie 30 dias Banner Full Screen
        // Solicitar notificação
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array(
            'token',
            'titulo',
            'alias'
        )))
            ->from($db->quoteName('#__HOME_CONTEUDO_NAO_LOGADO'));
        $db->setQuery($query);
        JRequest::setVar('conteudos_destaque', $db->loadObjectList());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CONTEUDO);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBE);
        parent::display(true);
    }

    /**
     */
    private function homeLogado()
    {
        $usuario = $this->getUsuarioLogado();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array(
            'token',
            'titulo',
            'alias'
        )))
            ->from($db->quoteName('#__HOME_CONTEUDO_LOGADO'))
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id
        ));

        $db->setQuery($query);
        JRequest::setVar('conteudos_destaque', $db->loadObjectList());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CONTEUDO);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBE);
        parent::display(false); // Desabilita o cache
    }

    /**
     * Grava os dados básicos e envia e-mail de confirmação de conta.
     */
    function salvarUsuario()
    {

        // Valida o token
        if (! JSession::checkToken('post')) {
            die('Restricted access');
        }
        $token_yotube = JRequest::getString('youtube', '', 'POST');
        $nome = JRequest::getString('name', null, 'POST');
        $sobrenome = JRequest::getString('lname', null, 'POST');
        $pais_canal = JRequest::getString('pais_canal', '', 'POST');
        $customUrl = JRequest::getString('customUrl', '', 'POST');
        $descricao_canal = JRequest::getString('descricao_canal', '', 'POST');
        $publicado_canal = JRequest::getString('publicado_canal', '', 'POST');
        $thumb_default_canal = JRequest::getString('thumb_default_canal', '', 'POST');
        $thumb_high_canal = JRequest::getString('thumb_high_canal', '', 'POST');
        $thumb_medium_canal = JRequest::getString('thumb_medium_canal', '', 'POST');
        $titulo = JRequest::getString('titulo', '', 'POST');
        $pl_favorites_canal = JRequest::getString('pl_favorites_canal', '', 'POST');
        $pl_likes_canal = JRequest::getString('pl_likes_canal', '', 'POST');
        $pl_uploads_canal = JRequest::getString('pl_uploads_canal', '', 'POST');

        $access_token = JRequest::getString('access_token', '', 'POST');
        $expira = JRequest::getInt('expira', null, 'POST');
        $issued = JRequest::getInt('issued', null, 'POST');
        $login_hint = JRequest::getString('login_hint', '', 'POST');

        $expira_dt = null;
        $issued_dt = null;

        $datetime = new DateTime();

        if (isset($expira) && $expira != 0) {
            $datetime->setTimestamp($expira);
            $expira_dt = $datetime->format('Y-m-d H:i:s');
        }
        if (isset($issued) && $issued != 0) {
            $datetime->setTimestamp($issued);
            $issued_dt = $datetime->format('Y-m-d H:i:s');
        }

        if (! isset($token_yotube) || $token_yotube == '') {
            JError::raiseWarning(100, JText::_("Conta n&atilde;o verificada pelo Youtube"));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        /*
         * $reCaptchaName = 'name'; // the name of the captcha plugin - retrieved from the custom component's parameters
         *
         * $privatekey = "your_private_key";
         * $resp = recaptcha_check_answer ($privatekey,
         * $_SERVER["REMOTE_ADDR"],
         * $_POST["recaptcha_challenge_field"],
         * $_POST["recaptcha_response_field"]);
         *
         * MamaezonaController::PRIVATEKEY_RECAPTCHAR
         */

        $code = JRequest::getString('recaptcha_response_field', '', 'POST');
        JPluginHelper::importPlugin('captcha');
        $dispatcher = JDispatcher::getInstance();
        $res = $dispatcher->trigger('onCheckAnswer', $code);
        if (! $res[0]) {
            JError::raiseWarning(100, JText::_("Siga a valida&ccedil;&atilde;o do captchar corretamente. (Imagem que v&aacute;lida que n&atilde;o &eacute; um robo.)"));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        if (! isset($user) && ! isset($nome)) {
            JError::raiseWarning(100, JText::_("Nome e sobre nome s&atilde;o campos obrigat&oacute;rios."));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        $db = JFactory::getDbo();

        $usuario = $this->gravarUser();
        // $_SESSION['usuario'] = $usuario;

        if ($usuario == null) {
            JError::raiseWarning(100, JText::_("Erro ao salvar o usuário"));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }
        $ipClient = $db->quote($this->getIp());
        $valores = array(
            $usuario->id,
            $db->quote($nome),
            $db->quote($nome),
            $db->quote($sobrenome),
            $db->quote(md5(uniqid(rand(), true))),
            $db->quote($token_yotube),
            $db->quote('Y'),
            $usuario->id,
            $db->quote($ipClient),
            $db->quote($pais_canal),
            $db->quote($customUrl),
            $db->quote($descricao_canal),
            $db->quote($publicado_canal),
            $db->quote($thumb_default_canal),
            $db->quote($thumb_high_canal),
            $db->quote($thumb_medium_canal),
            $db->quote($titulo),
            $db->quote($pl_favorites_canal),
            $db->quote($pl_likes_canal),
            $db->quote($pl_uploads_canal)
        );

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__usuario_mm'))
            ->columns($db->quoteName(array(
            'id_usuario',
            'apelido',
            'primeiro_nome',
            'sobre_nome',
            'token',
            'toke_youtube',
            'tipo',
            'created_by',
            'created_by_ip',
            'pais_canal',
            'customUrl',
            'descricao_canal',
            'publicado_canal',
            'thumb_default_canal',
            'thumb_high_canal',
            'thumb_medium_canal',
            'titulo',
            'pl_favorites_canal',
            'pl_likes_canal',
            'pl_uploads_canal'
        )))
            ->values(implode(',', $valores));
        $db->setQuery($query);
        $db->execute();

        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__youtube_tokens'))
            ->columns($db->quoteName(array(
            'id_usuario',
            'access_token',
            'expira',
            'issued',
            'login_hint',
            'data',
            'created_by_ip'
        )))
            ->values(implode(',', array(
            $usuario->id,
            $db->quote($access_token),
            (isset($expira_dt) ? $db->quote($expira_dt) : 'null'),
            (isset($issued_dt) ? $db->quote($issued_dt) : 'null'),
            $db->quote($login_hint),
            'NOW()',
            $ipClient
        )));
        $db->setQuery($query);
        $db->execute();

        $this->recarregarAssuntosUsuario();

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_CONFIRMACAO);
        parent::display();
    }

    private function recarregarAssuntosUsuario()
    {
        $usuario = $this->getUsuarioLogado();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__usu_assunto_interesse_mm'));
        $query->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id
        ));
        $db->setQuery($query);
        $db->execute();

        $interesses = JRequest::getVar('interesse', array(), 'POST', 'array');
        $assuntos = JRequest::getVar('assunto', array(), 'POST', 'array');

        for ($i = 0; $i < 3; $i ++) {
            $assunto = $assuntos[i];
            $interesse = $interesses[i];
            if (isset($interesse) && ! is_null($interesse) && $interesse != '' && isset($assunto) && ! is_null($assunto) && $assunto != '') {
                if ($interesse > 0 && $assunto > 0) {
                    $this->salvarAssuntoUsuario($usuario->id, $interesse, $assunto);
                }
            }
        }
    }

    private function salvarAssuntoUsuario($idUsuario, $idInteresse, $idAssunto)
    {
        $db = JFactory::getDbo();
        /*
         * $query = $db->getQuery(true);
         * $query->select(array(
         * 'id'
         * ))
         * ->from($db->quoteName('#__assunto_conteudo_mm') )
         * ->where(array(
         * ' token = ' . $db->quote($idAssunto)
         * ));
         * $db->setQuery($query);
         * $idAssuntoFinal = $db->loadObject();
         */

        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__usu_assunto_interesse_mm'))
            ->columns($db->quoteName(array(
            'id_assunto',
            'id_tipo_cont',
            'id_usuario',
            'created',
            'created_by'
        )))
            ->values(implode(',', array(
            "(SELECT id FROM #__assunto_conteudo_mm WHERE token = " . $db->quote($idAssunto) . ")",
            "(SELECT id FROM #__tipo_conteudo_mm WHERE token = " . $db->quote($idInteresse) . ")",
            $idUsuario,
            ' NOW() ',
            $idUsuario
        )));
        $db->setQuery($query);
        $db->execute();
    }

    public function salvarUsuarioDetalhes()
    {
        if (! JSession::checkToken('post')) {
            die('Restricted access');
        }
        $apelido = JRequest::getString('apelido', null, 'post');
        $ddd_cel = JRequest::getString('ddd_cel', null, 'post');
        $celular = JRequest::getString('celular', null, 'post');
        $genero = JRequest::getString('genero', null, 'post');
        $nascimento = JRequest::getString('nascimento', null, 'post');
        $cep = JRequest::getString('cep', null, 'post');
        $uf = JRequest::getString('uf', null, 'post');
        $cidade = JRequest::getInt('cidade', null, 'post');
        $logradouro = JRequest::getInt('logradouro', null, 'post');
        $numero = JRequest::getInt('numero', null, 'post');
        $bairro = JRequest::getInt('bairro', null, 'post');
        $complemento = JRequest::getInt('complemento', null, 'post');

        $hasError = false;

        $nascimento_pased = null;

        $usuario = $this->getUsuarioLogado();

        if (! isset($usuario) || is_null($usuario)) {
            JError::raiseWarning(100, JText::_("Sess&atilde;o inspirada, inicie um cadastro!"));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        if (! isset($nascimento) || is_null($nascimento) || $nascimento == '') {
            JError::raiseWarning(100, JText::_("Data de nascimento n&atilde;o &eacute; v&aacute;lida"));
            $hasError = true;
        } else {
            $nascimento_pased = JFactory::getDate($nascimento)->format('d/m/Y');
            $data = mktime(0, 0, 0, date("m"), date("d"), date("Y") - 17);
            if ($nascimento_pased > $data) {
                JError::raiseWarning(100, JText::_("Cadastro apenas permitido para maiores de 18 anos."));
                $hasError = true;
            }
        }

        if (! isset($genero) || is_null($genero) || $genero == '') {
            JError::raiseWarning(100, JText::_("Defina um genero."));
            $hasError = true;
        }

        if (! isset($cep) || is_null($cep) || $cep == '') {
            JError::raiseWarning(100, JText::_("Digite um CEP v&aacute;lido."));
            $hasError = true;
        }

        if (! isset($ddd_cel) || is_null($ddd_cel) || $ddd_cel == '') {
            JError::raiseWarning(100, JText::_("Digite DDD para o celular v&aacute;lido."));
            $hasError = true;
        }

        if (! isset($celular) || is_null($celular) || $celular == '') {
            JError::raiseWarning(100, JText::_("Digite um celular v&aacute;lido."));
            $hasError = true;
        }
        if (! isset($uf) || is_null($uf) || $uf == '') {
            JError::raiseWarning(100, JText::_("Seleciona um Estado/Unidade Federativa."));
            $hasError = true;
        }
        if (! isset($cidade) || is_null($cidade) || $cidade == '') {
            JError::raiseWarning(100, JText::_("Selecione uma cidade."));
            $hasError = true;
        }

        if (! isset($logradouro) || is_null($logradouro) || $logradouro == '') {
            JError::raiseWarning(100, JText::_("Defina seu endere&ccedil;o."));
            $hasError = true;
        }

        if (! isset($numero) || is_null($numero) || $numero == '') {
            JError::raiseWarning(100, JText::_("Defina um námero do endere&ccedil;o."));
            $hasError = true;
        }

        if (! isset($bairro) || is_null($bairro) || $cidade == '') {
            JError::raiseWarning(100, JText::_("Defina o seu bairro."));
            $hasError = true;
        }

        if ($hasError) {
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        $nascimento_pased = JFactory::getDate($nascimento)->format('Y-m-d');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__usuario_mm'))
            ->set(array(
            $db->quoteName('apelido') . ' = ' . $db->quote($apelido),
            $db->quoteName('ddd_celular') . ' = ' . $db->quote($ddd_cel),
            $db->quoteName('celular') . ' = ' . $db->quote($celular),
            $db->quoteName('genero') . ' = ' . $db->quote($genero),
            $db->quoteName('data_nascimento') . ' = ' . $db->quote($nascimento_pased),
            $db->quoteName('cep') . ' = ' . $db->quote($cep),
            $db->quoteName('id_cidade') . ' = ' . $db->quote($cidade),
            $db->quoteName('logradouro') . ' = ' . $db->quote($logradouro),
            $db->quoteName('numero') . ' = ' . $db->quote($numero),
            $db->quoteName('bairro') . ' = ' . $db->quote($bairro),
            $db->quoteName('complemento') . ' = ' . (isset($complemento) ? $db->quote($complemento) : 'null')
        ))
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $db->quote($usuario->id)
        ));
        $db->setQuery($query);
        $db->execute();

        $this->carregarEditarVideos();
    }

    /**
     * Carrega Vídeos
     */
    function carregarEditarVideos()
    {
        $db = JFactory::getDbo();
        $usuario = $this->getUsuarioLogado();
        if (! isset($usuario) || is_null($usuario) || $usuario == null) {
            JError::raiseWarning(100, JText::_("Sess&atilde;o inspirada, inicie um cadastro!"));
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA1);
            parent::display();
            return;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName(array(
            'id',
            'id_usuario',
            'titulo',
            'token',
            'url',
            'descricao',
            'tipo',
            'created_by_ip',
            'modified_by_ip',
            'alias',
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
            'metakey',
            'metadesc',
            'access',
            'hits',
            'metadata',
            'language',
            'xreference'
        )))
            ->from($db->quoteName('#__conteudo_mm'))
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id,
            $db->quoteName('status') . ' IN (' . $db->quote(Status::ATIVO) . ', ' . $db->quote(Status::CADASTRADO) . ')'
        ))
            ->order('ordering ASC');
        $db->setQuery($query);

        JRequest::setVar('conteudo', $db->loadObjectList());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_YOUTUBER_TELA2);
        parent::display();
    }

    public function video()
    {
        $id = JRequest::getString('v', null, 'get');
        if (is_null($id) || ! isset($id)) {
            JError::raiseError(404, 'P&aacute;gina n&atilde;o encontrada');
            JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_VER_VIDEOS_YOUTUBERS);
            JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_VIDEO);
            parent::display();
            return;
        }
        $db = JFactory::getDbo();

        $usuario = $this->getUsuarioLogado();

        $query = $db->getQuery(true);
        $query->select(array(
            'a.id',
            'a.id_usuario',
            'a.titulo',
            'a.token',
            'a.token_provedor',
            'a.url',
            'a.descricao',
            'a.tipo',
            'a.alias',
            'a.state',
            'a.estat_view',
            'a.estat_lks',
            'a.estat_desliks',
            'a.estat_comments',
            'a.created',
            'a.created_by',
            'a.created_by_alias',
            'a.modified',
            'a.modified_by',
            'a.checked_out',
            'a.checked_out_time',
            'a.publish_up',
            'a.publish_down',
            'a.metakey',
            'a.metadesc',
            'a.hits',
            'a.metadata'
        ))
            ->from($db->quoteName('#__conteudo_mm') . ' AS a')
            ->JOIN('INNER', $db->quoteName('#__usuario_mm') . ' AS b ON a.id_usuario = b.id_usuario ')
            ->JOIN('INNER', $db->quoteName('#__users') . ' AS c ON a.id_usuario = c.id ')
            ->where(array(
            $db->quoteName('token') . ' = ' . $db->quote($id),
            'a.' . $db->quoteName('status') . ' IN (' . $db->quote(Status::ATIVO) . ', ' . $db->quote(Status::CADASTRADO) . ')',
            '( a.' . $db->quoteName('publish_up') . ' IS NULL ||  a.' . $db->quoteName('publish_up') . ' <= NOW() )',
            '( a.' . $db->quoteName('publish_down') . ' IS NULL ||  a.' . $db->quoteName('publish_down') . ' > NOW() )'
        ));
        $db->setQuery($query);

        $conteudo = $db->loadObject();

        if ($conteudo->status != Status::ATIVO) {
            JError::raiseError(4711, 'Conte&uacute;do n&atilde;o liberado');
            $this->home();
            return;
        }
        JRequest::setVar('conteudo', $conteudo);

        // Calcula todas as regras de pontos de vizualização de Vídeos e hiss
        $query = 'CALL prc_carregar_conteudo(' . $db->quote($id) . ',' . (isset($usuario) && ! is_null($usuario) && $usuario->id > 0 ? $usuario->id : 'null') . ',' . $db->quote($this->getIp()) . ')';

        $db->setQuery($query);
        $db->query();
        // $db->execute();

        $query = $db->getQuery(true);
        $query->select(array(
            'A.titulo',
            'A.token',
            'A.descricao',
            'B.titulo AS TIPO',
            'B.token AS TOKEN_TIPO'
        ))
            ->from($db->quoteName('#__assunto_conteudo_mm') . ' AS A ')
            ->join('INNER', $db->quoteName('#__tipo_conteudo_mm') . ' AS B ON b.id_grupo_assunto = A.id_grupo_assunto')
            ->join('INNER', $db->quoteName('#__usu_assunto_princ_cont_mm') . ' AS C ON c.id_assunto = A.id')
            ->where(array(
            ' C.id_conteudo = ' . $conteudo->id
        ));
        $db->setQuery($query);
        JRequest::setVar('assuntos', $db->loadObjectList());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_VER_VIDEOS_YOUTUBERS);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_VIDEO);
        parent::display();
    }

    public function extratoFeijoes()
    {
        $usuario = $this->getUsuarioLogado();
        // $pagina = JRequest::getInt('p',0);

        $option = 0;
        $mainframe = JFactory::getApplication();

        $lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', 50, 'int');
        $lim0 = JRequest::getVar('limitstart', 0, '', 'int');

        $inicio = JRequest::getInt('inicio', null, 'get');
        $fim = JRequest::getInt('fim', null, 'get');

        if (isset($inicio) && ! is_null($inicio)) {
            $inicio = JFactory::getDate($inicio)->format('Y-m-d');
        } else {
            $inicio = JFactory::getDate()->format('Y-m-d');
        }

        if (isset($fim) && ! is_null($fim)) {
            $fim = JFactory::getDate($fim)->format('Y-m-d');
        } else {
            $fim = strtotime(date("Y-m-d") . " +30 day");
            $fim = JFactory::getDate($fim)->format('Y-m-d');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array(
            'SQL_CALC_FOUND_ROWS',
            'tp.id as id',
            'tp.nome as nome',
            'tp.credito_debito as credito_debito',
            'DATE_FORMAT(ex.data_movimento, "%d/%M/%Y") AS  DATA_GERADO',
            'SUM(ex.quantidade) AS TOTAL',
            'COUNT(ex.quantidade) AS QUANTIDADE'
        ))
            ->from($db->quoteName('#__movimento_feijoes_mm') . ' AS ex')
            ->join('INNER', $db->quoteName('#__tipo_movimento') . ' AS tp ON (tp.id = ex.id_tipo_motivo) ')
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id,
            $db->quoteName('status') . ' IN (' . $db->quote(Status::ATIVO) . ', ' . $db->quote(Status::CADASTRADO) . ')',
            'ex.data_movimento between ( ' . $db->quote($inicio) . ' AND ' . $db->quote($fim) . ') '
        ))
            ->order('DATA_GERADO DESC')
            ->group(array(
            'tp.nome',
            'ex.credito_debito',
            'DATA_GERADO'
        ))
            ->limit($lim0, $lim);
        /*
         * ->limit(
         * ($pagina * MamaezonaController::ITENS_POR_PAGINA),
         * (($pagina + 1) * MamaezonaController::ITENS_POR_PAGINA));
         */

        $db->setQuery($query);
        $lista = $db->loadObjectList();

        if (empty($lista) || ! isset($lista)) {
            // JError::raiseError( 4711, 'A severe error occurred' );
            JError::raiseWarning(100, JText::_('Registros n&atilde;o encontrados'));
        }

        JRequest::setVar('extrato', $lista);
        $query = $db->getQuery(true);
        $db->setQuery('SELECT FOUND_ROWS();');
        JRequest::setVar('pageNav', new JPagination($db->loadResult(), $lim0, $lim));

        $query = $db->getQuery(true);

        $query->select(array(
            'id_usuario',
            'apelido',
            'saldo_feijoes',
            'saldo_tutu'
        ))
            ->from($db->quoteName('#__usuario_mm'))
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id
        ));
        JRequest::setVar('usuario', $db->loadObject());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_EXTRATO);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_EXTRATO_FEIJAO);
        parent::display();
    }

    public function extratoDetalheFeijoes()
    {
        jimport('joomla.html.pagination');

        $usuario = $this->getUsuarioLogado();
        // $pagina = JRequest::getInt('p',0);

        $option = 0;
        $mainframe = JFactory::getApplication();

        $lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', 50, 'int');
        $lim0 = JRequest::getVar('limitstart', 0, '', 'int');

        $data = JRequest::getString('data', null, 'get');
        $tipo = JRequest::getInt('tipo', 0, 'get');

        if (isset($data) && ! is_null($data)) {
            $inicio = JFactory::getDate($data)->format('Y-m-d');
        } else {
            $data = JFactory::getDate()->format('Y-m-d');
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array(
            'SQL_CALC_FOUND_ROWS',
            'ex.id as id',
            'tp.motivo as nome',
            'con.titulo',
            'con.url_thumb_default as thumb',
            'con.token_provedor as token',
            'tp.credito_debito as credito_debito',
            'DATE_FORMAT(ex.data_movimento, "%d/%M/%Y %H:%i") AS  DATA_GERADO',
            'ex.quantidade AS QUANTIDADE'
        ))
            ->from($db->quoteName('#__movimento_feijoes_mm') . ' AS ex')
            ->join('INNER', $db->quoteName('#__tipo_movimento') . ' AS tp ON (tp.id = ex.id_tipo_motivo) ')
            ->join('LEFT', $db->quoteName('#__conteudo_mm') . ' AS con ON (con.id = ex.id_conteudo_origem) ')
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id,
            $db->quoteName('status') . ' IN (' . $db->quote(Status::ATIVO) . ', ' . $db->quote(Status::CADASTRADO) . ')',
            'ex.data_movimento = ' . $db->quote($data),
            'tp.id = ' . $db->quote($tipo)
        ))
            ->limit($lim0, $lim);
        /*
         * ->limit(
         * ($pagina * MamaezonaController::ITENS_POR_PAGINA),
         * (($pagina + 1) * MamaezonaController::ITENS_POR_PAGINA));
         */

        $db->setQuery($query);

        JRequest::setVar('extrato', $db->loadObjectList());

        $db->setQuery('SELECT FOUND_ROWS();');
        JRequest::setVar('pageNav', new JPagination($db->loadResult(), $lim0, $lim));

        $query = $db->getQuery(true);

        $query->select(array(
            'id_usuario',
            'apelido',
            'saldo_feijoes',
            'saldo_tutu'
        ))
            ->from($db->quoteName('#__usuario_mm'))
            ->where(array(
            $db->quoteName('id_usuario') . ' = ' . $usuario->id
        ));
        JRequest::setVar('usuario', $db->loadObject());

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_EXTRATO);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_EXTRATO_FEIJAO_DETALHE);
        parent::display();
    }

    public function cidadeJson()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $uf = JRequest::getString('uf', 'SP', 'post');

        $query->select($db->quoteName(array(
            'nome',
            'id'
        )))
            ->from($db->quoteName('cep') . '.' . $db->quoteName('cidades'))
            ->where(array(
            'upper(uf) = upper(' . trim($db->quote($uf)) . ')'
        ))
            ->order(array(
            'ordering',
            'titulo'
        ));
        $db->setQuery($query);
        $cidades = $db->loadObjectList();

        header('Content-type:application/json;charset=utf-8');
        echo json_encode($cidades);
        die();
    }

    /**
     * Metodo JSON para trazer os intereces
     */
    public function interessesJSON()
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

        foreach ($tipos as $tipo) {
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array(
                'titulo',
                'token',
                'descricao'
            )))
                ->from($db->quoteName('#__assunto_conteudo_mm'));

            if (isset($user) && ! is_null($user) && $user->id > 0) {
                $query->where(array(
                    $db->quoteName('id_grupo_assunto') . ' = ' . $tipo->id_grupo_assunto,
                    '(' . $db->quoteName('status') . ' = ' . Status::ATIVO . ' || ' . $db->quoteName('created_by') . ' = ' . $user->id . ' ) '
                ));
            } else {
                $query->where(array(
                    $db->quoteName('id_grupo_assunto') . ' = ' . $tipo->id_grupo_assunto,
                    $db->quoteName('status') . ' = ' . Status::ATIVO
                ));
            }
            $query->order(array(
                'ordering',
                'titulo'
            ));
            $db->setQuery($query);
            $tipo->assuntos = $db->loadObjectList();
        }

        header('Content-type:application/json;charset=utf-8');
        echo '$(document).ready(function(){MAMAEZONA.tipos=' . json_encode($tipos) . ';});';
        die();
    }

    /**
     *
     * @return usuario salvo
     */
    private function gravarUser()
    {
        $userBinder = JFactory::getUser(0);

        try {
            $user = $this->getUsuarioLogado();
            $email = trim(JRequest::getString('email', '', 'POST'));
            $email1 = trim(JRequest::getString('email1', '', 'POST'));
            $senha = trim(JRequest::getString('password', null, 'POST'));
            $senha2 = trim(JRequest::getString('password1', null, 'POST'));
            $nome = trim(JRequest::getString('name', null, 'POST'));

            if (isset($user) && $user->id != 0) {
                $user->name = $nome;
                if (! $user->save()) {
                    JError::raiseWarning(100, JText::_($user->getError()));
                }
            } else {
                if (isset($senha) && isset($senha2) && isset($user) && isset($nome)) {
                    $token = $this->gerarTokenAtivacaoContaEnviarEmail($email, $nome);
                    if ($token == null) {
                        return null;
                    }

                    $usersParams = JComponentHelper::getParams('com_users');
                    $userdata = array();
                    $userdata['username'] = $email;
                    $defaultUserGroup = $usersParams->get('new_usertype', 2);

                    $userdata['email'] = $email;
                    $userdata['email1'] = $email1;
                    $userdata['name'] = $nome;
                    $userdata['password'] = $senha;
                    $userdata['password2'] = $senha2;
                    $userdata['block'] = 1;
                    $userdata['activation'] = $token;
                    $userdata['groups'] = array(
                        $defaultUserGroup,
                        GrupoAcesso::YOUTUBERS
                    );

                    if (! $userBinder->bind($userdata)) {
                        JLog::add($userBinder->getError(), JLog::WARNING);
                        JError::raiseWarning(100, JText::_($userBinder->getError()));
                        return null;
                    }

                    if (! $userBinder->save()) {
                        JLog::add($userBinder->getError(), JLog::WARNING);
                        JError::raiseWarning(100, JText::_($user->getError()));
                        return null;
                    }
                } else {
                    JError::raiseWarning(100, JText::_("Erro no cadastro"));
                    return null;
                }
            }
            return $user;
        } catch (Exception $e) {
            JLog::add($e->getMessage(), JLog::WARNING);
            JError::raiseWarning(100, $e->getMessage());
        }
        return null;
    }

    private function gerarTokenAtivacaoContaEnviarEmail($email, $nome)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $token = md5(uniqid(rand(), true));
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $mailer->setSender($sender);

        $urlSalvar = $_SERVER['SERVER_NAME'] . JRoute::_('index.php?option=com_mamaezona&task=confirmarConta&t=' . $token, false);

        $body = '<h2>Seja bem vindo ao mam&atilde;ezona</h2>' . '<p>Ol&aacute;, ' . $nome . ', tudo bem?<p><p>Acesse o linque abaixo para confirmar a sua conta.<br/><a href="' . $urlSalvar . '">' . $urlSalvar . '</a></p>';
        $body .= '<p>A proposito o seu usu&aacute;rio &eacute; : <b>' . $email . '</b></p>';
        $mailer->isHtml(true);
        $mailer->Encoding = 'base64';
        $mailer->addRecipient($email);
        $mailer->setSubject('[Mamãezona] Confirmação de conta, segue o linque para confirmar a conta.');
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
            JLog::add('Erro ao enviar e-mail para ' . $email, JLog::WARNING);
            JError::raiseWarning(100, 'Erro ao enviar e-mail para ' . $email);
            return null;
        }
        return $token;
    }

    private function emailAgradecimenot($email, $nome)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $token = md5(uniqid(rand(), true));
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );

        $mailer->setSender($sender);

        $urlSalvar = $_SERVER['SERVER_NAME'] . JRoute::_('index.php?option=com_mamaezona&task=confirmarConta&t=' . $token, false);

        $body = '<h2>Obrigado por confirmar seu cadastro</h2>' . '<p>Olá, ' . $nome . ', tudo bem?<p><p>Acesse o linque abaixo para confirmar a sua conta.<br/><a href="' . $urlSalvar . '">' . $urlSalvar . '</a></p>';
        $mailer->isHtml(true);
        $mailer->Encoding = 'base64';
        $mailer->addRecipient($email);
        $mailer->setSubject('[Mamãezona] Conclusão de cadastro.');
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) {
            JLog::add('Erro ao enviar e-mail para ' . $email, JLog::WARNING);
            JError::raiseWarning(100, 'Erro ao enviar e-mail para ' . $email);
            return null;
        }
        return $token;
    }

    public function confirmarConta()
    {
        $db = JFactory::getDbo();
        $token = trim(JRequest::getString('t', '', 'GET'));

        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__users'))
            ->set(array(
            $db->quoteName('activation') . " = ''",
            $db->quoteName('block') . ' = 0'
        ))
            ->where(array(
            $db->quoteName('activation') . ' = ' . $db->quote($token)
        ));
        $db->setQuery($query);
        $db->execute();

        JRequest::setVar(MamaezonaController::VIEW, MamaezonaController::VIEW_CADASTRO_YOUTUBERS);
        JRequest::setVar(MamaezonaController::LAYOUT, MamaezonaController::VIEW_LAYOUT_CADASTRO_CONFIRMACAO);
        parent::display();
    }

    private function getIp()
    {
        $ip = '';
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private function getShortURL($url)
    {
        // AIzaSyCWYKcpB6cFdn_AB8TYz_8B_5LLS9wQ3ng
        // "Authorization: Basic ".base64_encode("$https_user:$https_password")."\r\n",
        $data_url = http_build_query(array(
            'key' => 'AIzaSyCWYKcpB6cFdn_AB8TYz_8B_5LLS9wQ3ng',
            'longUrl' => $url
        ));
        $data_len = strlen($data_url);

        $opcoes = array(
            'http' => array(
                'method' => "POST",
                'header' => "Accept-language: en\r\n" . "Cookie: foo=bar\r\n",
                'Content-Type' => 'application/json',
                'header' => "Connection: close\r\nContent-Length: $data_len\r\n",
                'content' => $data_url
            )
        );

        $contexto = stream_context_create($opcoes);

        // Abre el fichero usando las cabeceras HTTP establecidas arriba
        $fichero = json_decode(file_get_contents('https://www.googleapis.com/urlshortener/v1/url', false, $contexto));
        /**
         * {
         * "kind": "urlshortener#url",
         * "id": "http://goo.gl/fbsS",
         * "longUrl": "http://www.google.com/"
         * }
         */
        return $fichero->id;
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