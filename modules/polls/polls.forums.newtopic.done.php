<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.newtopic.newtopic.done
Tags=
[END_COT_EXT]
==================== */

/**
 * Polls
 *
 * @package polls
 * @version 0.7.0
 * @author esclkm, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL');

if(!empty($poll))
{
	cot_poll_save('forum', $q);
}

?>