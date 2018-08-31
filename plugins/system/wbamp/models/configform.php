<?php

/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author       Yannick Gaultier
 * @copyright    (c) Yannick Gaultier - Weeblr llc - 2017
 * @package      wbAmp
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version      1.12.0.790
 * @date        2018-05-16
 */

defined('_JEXEC') or die();

class WbampModel_Configform
{
	/**
	 * Global white list
	 *
	 * https://github.com/ampproject/amphtml/blob/master/spec/amp-tag-addendum.md
	 *
	 * @var array
	 */
	private $_tagsWhiteList = array(
		/* 'html', 'head','title','link','style' not in body */
		'meta', 'link',
		'body', 'article', 'section', 'nav', 'aside', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'footer', 'address',
		'p', 'hr', 'pre', 'blockquote', 'ol', 'ul', 'li', 'dl', 'dt', 'dd', 'figure', 'figcaption', 'div', 'main',
		'a', 'em', 'strong', 'small', 's', 'cite', 'q', 'dfn', 'abbr', 'data', 'time', 'code', 'var', 'samp', 'kbd', 'sub', 'sup', 'i', 'b', 'u', 'mark', 'ruby', 'rb', 'rt', 'rtc', 'rp', 'bdi', 'bdo', 'span', 'br', 'wbr',
		'ins', 'del',
		'source',
		'svg', 'g', 'path', 'glyph', 'glyphref', 'marker', 'view', 'circle', 'line', 'polygon', 'polyline', 'rect', 'text', 'textpath', 'tref', 'tspan', 'clippath', 'filter', 'lineargradient', 'radialgradient', 'mask', 'pattern', 'vkern', 'hkern', 'defs', 'use', 'symbol', 'desc', 'title',
		'table', 'caption', 'colgroup', 'col', 'tbody', 'thead', 'tfoot', 'tr', 'td', 'th',
		'button',
		'script',
		'noscript',
		'acronym', 'big', 'center', 'dir', 'hgroup', 'listing', 'multicol', 'nextid', 'nobr', 'spacer', 'strike', 'tt', 'xmp',
		'o:p',
		'amp-ad', 'amp-access', 'amp-accordion', 'amp-analytics', 'amp-anim', 'amp-audio', 'amp-brid-player', 'amp-brightcove', 'amp-carousel', 'amp-dailymotion', 'amp-dynamic-css-classes', 'amp-embed', 'amp-facebook', 'amp-fit-text', 'amp-font', 'amp-iframe', 'amp-image-lightbox', 'amp-img', 'amp-instagram', 'amp-install-serviceworker', 'amp-kaltura-player', 'amp-lightbox', 'amp-list', 'amp-mustache', 'amp-pinterest', 'amp-pixel', 'amp-reach-player', 'amp-slides', 'amp-social-share', 'amp-soundcloud', 'amp-springboard-player', 'amp-twitter', 'amp-user-notification', 'amp-video', 'amp-vimeo', 'amp-vine', 'amp-youtube',
		// form support
		'form',
		'input',
		'textarea',
		'select',
		'option',
		'fieldset',
		'label',
		'template'
	);

	/**
	 * HTML global attributes
	 *
	 * @var array
	 */
	private $_globalAttributes = array(
		'itemid', 'itemprop', 'itemref', 'itemscope', 'itemtype',
		'class', 'id', 'title', 'tabindex', 'dir', 'draggable', 'lang', 'accesskey', 'translate',
		'role',
		'placeholder', 'fallback'
	);

	/**
	 * http://microformats.org/wiki/existing-rel-values
	 * @var array
	 */
	private $_relWhiteList = array(
		'accessibility',
		'alternate',
		'apple-touch-icon',
		'apple-touch-icon-precomposed',
		'apple-touch-startup-image',
		'appendix',
		'archived',
		'archive',
		'archives',
		'attachment',
		'author',
		'bibliography',
		'category',
		'cc:attributionurl',
		'chapter',
		'chrome-webstore-item',
		'cite',
		'code-license',
		'code-repository',
		'colorschememapping',
		'comment',
		'content-license',
		'content-repository',
		'contents',
		'contribution',
		'copyright',
		'designer',
		'directory',
		'discussion',
		'dofollow',
		'edit-time-data',
		'EditURI',
		'endorsed',
		'fan',
		'feed',
		'file-list',
		'follow',
		'footnote',
		'galeria',
		'galeria2',
		'generator',
		'glossary',
		'group',
		'help',
		'home',
		'homepage',
		'hub',
		'icon',
		'image_src',
		'in-reply-to',
		'index',
		'indieauth',
		'introspection',
		'issues',
		'its-rules',
		'jslicense',
		'license',
		'lightbox',
		'made',
		'map',
		'me',
		'member',
		'meta',
		'micropub',
		'microsummary',
		'next',
		'nofollow',
		'noreferrer',
		'ole-object-data',
		'original-source',
		'owns',
		'p3pv1',
		'payment',
		'pgpkey',
		'pingback',
		'prettyphoto',
		'privacy',
		'pronounciation',
		'profile',
		'pronunciation',
		'publisher',
		'prev',
		'previous',
		'referral',
		'related',
		'rendition',
		'replies',
		'reply-to',
		'schema.dc',
		'schema.DCTERMS',
		'search',
		'section',
		'service',
		'service.post',
		'shortcut',
		'shortlink',
		'source',
		'sidebar',
		'sitemap',
		'sponsor',
		'start',
		'status',
		'subsection',
		'syndication',
		'tag',
		'themedata',
		'timesheet',
		'toc',
		'token_endpoint',
		'top',
		'trackback',
		'transformation',
		'unendorsed',
		'up',
		'user',
		'vcalendar-parent',
		'vcalendar-sibling',
		'webmention',
		'wikipedia',
		'wlwmanifest',
		'yandex-tableau-widget'
	);

	private $_perTagAttrDefaultWhiteList = array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__');

	/**
	 * Partial per tag white list for attributes
	 * Complete per tag, but only some tags are included
	 *
	 * @var array
	 */
	private $_perTagAttrWhiteList = array(
		'a'          => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'href', 'hreflang', 'target', 'rel', 'name', 'download', 'media', 'type', 'border'),
		'audio'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'autoplay', 'controls', 'loop', 'muted', 'preload', 'src'),
		'bdo'        => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'dir'),
		'blockquote' => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'cite'),
		'button'     => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'disabled', 'name', 'type', 'value', 'on'),
		'caption'    => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'align'),
		'col'        => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'span'),
		'colgroup'   => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'align'),
		'del'        => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'cite', 'datetime'),
		'img'        => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'alt', 'border', 'height', 'ismap', 'longdesc', 'src', 'srcset', 'width'),
		'ins'        => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'cite', 'datetime'),
		'li'         => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'value'),
		'link'       => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'crossorigin', 'href', 'hreflang', 'media', 'rel', 'type'),
		// http-equiv forbidden on meta
		'meta'       => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'charset', 'content', 'name'),
		'ol'         => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'reversed', 'start', 'type'),
		'q'          => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'cite'),
		'section'    => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'expanded'),
		'script'     => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'type'),
		'source'     => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'sizes', 'src', 'type'),
		'svg'        => array('__wbamp_any__'),
		'table'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'align', 'border', 'bgcolor', 'cellpadding', 'cellspacing', 'width'),
		'tbody'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__'),
		'td'         => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'colspan', 'headers', 'rowspan', 'align', 'bgcolor', 'height', 'valign', 'width'),
		'tfoot'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__'),
		'th'         => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'abbr', 'colspan', 'headers', 'rowspan', 'scope', 'sorted', 'align', 'bgcolor', 'height', 'valign', 'width'),
		'thead'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__'),
		'tr'         => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'align', 'bgcolor', 'height', 'valign'),
		'video'      => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'autoplay', 'controls', 'height', 'loop', 'muted', 'poster', 'preload', 'src', 'width'),

		'amp-ad'             => array('__wbamp_any__'),
		'amp-anim'           => array('__wbamp_any__'),
		'amp-audio'          => array('__wbamp_any__'),
		'amp-carousel'       => array('__wbamp_any__'),
		'amp-fit-text'       => array('__wbamp_any__'),
		'amp-font'           => array('__wbamp_any__'),
		'amp-iframe'         => array('__wbamp_any__'),
		'amp-image-lightbox' => array('__wbamp_any__'),
		'amp-img'            => array('__wbamp_any__'),
		'amp-instagram'      => array('__wbamp_any__'),
		'amp-lightbox'       => array('__wbamp_any__'),
		'amp-pixel'          => array('__wbamp_any__'),
		'amp-twitter'        => array('__wbamp_any__'),
		'amp-vine'           => array('__wbamp_any__'),
		'amp-video'          => array('__wbamp_any__'),
		'amp-youtube'        => array('__wbamp_any__'),

		// form support
		'div'                => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'submit-success',
			'submit-error',
			'overflow'
		),
		'form'               => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'accept',
			'accept-charset',
			'action',
			'action-xhr',
			'autocomplete',
			'custom-validation-reporting',
			'enctype',
			'name',
			'novalidate',
			'method',
			'on',
			'target'
		),
		'input'              => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'accept',
			'autocomplete',
			'autofocus',
			'checked',
			'disabled',
			'height',
			'inputmode',
			'list',
			'max',
			'maxlength',
			'min',
			'minlength',
			'multiple',
			'name',
			'pattern',
			'readonly',
			'required',
			'selectiondirection',
			'size',
			'spellcheck',
			'step',
			'type',
			'value',
			'width'
		),
		'textarea'           => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'autocomplete',
			'autofocus',
			'cols',
			'disabled',
			'maxlength',
			'minlenght',
			'name',
			'readonly',
			'required',
			'rows',
			'selectiondirection',
			'selectionend',
			'selectionstart',
			'spellcheck',
			'wrap'
		),
		'select'             => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'autofocus',
			'disabled',
			'multiple',
			'name',
			'required',
			'selected',
			'size',
			'on'
		),
		'optgroup'           => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'disabled',
			'label'
		),
		'option'             => array(
			'__wbamp_global__',
			'__wbamp_data__',
			'__wbamp_aria__',
			'disabled',
			'label',
			'selected',
			'value'
		),
		'fieldset'           => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'disabled', 'name'),
		'legend'             => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__'),
		'label'              => array('__wbamp_global__', '__wbamp_data__', '__wbamp_aria__', 'for'),
		'template'           => array('type'),
	);

	/**
	 * Attributes that must be removed, but only
	 * on some tags
	 *
	 * @var array
	 */
	private $_perTagAttrBlackList = array(
		'article' => array('itemtype'),
		'aside'   => array('itemtype'),
		'section' => array('itemtype'),
	);

	/**
	 * Invalid protocols for href, src
	 * Attribute to be removed
	 *
	 * @var array
	 */
	private $_protocolsDef = array(
		'a.href'    => array(
			'allowed' => array(
				'ftp',
				'http',
				'https',
				'mailto',
				'fb-messenger',
				'intent',
				'skype',
				'sms',
				'snapchat',
				'tel',
				'tg',
				'threema',
				'twitter',
				'viber',
				'whatsapp'
			),
		),
		'link.href' => array(
			'allowed' => array(
				'http',
				'https'
			),
		),
	);

	/**
	 * Globally invalid attributes,
	 * Attribute to be removed
	 *
	 * @var array
	 */
	private $_invalidAttributes = array(
		'style'
	);

	/**
	 * Some tags are allowed only within others
	 * Currently only checking direct parent
	 * @var array
	 */
	private $_tagMandatoryParents = array(
		'script' => array
		(
			'forbidden_parents' => array(),
			'mandatory_parents' => array('amp-analytics', 'amp-social-share')
		)
	);
	/**
	 * Some tags may be required to have one or more
	 * attributes. They can either be removed if
	 * an attribute is missing, or the attr can
	 * be added with a default value
	 *
	 * @var array
	 */
	private $_tagMandatoryAttr = array(
		'script' => array
		(
			'type' => array(
				'action'    => 'remove_tag', // add | remove_tag
				'add_value' => ''
			)
		),
		'form'   => array
		(
			'target' => array(
				'action'    => 'add', // add | remove_tag
				'add_value' => '_blank'
			)
		),

	);

	/**
	 * Attribute is valid but must have specific values
	 * Attribute value is enforced
	 *
	 * @var array
	 */
	private $_attrForcedValue = array(
		'a.target' => array
		(
			'allow'        => array('_blank', '_self'),
			'forced_value' => '_blank'
		)
	);

	/**
	 * Attribute is valid but must have a specific value
	 * Attribute is removed if incorrect value
	 *
	 * @TODO: remove $_attrForcedValue rules, which can now
	 * be expressed using $_attrMandatoryValue
	 *
	 * @var array
	 */
	private $_attrMandatoryValue = array(
		'script.type'  => array
		(
			'processed_values' => array(
				'application/ld+json' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'application/json'    => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				)
			),
			'other_values'     => array(
				'action'       => 'remove_tag', // allow | replace | remove_attr | remove_tag
				'replace_with' => ''
			)
		),
		'a.type'       => array
		(
			'processed_values' => array(
				'text/html' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				)
			),
			'other_values'     => array(
				'action'       => 'remove_attr', // allow | replace | remove_attr | remove_tag
				'replace_with' => ''
			)
		),
		'a.href'       => array
		(
			'processed_values' => array(
				'void'     => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				),
				'void(0)'  => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				),
				'void(0);' => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				),
				'Void'     => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				),
				'Void(0)'  => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				),
				'Void(0);' => array(
					'action'       => 'replace', // allow | replace | remove_attr | remove_tag
					'replace_with' => '#0'
				)
			),
			'empty'            => array(
				'action'       => 'replace', // allow | replace | remove_attr | remove_tag
				'replace_with' => '#0'
			)
		),
		'meta.charset' => array
		(
			'other_values' => array(
				'action'       => 'remove_tag', // allow | replace | remove_attr | remove_tag
				'replace_with' => ''
			),
			'empty'        => array(
				'action' => 'remove_tag', // allow | replace | remove_attr | remove_tag
			)
		),
		'table.border' => array
		(
			'processed_values' => array(
				'0' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'1' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				)
			),
			'other_values'     => array(
				'action'       => 'remove_attr', // allow | replace | remove_attr | remove_tag
				'replace_with' => ''
			)
		),

		'input.type' => array
		(
			'processed_values' => array(
				'checkbox'       => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'color'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'date'           => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'datetime-local' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'email'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'hidden'         => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'month'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'number'         => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'radio'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'range'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'reset'          => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'search'         => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'submit'         => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'tel'            => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'text'           => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'time'           => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'url'            => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'week'           => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
			),
			'other_values'     => array(
				'action'       => 'remove_tag', // allow | replace | remove_attr | remove_tag
				'replace_with' => ''
			)
		),

		'form.target' => array
		(
			'processed_values' => array(
				'_blank' => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				),
				'_top'   => array(
					'action'       => 'allow', // allow | replace | remove_attr | remove_tag
					'replace_with' => ''
				)
			),
			'other_values'     => array(
				'action'       => 'replace', // allow | replace | remove_attr | remove_tag
				'replace_with' => '_blank'
			),
			'empty'            => array(
				'action'       => 'replace', // allow | replace | remove_attr | remove_tag
				'replace_with' => '_blank'
			)
		),
	);

	/**
	 * Per tag list of invalid values of some attributes. Attribute can be removed or value
	 * replaced
	 *
	 * @var array
	 */
	private $_attrForbiddenValue = array(
		'div.itemtype' => array(
			'http://schema.org/Article'      => array(
				'action'       => 'remove', // replace | remove
				'replace_with' => ''
			),
			'http://schema.org/NewsArticle'  => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'http://schema.org/BlogPosting'  => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'http://schema.org/Blog'         => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'https://schema.org/Article'     => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'https://schema.org/NewsArticle' => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'https://schema.org/BlogPosting' => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'https://schema.org/Blog'        => array(
				'action'       => 'remove',
				'replace_with' => ''
			)
		),

		// note: removing the rel attribute of a link tag
		// will cause this tag to be removed later on
		// as rel is a required attribute
		'link.rel'     => array(
			'stylesheet' => array(
				'action'       => 'remove', // replace | remove
				'replace_with' => ''
			),
			'preconnect' => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'prerender'  => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
			'prefetch'   => array(
				'action'       => 'remove',
				'replace_with' => ''
			),
		),
	);

	/**
	 * List of article types used
	 * as default values for documents
	 * Not used to whitelist, to allow
	 * for user customization
	 *
	 * @var array
	 */
	private $_documentTypes = array(
		'article'    => 'Article',
		'blog'       => 'BlogPosting',
		'news'       => 'NewsArticle',
		'photograph' => 'Photograph',
		'recipe'     => 'Recipe',
		'review'     => 'Review',
		'webpage'    => 'WebPage'
	);

	/**
	 * WIdth and height required for a publisher logo
	 *
	 * @var array
	 */
	private $_publisherLogoSize = array(
		'width'  => 600,
		'height' => 60
	);

	/**
	 * Minimal width for a page image
	 *
	 * @var int
	 */
	private $_pageImageMinWidth = 1200;

	/**
	 * Minimal pixels count for a page image
	 *
	 * @var int
	 */
	private $_pageImageMinPixels = 800000;

	/**
	 * Minimal width for a page image
	 *
	 * @var int
	 */
	private $_pageImageTypes = array('jpg', 'png', 'gif');

	/**
	 * Max length of json-ld headline
	 * @var int
	 */
	private $_headlineMaxLength = 110;

	private $_defaulCleanupRegexp = "
; ---------- RegularLabs Sourcerer ---------
#{source[^}]*}.*{/source}#iuUs => \"\"
; ---------- RegularLabs Slider ---------
#{slider[^}]*}#iuUs  => \"<br />\"
#{/slider[^}]*}#iuUs => \"<br />\"
; ---------- RegularLabs Tabs ---------
#{tab[^}]*}#iuUs  => \"<br />\"
#{/tab[^}]*}#iuUs => \"<br />\"
; ---------- RegularLabs Modals ---------
#{modal[^}]*}#iuUs  => \"\"
#{/modal[^}]*}#iuUs => \"\"
; ---------- RegularLabs Modules Anywhere ---------
#{module[^}]*}#iuUs  => \"\"
; ---------- RegularLabs Snippets ---------
#{snippet[^}]*}#iuUs  => \"\"
; ---------- RegularLabs Tips ---------
#{tip[^}]*}#iuUs  => \"\"
; ---------- RSForm ---------
#{rsform[^}]*}#iuUs  => \"\"
; ---------- Chronoforms ---------
#{chronoforms[^}]*}.*{/chronoforms}#iuUs => \"\"
#{chronoforms5[^}]*}.*{/chronoforms5}#iuUs => \"\"
; ---------- Yootheme WidgetKit ---------
#\[widgetkit[^\]]*\]#iuUs  => \"\"
; ---------- Alledia Simple Image gallery ---------
#{gallery[^}]*}.*{/gallery}#iuUs => \"\"
; ---------- PhocaGallery ---------
#{phocagallery[^}]*}#iuUs  => \"\"
";

	/**
	 * Magic method to fetch a config value directly
	 * or possibly through remote configuration
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		switch ($name)
		{
			// remote value will overwrite hardcoded value
			default:
				$prop = '_' . $name;
				if (property_exists($this, $prop))
				{
					return $this->$prop;
				}
				else
				{
					return null;
				}
				break;
		}
	}

	/**
	 * Magic method to override items
	 * Used for testing only
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return $this
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			// remote value will overwrite hardcoded value
			default:
				$prop = '_' . $name;
				$this->$prop = $value;
				break;
		}

		return $this;
	}

	/**
	 * Magic method to find if a config value
	 * exists in this config object
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __isset($name)
	{
		$prop = '_' . $name;
		return isset($this->$prop);
	}
}

