<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.first
[END_COT_EXT]
==================== */

/**
 * Overrides markup in Forums posts
 *
 * @package bbcode
 * @version 0.9.0
 * @author Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

if ($cfg['parser'] == 'bbcode')
{
	$R['forums_code_quote'] = "[quote][url={\$url}]#{\$id}[/url] [b]{\$postername} :[/b]\n{\$text}\n[/quote]";
	$R['forums_code_quote_begin'] = '[quote]';
	$R['forums_code_quote_close'] = '[/quote]';
	$R['forums_code_update'] = "\n\n[b]{\$updated}[/b]\n\n";
}

?>
