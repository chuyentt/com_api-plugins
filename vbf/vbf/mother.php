<?php
/**
 * @package    Com_Api
 * @copyright  Copyright (C) 2009-2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license    GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link       http://www.techjoomla.com
 */
defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_SITE . '/components/com_vbf/models/mothers.php';
require_once JPATH_SITE . '/components/com_vbf/models/mother.php';

/**
 * Mother Resource
 *
 * @since  3.5
 */
class VbfApiResourceMother extends ApiResource
{
	/**
	 * get Method to get all mother data
	 *
	 * @return  json
	 *
	 * @since  3.5
	 */
	public function get()
	{
		$this->plugin->setResponse($this->getMothers());
	}

	/**
	 * delete Method to delete Mother
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
	 * getMothers Method to getMothers data
	 *
	 * @return  array
	 *
	 * @since  3.5
	 */
	public function getMothers()
	{
		$app = JFactory::getApplication();
		$items = array();
		$mother_id = $app->input->get('id', 0, 'INT');
		$mother_code = $app->input->get('code', '', 'STRING');

		$listOrder = $app->input->get('listOrder', 'ASC', 'STRING');

		$mother_obj = new VbfModelMothers;

		$mother_obj->setState('list.direction', $listOrder);

		if ($limit)
		{
			$mother_obj->setState('list.start', $limitstart);
			$mother_obj->setState('list.limit', $limit);
		}

		// Filter by mother
		if ($mother_id)
		{
			$mother_obj->setState('filter.id', $mother_id);
		}

		// Filter by mother
		if ($mother_code)
		{
			$mother_obj->setState('filter.code', $mother_code);
		}
		
		$rows = $mother_obj->getItems();

		$num_mothers = $mother_obj->getTotal();
		$data[] = new stdClass;

		foreach ($rows as $subKey => $subArray)
		{
			$data[$subKey]->babybirth = $subArray->babybirth;
			$data[$subKey]->code = $subArray->code;
			$data[$subKey]->lmp = $subArray->lmp;
			$data[$subKey]->name = $subArray->name;
			$data[$subKey]->note = $subArray->note;
			$data[$subKey]->phone = $subArray->phone;
			$data[$subKey]->token = $subArray->token;
			$data[$subKey]->type = $subArray->type;
		}

		$obj = new stdclass;
		$result = new stdClass;

		if (count($data) > 0)
		{
			$result->results = $data;
			$result->total = $num_mothers;
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
	 * Post is to create / update mother
	 *
	 * @return  Boolean
	 *
	 * @since  3.5
	 */
	public function post()
	{
		$this->plugin->setResponse($this->CreateUpdateMother());
	}

	/**
	 * CreateUpdateArticle is to create / upadte mother
	 *
	 * @return  Bolean
	 *
	 * @since  3.5
	 */
	public function CreateUpdateMother()
	{
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JTable::addIncludePath(JPATH_PLATFORM . 'joomla/database/table');
		}

		$obj = new stdclass;

		$app = JFactory::getApplication();
		$mother_id = $app->input->get('id', 0, 'INT');

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

		if ($mother_id)
		{
			$mother = JTable::getInstance('Mother', 'JTable', array());
			$mother->load($mother_id);
			$data = array(
				'state' => $app->input->get('state', '', 'INT'),
				'babybirth' => $app->input->get('babybirth', '', 'DATE'),
				'code' => $app->input->get('code', '', 'STRING'),
				'lmp' => $app->input->get('lmp', '', 'DATE'),
				'name' => $app->input->get('name', '', 'STRING'),
				'note' => $app->input->get('note', '', 'STRING'),
				'phone' => $app->input->get('phone', '', 'STRING'),
				'token' => $app->input->get('token', '', 'STRING'),
				'type' => $app->input->get('type', '', 'STRING')
			);
			

			// Bind data
			if (!$mother->bind($data))
			{
				$obj->success = false;
				$obj->message = $mother->getError();

				return $obj;
			}
		}
		else
		{
			$mother = JTable::getInstance('Mother', 'JTable', array());
			$mother->state = $app->input->get('state', '', 'INT');
			$mother->babybirth = $app->input->get('babybirth', '', 'DATE');
			$mother->code = $app->input->get('code', '', 'STRING');
			$mother->lmp = $app->input->get('lmp', '', 'DATE');
			$mother->name = $app->input->get('name', '', 'STRING');
			$mother->note = $app->input->get('note', '', 'STRING');
			$mother->phone = $app->input->get('phone', '', 'STRING');
			$mother->token = $app->input->get('token', '', 'STRING');
			$mother->type = $app->input->get('type', '', 'STRING');
		}

		// Check the data.
		if (!$mother->check())
		{
			$obj->success = false;
			$obj->message = $mother->getError();

			return $obj;
		}

		// Store the data.
		if (!$mother->store())
		{
			$obj->success = false;
			$obj->message = $mother->getError();

			return $obj;
		}

		$result = new stdClass;
		$result->results = $mother;

		$obj->success = true;
		$obj->data = $result;

		return $obj;
	}
}
