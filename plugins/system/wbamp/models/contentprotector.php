<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2017
 * @package     wbAmp
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     1.12.0.790
 * @date        2018-05-16
 */

// Security check to ensure this file is being included by a parent file.
defined('_JEXEC') || die;

/**
 * Class WbampModel_Contentprotector
 *
 * Protects some parts of the page from the AMP converter. The content is removed from the page (and replaced with a
 * tag) then can be put back at a later stage, after the initial conversion to AMP.
 *
 * Typical usage, inside a template, before AMPlification:
 *
 * echo $contentProtector->protect( $someContent);
 *
 * Then after AMPlification has taken place (and all invalid tags have been removed, modified):
 *
 * $pageContent = $contentProtector->injectProtectedContent($pageContent);
 *
 * where $pageContent is the AMPlified content of the page.
 *
 * In addition, a specific AMP processor can be passed during the initial call, so that
 * the protected content is also AMPlified, but maybe with a different set of rules:
 *
 * echo $contentProtector->protect(
 * $someContent, array(
 *   $this->get('amp_form_processor'),
 *   'convert'
 * ));
 *
 * When injecting back each bit of protected content, it will be processed
 * by using the callback provided to protect(). The callback receives some string content
 * and must return the processed content as a string.
 *
 * Currently, we use this to allow forms in our comment or search implementation,
 * while still removing all forms and input in other parts of the page.
 */
class WbampModel_Contentprotector
{
	private $protectedContent = array();

	/**
	 * Store a piece of content, and an optional processor, returning a
	 * unique tag. This tag should be echoed in the page content, to be later replaced
	 * back by the stored content.
	 *
	 * @param string   $content
	 * @param callback $processor
	 *
	 * @return string
	 */
	public function protect($content, $processor = null)
	{
		if (empty($content))
		{
			return '';
		}

		// build hash and store original content
		$id = md5($content);
		$tag = '{weeblramp_protected_' . $id . '}';
		$this->protectedContent[$tag] =
			array(
				'content' => $content,
				'processor' => $processor
			);

		// return an id, which we can later on replace with the actual content
		return $tag;
	}

	/**
	 * Inject back one or all protected content into a page content (a string)
	 *
	 * @param string $pageContent The current page content
	 * @param string $tag A protected content tag found in content
	 *
	 * @return string
	 */
	public function injectProtectedContent($pageContent, $tag = null)
	{
		if (empty($tag))
		{
			foreach ($this->protectedContent as $contenTag => $record)
			{
				$pageContent = $this->inject($pageContent, $contenTag, $record);
			}
		}
		else if (!empty($this->protectedContent[$tag]))
		{
			$pageContent = $this->inject($pageContent, $tag, $this->protectedContent[$tag]);
		}

		return $pageContent;
	}

	/**
	 * Put back a single bit of protected content in a page
	 *
	 * @param string $pageContent The full page content
	 * @param string $tag The tag to be replaced, as obtained with the protect() method
	 * @param array  $record The convert() record, holding content and an optional processor
	 *
	 * @return mixed
	 */
	private function inject($pageContent, $tag, $record)
	{
		$processor = wbArrayGet($record, 'processor');
		if (empty($processor) || !is_callable(array($processor, 'convert')))
		{
			$processedContent = $record['content'];
		}
		else
		{
			$processedContent = $processor->convert($record['content']);
		}

		$pageContent = str_replace($tag, $processedContent, $pageContent);

		return $pageContent;
	}
}
