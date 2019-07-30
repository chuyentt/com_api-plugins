<?php
/**
 * @package    Com_Api
 * @copyright  Copyright (C) 2009-2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license    GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link       http://www.techjoomla.com
 */
defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_SITE . '/components/com_vbf/models/faqs.php';
require_once JPATH_SITE . '/components/com_vbf/models/faq.php';

/**
 * Faq Resource
 *
 * @since  3.5
 */
class VbfApiResourceFaq extends ApiResource
{
	/**
	 * get Method to get all faq data
	 *
	 * @return  json
	 *
	 * @since  3.5
	 */
	public function get()
	{
		$this->plugin->setResponse($this->getFaqs());
	}

	/**
	 * delete Method to delete faq
	 *
	 * @return  json
	 *
	 * @since  3.5
	 */
	public function delete()
	{
		$this->plugin->setResponse('in delete');
	}

	/**
	 * getFaqs Method to getFaqs data
	 *
	 * @return  array
	 *
	 * @since  3.5
	 */
	public function getFaqs()
	{
		$app = JFactory::getApplication();
		$items = array();
		$faq_id = $app->input->get('id', 0, 'INT');

		$listOrder = $app->input->get('listOrder', 'ASC', 'STRING');

		$faq_obj = new VbfModelFaqs;

		$faq_obj->setState('list.direction', $listOrder);

		if ($limit)
		{
			$faq_obj->setState('list.start', $limitstart);
			$faq_obj->setState('list.limit', $limit);
		}

		// Filter by faq
		if ($faq_id)
		{
			$faq_obj->setState('filter.faq_id', $faq_id);
		}

		$rows = $faq_obj->getItems();

		$num_faqs = $faq_obj->getTotal();
		$data[] = new stdClass;

		foreach ($rows as $subKey => $subArray)
		{
			$data[$subKey]->code = $subArray->code;
			$data[$subKey]->title = $subArray->title;
			$data[$subKey]->content = $subArray->content;
		}

		$obj = new stdclass;
		$result = new stdClass;

		if (count($data) > 0)
		{
			$result->results = $data;
			$result->total = $num_faqs;
			$obj->success = true;
			$obj->data = $result;

			return $obj;
		}
		else
		{
			$obj->success = false;
			$obj->message = 'System does not have items';
		}

		return $obj;
	}

	/**
	 * Post is to create / update faq
	 *
	 * @return  Boolean
	 *
	 * @since  3.5
	 */
	public function post()
	{
		$this->plugin->setResponse($this->CreateUpdateFaq());
	}

	/**
	 * CreateUpdateArticle is to create / upadte faq
	 *
	 * @return  Bolean
	 *
	 * @since  3.5
	 */
	public function CreateUpdateFaq()
	{
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}

		$obj = new stdclass;

		$app = JFactory::getApplication();
		$faq_id = $app->input->get('id', 0, 'INT');

		/* Kiểm tra các trường bắt buộc
		if (empty($app->input->get('code', '', 'STRING')))
		{
			$obj->success = false;
			$obj->message = 'Code is missing';

			return $obj;
		}
		
		if (empty($app->input->get('ntitle', '', 'STRING')))
		{
			$obj->success = false;
			$obj->message = 'Notification title is missing';

			return $obj;
		}

		if (empty($app->input->get('nbody', '', 'STRING')))
		{
			$obj->success = false;
			$obj->message = 'Notification body is missing';

			return $obj;
		}

		if (empty($app->input->get('title', '', 'STRING')))
		{
			$obj->success = false;
			$obj->message = 'Title is Missing';

			return $obj;
		}

		if (empty($app->input->get('content', '', 'STRING')))
		{
			$obj->success = false;
			$obj->message = 'Content is missing';

			return $obj;
		}
		*/

		if ($faq_id)
		{
			$faq = JTable::getInstance('Faq', 'JTable', array());
			$faq->load($faq_id);
			$data = array(
				'state' => $app->input->get('state', '', 'INT'),
				'code' => $app->input->get('code', '', 'STRING'),
				'title' => $app->input->get('title', '', 'STRING'),
				'content' => $app->input->get('content', '', 'STRING')
			);

			// Bind data
			if (!$faq->bind($data))
			{
				$obj->success = false;
				$obj->message = $faq->getError();

				return $obj;
			}
		}
		else
		{
			$faq = JTable::getInstance('Faq', 'JTable', array());
			$faq->state = $app->input->get('state', '', 'INT');
			$faq->code = $app->input->get('code', '', 'STRING');
			$faq->title = $app->input->get('title', '', 'STRING');
			$faq->content = $app->input->get('content', '', 'STRING');
		}

		// Check the data.
		if (!$faq->check())
		{
			$obj->success = false;
			$obj->message = $faq->getError();

			return $obj;
		}

		// Store the data.
		if (!$faq->store())
		{
			$obj->success = false;
			$obj->message = $faq->getError();

			return $obj;
		}

		$result = new stdClass;
		$result->results = $faq;

		$obj->success = true;
		$obj->data = $result;

		return $obj;
	}
}
