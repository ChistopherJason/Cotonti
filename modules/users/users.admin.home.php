<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.home.sidepanel
[END_COT_EXT]
==================== */

/**
 * Users module
 *
 * @package users
 * @version 0.9.4
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

$tt = new XTemplate(cot_tplfile('users.admin.home', 'module'));

require_once cot_incfile('users', 'module');

$tt->parse('MAIN');

$line = $tt->text('MAIN');

?>