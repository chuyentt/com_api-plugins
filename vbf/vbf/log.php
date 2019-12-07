<?php
/**
 * @package    Com_Api
 * @copyright  Copyright (C) 2009-2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license    GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link       http://www.techjoomla.com
 */
defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_SITE . '/components/com_vbf/models/logs.php';
require_once JPATH_SITE . '/components/com_vbf/models/log.php';

/**
 * Log Resource
 *
 * @since  3.5
 */
class VbfApiResourceLog extends ApiResource
{
	/**
	 * get Method to get all log data
	 *
	 * @return  json
	 *
	 * @since  3.5
	 */
	public function get()
	{
		$this->plugin->setResponse($this->getLogs());
	}

	/**
	 * delete Method to delete Log
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
	 * getLogs Method to getLogs data
	 *
	 * @return  array
	 *
	 * @since  3.5
	 */
	public function getLogs()
	{
		$app = JFactory::getApplication();
		$items = array();
		$log_id = $app->input->get('id', 0, 'INT');
		$log_mother_code = $app->input->get('code', '', 'STRING');

		$listOrder = $app->input->get('listOrder', 'ASC', 'STRING');

		$log_obj = new VbfModelLogs;

		$log_obj->setState('list.direction', $listOrder);

		if ($limit)
		{
			$log_obj->setState('list.start', $limitstart);
			$log_obj->setState('list.limit', $limit);
		}

		// Filter by log
		if ($log_id)
		{
			$log_obj->setState('filter.id', $log_id);
		}

		// Filter by log
		if ($log_mother_code)
		{
			$log_obj->setState('filter.mother_code', $log_mother_code);
		}
		
		$rows = $log_obj->getItems();

		$num_logs = $log_obj->getTotal();
		$data[] = new stdClass;

		foreach ($rows as $subKey => $subArray)
		{
			$data[$subKey]->mother_code = $subArray->mother_code;
			$data[$subKey]->start = $subArray->start;
			$data[$subKey]->end = $subArray->end;
			$data[$subKey]->note = $subArray->note;
		}

		$obj = new stdclass;
		$result = new stdClass;

		if (count($data) > 0)
		{
			$result->results = $data;
			$result->total = $num_logs;
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
	 * Post is to create / update log
	 *
	 * @return  Boolean
	 *
	 * @since  3.5
	 */
	public function post()
	{
		$this->plugin->setResponse($this->CreateUpdateLog());
	}

	/**
	 * CreateUpdateArticle is to create / upadte log
	 *
	 * @return  Bolean
	 *
	 * @since  3.5
	 */
	public function CreateUpdateLog()
	{
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}

		$obj = new stdclass;

		$app = JFactory::getApplication();
		$log_id = $app->input->get('id', 0, 'INT');

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

		if ($log_id)
		{
			$log = JTable::getInstance('Log', 'JTable', array());
			$log->load($log_id);
			$data = array(
				'state' => $app->input->get('state', '', 'INT'),
				'mother_code' => $app->input->get('mother_code', '', 'STRING'),
				'start' => $app->input->get('start', '', 'DATE'),
				'end' => $app->input->get('end', '', 'DATE'),
				'note' => $app->input->get('note', '', 'STRING')
			);
			

			// Bind data
			if (!$log->bind($data))
			{
				$obj->success = false;
				$obj->message = $log->getError();

				return $obj;
			}
		}
		else
		{
			$log = JTable::getInstance('Log', 'JTable', array());
			$log->state = $app->input->get('state', '', 'INT');
			$log->mother_code = $app->input->get('mother_code', '', 'STRING');
			$log->start = $app->input->get('start', '', 'DATE');
			$log->end = $app->input->get('end', '', 'DATE');
			$log->note = $app->input->get('note', '', 'STRING');
		}

		// Check the data.
		if (!$log->check())
		{
			$obj->success = false;
			$obj->message = $log->getError();

			return $obj;
		}

		// Store the data.
		if (!$log->store())
		{
			$obj->success = false;
			$obj->message = $log->getError();

			return $obj;
		}

		$result = new stdClass;
		$result->results = $log;

		$obj->success = true;
		$obj->data = $result;

		return $obj;
	}
}
