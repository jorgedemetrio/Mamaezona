<?php
// no direct access
defined( '_JEXEC' ) || die;

class plgContentDemeter_content_plugin extends JPlugin
{
	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;
	function onContentAfterTitle($context, &$article, &$params, $limitstart)
	{

		if($context!='com_content.article'){
			return;
		}
		
		
		$document = JFactory::getDocument();
		
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$baseURL = $protocol.$_SERVER['SERVER_NAME'];
		$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];


		
		$document = JFactory::getDocument();
		$id_facebook = $this->params['id_facebook'];
		$script ='<div id="fb-root"></div>';


		
		//<div id="fb-root"></div>
		$document->addScriptDeclaration("  window.fbAsyncInit = function() {
    FB.init({
      appId      : '".$id_facebook."',
      xfbml      : true,
      version    : 'v2.11'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = 'https://connect.facebook.net/pt_BR/sdk.js';
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));");
		
		$document->addCustomTag('<link rel="me"
				href="https://twitter.com/twitterdev"
				>');

		
		$textoTwitter = urlencode($article->title) . ' em ' . $urlLocal;
		
		$document->addCustomTag('<script src="https://apis.google.com/js/platform.js" async defer>
		{lang: "pt-BR"}
		</script>');

		return $script.'<div class="fb-like" 
    data-href="'.$urlLocal.'" 
    data-layout="standard" 
    data-action="like" 
    data-show-faces="true">
  </div><a class="twitter-share-button" target="_new"
  href="https://twitter.com/intent/tweet?text='.$textoTwitter.'"
  data-size="large">
	Tweet</a><g:plusone></g:plusone><div class="g-plus" data-action="share"></div>';
	}
	
	function onContentAfterDisplay($context, &$article, &$params, $limitstart)
	{
		if($context!='com_content.article'){
			return;
		}
		$tamanho = $this->params['tamanho'];

		
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$baseURL = $protocol.$_SERVER['SERVER_NAME'];
		$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];

		
		
		
		
		return '<div class="fb-comments" data-href="'. $urlLocal . '" data-width="'.$tamanho .'" style="margin: 0 auto;">';
	}
}