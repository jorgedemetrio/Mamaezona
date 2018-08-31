<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
header("Refresh:45");
/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');
//header("Cache-Control: private, max-age=10800, pre-check=10800");
//header("Pragma: private");
//header("Expires: " . date(DATE_RFC822,strtotime("30 day")));

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'async'=>true, 'relative' => true), array('async'=>'1'));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'async'=>true, 'relative' => true, 'conditional' => 'lt IE 9'), array('async'=>'1'));

// Add Stylesheets
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'media' => 'nope!', 'onload'=>"this.media='all'", 'relative' => true), array('async'=>'1'));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $this->params->get('googleFontName'),array('media' => 'nope!', 'onload'=>"this.media='all'"));
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . str_replace('+', ' ', $this->params->get('googleFontName')) . "', sans-serif;
	}");
}

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true, 'media' => 'nope!', 'onload'=>"this.media='all'"));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction, array('media' => 'nope!', 'onload'=>"this.media='all'"));

// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<?php
// JFactory::getDocument()->addScript(JURI::base( true  ).'/components/com_mamaezona/assets/js/jquery.mask.min.js',array(),array('async'=>'1'));
$scripts = JFactory::getDocument()->_scripts;

foreach($scripts as &$script){
	$script['async']=1;
	$script['options']['async']=1;
}
//print_r($scripts);die(); 

//print_r($scripts);die();
  
$scripts = JFactory::getDocument()->_styleSheets;
foreach($scripts as &$script){
	$script['media']='nope!';
	$script['onload'] = "this.media='all'";
	$script['options']['media']='nope!';
	$script['options']['onload'] = "this.media='all'";
}
$scripts=null;


?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />





<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/pt_BR/fbevents.js');
  fbq('init', '243443112942162');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=243443112942162&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->



<!-- End Facebook Pixel Code -->
<meta property="fb:app_id" content="2736072396620216" />
<meta name="google-site-verification" content="lRb670UUByIhiKY6gDCT9Fii6yaL_yUqHqdr7O5WOjg" />
<meta name="msvalidate.01" content="A83539FBF6366677995E444F0AE709ED" />
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
<meta name="msvalidate.01" content="A83539FBF6366677995E444F0AE709ED" />


<?php if(!(strpos($_SERVER['SERVER_NAME'],'www')===false)): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108764175-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-108764175-1');
  <?php 
		  $usuario = JFactory::getUser();
		if ( isset($usuario) && !is_null($usuario) && $usuario != null && $usuario->id > 0) {
		      echo "gtag('set', {'user_id': '".$usuario->id."'}); ";
		  }
		  ?>
</script>
<?php else : ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108764175-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-108764175-2');
  <?php 
  $usuario = JFactory::getUser();
  if (! isset($usuario) || is_null($usuario) || $usuario == null || $usuario->id <= 0) {
      echo "gtag('set', {'user_id': '".$usuario->id."'}); ";
  }
  ?>
  
</script>
<?php endif; ?>


</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
<script>

//FB.AppEvents.logPageView(); 


      window.fbMessengerPlugins = window.fbMessengerPlugins || {        init: function () {          FB.init({            appId            : '1678638095724206',            autoLogAppEvents : true,            xfbml            : true,            version          : 'v2.10'          });        }, callable: []      };      window.fbAsyncInit = window.fbAsyncInit || function () {        window.fbMessengerPlugins.callable.forEach(function (item) { item(); });        window.fbMessengerPlugins.init();      };      setTimeout(function () {        (function (d, s, id) {          var js, fjs = d.getElementsByTagName(s)[0];          if (d.getElementById(id)) { return; }          js = d.createElement(s);          js.id = id;          js.src = "//connect.facebook.net/pt_BR/sdk.js";          fjs.parentNode.insertBefore(js, fjs);        }(document, 'script', 'facebook-jssdk'));      }, 0);      </script>            <div        class="fb-customerchat"        page_id="1795128250511015"        ref="">      </div>
	<!-- Body -->
	<div class="body" id="top">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<!-- Header -->
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
						<?php if ($this->params->get('sitedescription')) : ?>
							<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
						<?php endif; ?>
					</a>
					<div class="header-search pull-right">
						<jdoc:include type="modules" name="position-0" style="none" />
					</div>
				</div>
			</header>
			<?php if ($this->countModules('position-1')) : ?>
				<nav class="navigation" role="navigation">
					<div class="navbar pull-left">
						<a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
							<span class="element-invisible"><?php echo JTEXT::_('TPL_PROTOSTAR_TOGGLE_MENU'); ?></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
					<div class="nav-collapse">
						<jdoc:include type="modules" name="position-1" style="none" />
					</div>
				</nav>
			<?php endif; ?>
			<jdoc:include type="modules" name="banner" style="xhtml" />
			<div class="row-fluid">
				<?php if ($position7ModuleCount) : ?>
					<div id="aside" class="span3">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="well" />
						<!-- End Right Sidebar -->
					</div>
				<?php endif; ?>
				<main id="content" role="main" class="<?php echo $span; ?>">
					<!-- Begin Content -->
					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
 
					<div class="clearfix"></div>
					<jdoc:include type="modules" name="position-2" style="none" />
					<!-- End Content -->
				</main>
				<?php if ($position8ModuleCount) : ?>
					<!-- Begin Sidebar -->
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="position-8" style="xhtml" />
						</div>
					</div>
					<!-- End Sidebar -->

				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
			<p class="pull-right">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a>
			</p>
			<p>
				&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
			</p>
		</div>
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
