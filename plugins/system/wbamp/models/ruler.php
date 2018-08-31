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

// no direct access
defined('_JEXEC') or die;

class WbampModel_Ruler
{
	const RULES_COUNT = 5;

	private $_request      = null;
	private $_manager      = null;
	private $_params       = null;
	private $_joomlaConfig = null;

	/**
	 * Stores request data
	 *
	 * @param $request the GET query vars from the request
	 * @param $manager the Manager object which handled the request
	 * @param $params User set params
	 * @param $joomlaConfig Global Joomla config
	 */
	public function __construct($request, $manager, $params, $joomlaConfig)
	{
		JPluginHelper::importPlugin('wbamp');
		$this->_request =
			/**
			 * Filter the request input object to allow some
			 * variables name/content substitution.
			 *
			 * @api
			 * @package wbAMP\filter\data
			 * @var wbamp_amplify_ruler_input
			 * @since   1.12.0
			 *
			 * @param JInput $input The current request input object.
			 *
			 * @return string
			 */
			ShlHook::filter(
				'wbamp_amplify_ruler_input',
				$request
			);

		$this->_manager = $manager;
		$this->_params = $params;
		$this->_joomlaConfig = $joomlaConfig;
	}

	/**
	 * Runs in sequence various checks based on URL query vars
	 * to allow or not creation of AMP version of pages
	 *
	 * returns true if passed, false if not passed and null if neutral
	 */
	public function checkRules()
	{
		$passUserRules =
			$this->checkComContentRules() === true
			||
			$this->checkComContactRules() === true
			||
			$this->checkComponentsRules() === true;

		$passUserRules =
			/**
			 * Filter the json-ld data to be included on any AMP page.
			 *
			 * @api
			 * @package wbAMP\filter\data
			 * @var wbamp_pass_amplify_rules
			 * @since   1.9.3
			 *
			 * @param bool   $passUserRules True if the current page should be AMPlified.
			 * @param JInput $input The current request input object.
			 *
			 * @return string
			 */
			ShlHook::filter(
				'wbamp_pass_amplify_rules',
				$passUserRules,
				$this->_request
			);

		return $passUserRules;
	}

	/**
	 * Check a rule for com_content
	 *
	 * returns true if passed, false if not passed and null if neutral
	 *
	 * @return bool|null
	 */
	public function checkComContentRules()
	{
		$pass = null;
		$component = $this->_request->getCmd('option');
		if (empty($component) || $component != 'com_content')
		{
			return $pass;
		}

		// is rule disabled?
		if (!$this->_params->get('_com_content_rule_enable', 1))
		{
			return $pass;
		}

		$ruleName = '_com_content_';

		if (!$this->checkRule(
			'Itemid',
			$this->_params->get($ruleName . 'itemid', array('__any__')),
			$this->_params->get($ruleName . 'itemid_exclude', array())
		)
		)
		{
			// one applicable rule failed, global fail
			return false;
		}
		else if (!($this->checkRule('view', $this->_params->get($ruleName . 'view'))))
		{
			// one applicable rule failed, global fail
			return false;
		}
		else if (!$this->checkComContentCategoryRule(
			$this->_params->get('_com_content_categories', array('__any__')),
			$this->_params->get('_com_content_categories_exclude', array())
		)
		)
		{
			// one applicable rule failed, global fail
			return false;
		}
		else if (!$this->checkRule(
			'id',
			$this->_params->get('_com_content_item_id'),
			$this->_params->get('_com_content_item_id_exclude', array())
		)
		)
		{
			// one applicable rule failed, global fail
			return false;
		}
		else
		{
			// applicable rule passed
			$pass = true;
		}

		return $pass;
	}

	/**
	 * Check a rule for com_content
	 *
	 * returns true if passed, false if not passed and null if neutral
	 *
	 * @return bool|null
	 */
	public function checkComContactRules()
	{
		$pass = null;
		$component = $this->_request->getCmd('option');
		if (empty($component) || $component != 'com_contact')
		{
			return $pass;
		}

		// hard coded single on/off switch
		$enabled = $this->_params->get('_com_contact_enable', false);
		if (!$enabled)
		{
			return false;
		}

		// generic rules
		$ruleName = '_com_contact_';

		if (!$this->checkRule(
			'Itemid',
			$this->_params->get($ruleName . 'itemid', array('__any__')),
			$this->_params->get($ruleName . 'itemid_exclude', array())
		)
		)
		{
			// one applicable rule failed, global fail
			return false;
		} // we only AMPlify the contact view
		else if (!($this->checkRule('view', 'contact')))
		{
			// one applicable rule failed, global fail
			return false;
		}
		// not supported yet, and probably never
		//else if (!($this->checkComContactCategoryRule($this->_params->get('_com_contact_categories'))))
		//{
		//	// one applicable rule failed, global fail
		//	return false;
		//}
		//else if (!($this->checkRule('id', $this->_params->get('_com_contact_item_id'))))
		//{
		//	// one applicable rule failed, global fail
		//	return false;
		//}
		else
		{
			// applicable rule passed
			$pass = true;
		}

		return $pass;
	}

	/**
	 * Check a category rule for com_content, based on catid query var.
	 * Relies on default checkRule method, but compute the catid if none
	 * is supllied in the query, based on the actual article id
	 *
	 * @param        $allowedValuesList
	 * @param string $varDisallowedValuesList
	 *
	 * @return bool
	 * @internal param $rule
	 */
	protected function checkComContentCategoryRule($allowedValuesList, $varDisallowedValuesList = array())
	{
		$allowedValuesList = is_array($allowedValuesList) ? $allowedValuesList : (array) $allowedValuesList;
		$varDisallowedValuesList = is_array($varDisallowedValuesList) ? $varDisallowedValuesList : (array) $varDisallowedValuesList;

		if (empty($allowedValuesList))
		{
			// no category specified, disallow AMP
			return false;
		}

		// if all categories allowed, and none on the disallowed list, good to go
		if (
			in_array(
				'__any__',
				$allowedValuesList
			)
			&&
			empty($varDisallowedValuesList)
		)
		{
			return true;
		}

		// use query catid if we have one
		$catid = $this->_request->getInt('catid');
		if (empty($catid))
		{
			// no catid, let's try find one from the article id
			$view = $this->_request->getCmd('view');
			$id = $this->_request->getInt('id');
			try
			{
				switch ($view)
				{
					case 'article':
						// @TODO: memoize
						$catid = ShlDbHelper::selectResult('#__content', 'catid', array('id' => $id));
						break;
					case 'category':
					case 'categories':
						$catid = $id;
						break;
				}
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			}
		}

		if (empty($catid))
		{
			// no catid found, only allow if all categories are allowed
			$pass = in_array(
				'__any__',
				$allowedValuesList
			);
		}
		else
		{
			$pass =
				in_array($catid, $allowedValuesList)
				||
				in_array(
					'__any__',
					$allowedValuesList
				);
			$pass = $pass && (
					empty($varDisallowedValuesList)
					||
					!in_array($catid, $varDisallowedValuesList)
				);
		}

		return $pass;
	}

	/**
	 * Execute user-set rules to decide whether an HTML
	 * page should also have an AMP version.
	 *
	 * returns true if passed, false if not passed and null if neutral
	 *
	 * @return bool|null
	 */
	public function checkComponentsRules()
	{
		$pass = null;
		$component = $this->_request->getCmd('option');
		if (empty($component))
		{
			return $pass;
		}

		// iterate over rules
		for ($ruleNb = 1; $ruleNb <= self::RULES_COUNT; $ruleNb++)
		{
			// is rule disabled?
			if (!$this->_params->get('_' . $ruleNb . '_rule_enable', 1))
			{
				return $pass;
			}

			$ruleName = '_' . $ruleNb . '_';
			$ruleComponent = $this->_params->get($ruleName . 'component');
			// view
			if ($ruleComponent != $component)
			{
				// non applicable rule, continue to next rule
				continue;
			}
			else if (!$this->checkRule(
				'Itemid',
				$this->_params->get($ruleName . 'itemid', array('__any__')),
				$this->_params->get($ruleName . 'itemid_exclude', array())
			)
			)
			{
				// one applicable rule failed, global fail
				continue;
			}
			else if (!($this->checkRule('view', $this->_params->get($ruleName . 'view'))))
			{
				// one applicable rule failed, global fail
				continue;
			}
			else if (!($this->checkCategoryRule($ruleName, $this->_params->get($ruleName . 'category_name'), $this->_params->get($ruleName . 'category_values'))))
			{
				// one applicable rule failed, global fail
				continue;
			}
			else if (!($this->checkRule($this->_params->get($ruleName . 'item_name'), $this->_params->get($ruleName . 'item_values'))))
			{
				// one applicable rule failed, global fail
				continue;
			}
			else
			{
				// applicable rule passed
				$pass = true;
			}
		}

		return $pass;
	}

	/**
	 * Checks that a query variable matches a list of values
	 * Values are passed as a comma-separated list
	 * with the following convention:
	 * * means any value is accepted (but the variable must exist)
	 * empty = no value is accepted, the variable must be undefined or empty
	 *
	 * @param $varName
	 * @param $varAllowedValuesList
	 * @param $varDisallowedValuesList
	 *
	 * @return bool
	 */
	private function checkRule($varName, $varAllowedValuesList, $varDisallowedValuesList = '')
	{
		if (empty($varName))
		{
			return true;
		}

		// clean up
		$varValue = $this->_request->getString($varName);

		return $this->executeCheckRule($varValue, $varAllowedValuesList, $varDisallowedValuesList);
	}

	/**
	 * Checks that a query variable matches a list of values
	 * Values are passed as a comma-separated list
	 * with the following convention:
	 * * means any value is accepted (but the variable must exist)
	 * empty = no value is accepted, the variable must be undefined or empty
	 *
	 * @param        $ruleName
	 * @param        $varName
	 * @param        $varAllowedValuesList
	 * @param string $varDisallowedValuesList
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function checkCategoryRule($ruleName, $varName, $varAllowedValuesList, $varDisallowedValuesList = '')
	{
		if (empty($varName))
		{
			return true;
		}

		// now figure out value
		$varValue = $this->_request->getString($varName);
		if (is_null($varValue))
		{
			// the desired category var was not in the request
			// maybe it can be infered from the item id
			$itemVarName = $this->_params->get($ruleName . 'item_name');
			if (!empty($itemVarName))
			{
				$itemId = $this->_request->getString($itemVarName);
				if (!is_null($itemId))
				{
					// we have an item id, find the corresponding category
					$option = $this->_request->getCmd('option', '');
					$eventArgs = array(
						$option,
						$itemId,
						& $varValue,
						$this->_request
					);
					ShlSystem_Factory::dispatcher()
					                 ->trigger('onWbampGetCategoryFromItem', $eventArgs);
				}
			}
		}

		return $this->executeCheckRule($varValue, $varAllowedValuesList, $varDisallowedValuesList);
	}

	/**
	 * Checks that a specific value is within a specified range
	 *
	 * @param $varValue
	 * @param $varAllowedValuesList
	 *
	 * @param $varDisallowedValuesList
	 *
	 * @return bool
	 */
	private function executeCheckRule($varValue, $varAllowedValuesList, $varDisallowedValuesList = '')
	{
		// clean up
		$varAllowedValuesList = is_array($varAllowedValuesList) ? $varAllowedValuesList : ShlSystem_Strings::stringToCleanedArray(($varAllowedValuesList));
		$varDisallowedValuesList = is_array($varDisallowedValuesList) ? $varDisallowedValuesList : ShlSystem_Strings::stringToCleanedArray(($varDisallowedValuesList));

		// if allowed values is empty, query var must be empty
		if (empty($varAllowedValuesList) && !empty($varValue))
		{
			return false;
		}
		else if (empty($varAllowedValuesList))
		{
			return true;
		}

		// To continue, either we have a wildcard allowing all values
		// or the specific request value is allowed
		if (
			!in_array('*', $varAllowedValuesList)
			&&
			!in_array('__any__', $varAllowedValuesList)
			&&
			!in_array($varValue, $varAllowedValuesList)
		)
		{
			return false;
		}

		// last check: the specific request value is not disallowed
		// (this would override a wildcard in the allowed values list)
		$pass = !in_array($varValue, $varDisallowedValuesList);

		return $pass;
	}
}
