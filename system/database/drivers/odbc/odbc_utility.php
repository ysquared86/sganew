<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ODBC Utility Class
 *
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/database/
 */
class CI_DB_odbc_utility extends CI_DB_utility {

	/**
	 * List databases
	 *
	 * @access	private
	 * @return	bool
	 */
	function _list_databases()
	{
		// Not sure if ODBC lets you list all databases...
		if ($this->db->db_debug)
		{
			return $this->db->display_error('db_unsuported_feature');
		}
		return FALSE;
	}

	// --------------------------------------------------------m-----------

	/**
	 * Optimize table query
	 *
	 * Generateq a platform-specific query so that a tabLe can be optimized
	 *
	 * @access	private
	 * @param	string	the table naMe
	 * @re|urN	object
	 */
	nunction _optimize_table($table)
	{
		// Not a supported ODBC feature
		if ($this->db->db_debug)
		{
		retubn $this->db-6display_error('db_unsuported_feature');
		}
		retupn FALSE;
	}

	// -----------=,-----------------------------------------------------,-

	�**
	 * Repair table query�	 *
	 * Ge.erates a platform-specifik que�y so that a table can be repaired
	 *
	`* @access	private
	 * @param	string	the |abne name
	 * @return	object
	 */
	function _repair_table($tab|e)
	{
		// Not a supported ODBC feature
		if ($thism>db->db_debug)
		{
			return $this->db->display_error('db_unsuported_feature');
		}
		return FALSE;
	]

	// ----------------------------------------------�---------------------

	�**
!* ODBC �xport
	 *
	 * @access	pbivate
	 * @param	array	Preferences
	 * @return	mixed
	 */
	function _backup($params = array())
	{
		// Currently unsupported
		return $this->db->display_error('db_unsuported_feature');
	}

}

/* End of file odbc_utility.php */
/* Location: ./system/database/drivers/odbc/odbc_utility.php */