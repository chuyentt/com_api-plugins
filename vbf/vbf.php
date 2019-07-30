<?php
/**
 * @package	API
 * @version 1.5
 * @author 	Tran Trung Chuyen
 * @link 	https://geomatics.vn
 * @copyright Copyright (C) 2019 Tran Trung Chuyen. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgAPIVbf extends ApiPlugin
{
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config = array());
		
		//load helper file
		//require_once JPATH_SITE.'/plugins/api/vbf/vbf/helper/helper.php';
		
		// Set resource path
		ApiResource::addIncludePath(dirname(__FILE__) . '/vbf');
		
		// Load language files
		//$lang = JFactory::getLanguage(); 
		//$lang->load('plg_api_vbf', JPATH_ADMINISTRATOR, '', true);
		
		// Set the vbf resource to be public (nếu muốn cho lấy thông tin mà không cần key)
		
		$this->setResourceAccess('faq', 'public', 'get');
		$this->setResourceAccess('notification', 'public', 'get');
		$this->setResourceAccess('vbf', 'public', 'get');
	}
}