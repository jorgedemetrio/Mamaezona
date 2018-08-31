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

class WbampModelFeature_Com_contact implements WbampClass_FormHandlerInterface
{
	const SCOPE = 'com_contact';

	/**
	 * Post a com_contact form, and builds a response object based on result.
	 *
	 * Response format is:
	 *
	 * array(
	 * 'response' => array(
	 * 'status' => 'ok',
	 * 'message' => '',
	 * 'link' => '',
	 * 'debug' => ''
	 * ),
	 * 'headers' => array()
	 * )
	 *
	 * @param array  $response
	 * @param JInput $input
	 * @param JUser  $user
	 *
	 * @return array | null Null if cannot handle, otherwise a response, even if an error occured
	 */
	public function handleAjax($response, $input, $user)
	{
		// check scope, return null if not ours to handle
		if (static::SCOPE != $input->getCmd('scope'))
		{
			return null;
		}

		// post the contact-us form
		$response = $this->processForm($response, $input, $user);

		return $response;
	}

	public function processForm($response, $input, $user)
	{
		// get model and apply workarounds
		$componentPath = JPATH_ROOT . '/components/com_contact';
		JForm::addFormPath($componentPath . '/models/forms');
		JForm::addFieldPath($componentPath . '/models/fields');
		JForm::addFormPath($componentPath . '/model/form');
		JForm::addFieldPath($componentPath . '/model/field');

		JModelLegacy::addIncludePath($componentPath . '/models');
		$model = JModelLegacy::getInstance('contact', 'ContactModel', array('ignore_request' => false));

		$params = JComponentHelper::getParams('com_contact');
		$stub = $input->getString('id');
		$id = (int) $stub;

		// Get the data from POST
		$data = $input->post->get('jform', array(), 'array');
		$contact = $model->getItem($id);

		$params->merge($contact->params);

		// Check for a valid session cookie
		// disabled on AMP

		// Contact plugins
		JPluginHelper::importPlugin('contact');
		$dispatcher = JEventDispatcher::getInstance();

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			$response = array(
				'status' => 'HTTP/1.1 400 Invalid request',
				'response' => array(
					'status' => 'error',
					'message' => $model->getError(),
					'link' => '',
					'debug' => ''
				),
				'headers' => array()
			);

			return $response;
		}

		// disable Captcha temporarily, to allow com_contact to validate
		JFactory::getApplication()->getParams()->set('captcha', 0);
		if (!$model->validate($form, $data))
		{
			$errors = $model->getErrors();

			$ampErrors = array();
			foreach ($errors as $error)
			{
				$errorMessage = $error;

				if ($error instanceof Exception)
				{
					$errorMessage = $error->getMessage();
				}

				$ampErrors[] = array(
					'error_detail' => $errorMessage
				);
			}

			$response = array(
				'status' => 'HTTP/1.1 400 Invalid request',
				'response' => array(
					'status' => 'error',
					'has_errors' => true,
					'errors' => $ampErrors,
					'message' => JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'),
					'link' => '',
					'debug' => ''
				),
				'headers' => array()
			);

			return $response;
		}

		// Validation succeeded, continue with custom handlers
		$results = $dispatcher->trigger('onValidateContact', array(&$contact, &$data));

		foreach ($results as $result)
		{
			if ($result instanceof Exception)
			{
				$response = array(
					'status' => 'HTTP/1.1 400 Invalid request',
					'response' => array(
						'status' => 'error',
						'has_errors' => true,
						'errors' => array($result->getMessage()),
						'message' => JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'),
						'link' => '',
						'debug' => ''
					),
					'headers' => array()
				);

				return $response;
			}
		}

		// Passed Validation: Process the contact plugins to integrate with other applications
		$dispatcher->trigger('onSubmitContact', array(&$contact, &$data));

		// Send the email
		$sent = false;

		if (!$params->get('custom_reply'))
		{
			$sent = $this->_sendEmail($data, $contact, $params->get('show_email_copy'));
		}

		// Set the success message if it was a success
		$response = array(
			'response' => array(
				'status' => 'ok',
				'link' => '',
				'debug' => ''
			),
			'headers' => array()
		);
		if (!($sent instanceof Exception))
		{
			JFactory::getLanguage()->load('com_contact');
			$msg = JText::_('COM_CONTACT_EMAIL_THANKS');
			if ($contact->params->get('redirect'))
			{
				$response['response']['link'] = $contact->params->get('redirect');
				$response['headers']['AMP-Redirect-To'] = $contact->params->get('redirect');
				$response['headers']['Access-Control-Expose-Headers'] = 'AMP-Access-Control-Allow-Source-Origin,AMP-Redirect-To';
			}
		}
		else
		{
			$msg = JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST');
			$response['status'] = 'HTTP/1.1 400 Invalid request';
			$response['response']['status'] = 'error';
			$response['response']['has_errors'] = true;
			$response['response']['errors'] = array('error_detail' => $sent->getMessage());
		}

		$response['response']['message'] = $msg;

		return $response;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   array    $data The data to send in the email.
	 * @param   stdClass $contact The user information to send the email to
	 * @param   boolean  $copy_email_activated True to send a copy of the email to the user.
	 *
	 * @return  boolean  True on success sending the email, false on failure.
	 *
	 * @since   1.6.4
	 */
	private function _sendEmail($data, $contact, $copy_email_activated)
	{
		$app = JFactory::getApplication();

		if ($contact->email_to == '' && $contact->user_id != 0)
		{
			$contact_user = JUser::getInstance($contact->user_id);
			$contact->email_to = $contact_user->get('email');
		}

		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');
		$sitename = $app->get('sitename');

		$name = $data['contact_name'];
		$email = JStringPunycode::emailToPunycode($data['contact_email']);
		$subject = $data['contact_subject'];
		$body = $data['contact_message'];

		// Prepare email body
		$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JUri::base());
		$body = $prefix . "\n" . $name . ' <' . $email . '>' . "\r\n\r\n" . stripslashes($body);

		// Load the custom fields
		if (!empty($data['com_fields']) && $fields = FieldsHelper::getFields('com_contact.mail', $contact, true, $data['com_fields']))
		{
			$output = FieldsHelper::render(
				'com_contact.mail',
				'fields.render',
				array('context' => 'com_contact.mail', 'item' => $contact, 'fields' => $fields)
			);
			if ($output)
			{
				$body .= "\r\n\r\n" . $output;
			}
		}

		$mail = JFactory::getMailer();
		$mail->addRecipient($contact->email_to);
		$mail->addReplyTo($email, $name);
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename . ': ' . $subject);
		$mail->setBody($body);
		$sent = $mail->Send();

		// If we are supposed to copy the sender, do so.

		// Check whether email copy function activated
		if ($copy_email_activated == true && !empty($data['contact_email_copy']))
		{
			$copytext = JText::sprintf('COM_CONTACT_COPYTEXT_OF', $contact->name, $sitename);
			$copytext .= "\r\n\r\n" . $body;
			$copysubject = JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->addReplyTo($email, $name);
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($copysubject);
			$mail->setBody($copytext);
			$sent = $mail->Send();
		}

		return $sent;
	}

}

