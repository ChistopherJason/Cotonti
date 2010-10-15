<?php
/**
 * Trashcan API
 *
 * @package Trashcan
 * @version 0.7.0
 * @author Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

$GLOBALS['db_trash'] = (isset($GLOBALS['db_trash'])) ? $GLOBALS['db_trash'] : $GLOBALS['db_x'] . 'trash';

$trash_types = array('comment' => $db_com, 'forumpost' => $db_forum_posts, 'forumtopic' => $db_forum_topics, 'page' => $db_pages, 'user' => $db_users);

/**
 * Sends item to trash
 *
 * @param string $type Item type
 * @param string $title Title
 * @param int $itemid Item ID
 * @param mixed $datas Item contents
 * @param int $parentid trashcan parent id
 * @return int Trash insert id
 */
function cot_trash_put($type, $title, $itemid, $datas, $parentid = '0')
{
	global $cot_db, $db_trash, $sys, $usr, $trash_types;

	$trash = array('tr_date' => $sys['now_offset'], 'tr_type' => $type, 'tr_title' => $title, 'tr_itemid' => $itemid,
		'tr_trashedby' => (int)$usr['id'], 'tr_parentid' => $parentid);
	$i = 0;
	if (is_array($datas))
	{
		$i++;
		$trash['tr_datas'] = serialize($datas);
		$sql = $cot_db->insert($db_trash, $trash);
	}
	else
	{
		$databasename = isset($trash_types[$type]) ? $trash_types[$type] : $type;
		$sql_s = $cot_db->query("SELECT * FROM $databasename WHERE $datas");
		while ($row_s = $sql_s->fetch())
		{
			$i++;
			$trash['tr_datas'] = serialize($row_s);
			$sql = $cot_db->insert($db_trash, $trash);
		}
	}
	$id = ($i) ? $cot_db->lastInsertId() : false;
	return $id;
}

/**
 * Restores a trash item
 *
 * @param int $id Trash item ID
 * @return bool Operation success or failure
 */
function cot_trash_restore($id)
{
	global $cot_db, $db_trash, $trash_types;

	$tsql = $cot_db->query("SELECT * FROM $db_trash WHERE tr_id='$id' LIMIT 1");
	if ($res = $tsql->fetch())
	{
		$data = unserialize($res['tr_datas']);
		$type = $res['tr_type'];
		$restore = true;
		$databasename = isset($trash_types[$type]) ? $trash_types[$type] : $type;
		if(isset($trash_types[$type]) && function_exists('cot_trash'.$type.'_check'))
		{
			$check = 'cot_trash'.$type.'_check';
			$restore = $check($data);
		}
		if ($restore)
		{
			$sql = $cot_db->insert($databasename, $data);
			cot_log("$type #".$res['tr_itemid']." restored from the trash can.", 'adm');

			if(isset($trash_types[$type]) && function_exists('cot_trash'.$type.'_sync'))
			{
				$resync = 'cot_trash'.$type.'_sync';
				$resync($data);
			}

			if ($sql > 0)
			{
				$cot_db->delete($db_trash, "tr_id='".$row['tr_id']."'");
				$sql2 = $cot_db->query("SELECT tr_id FROM $db_trash WHERE tr_parentid='".(int)$res['tr_id'] ."'");
				while ($row2 = $sql2->fetch())
				{
					cot_trash_restore($row2['tr_id']);
				}
			}
		}
		return $sql;
	}
	return false;
}

/**
 * Deletes a trash item with subitems
 *
 * @param int $id Trash item ID
 * @return bool Operation success or failure
 */
function cot_trash_delete($id)
{
	global $cot_db, $db_trash;

	$tsql = $cot_db->query("SELECT * FROM $db_trash WHERE tr_id='$id' LIMIT 1");
	if ($res = $tsql->fetch())
	{
		$cot_db->delete($db_trash, "tr_id='".$row['tr_id']."'");
		$sql2 = $cot_db->query("SELECT tr_id FROM $db_trash WHERE tr_parentid='".(int)$res['tr_id'] ."'");
		while ($row2 = $sql2->fetch())
		{
			cot_trash_delete($row2['tr_id']);
		}
	}
	return true;
}

/**
 * Sync page action
 *
 * @param array $data trashcan item data
 * @return bool
 */
function cot_trashpage_sync($data)
{
	cot_structure_resync($id);
	($cot_cache && $cfg['cache_page']) && $cot_cache->page->clear('page');
	return true;
}

/**
 * Check forumpost action
 *
 * @param array $data trashcan item data
 * @return bool
 */
function cot_trashforumpost_check($data)
{
	global $db_forum_posts;
	$sql = $cot_db->query("SELECT ft_id FROM $db_forum_topics WHERE ft_id='".$data['fp_topicid']."'");
	if ($row = $sql->fetch())
	{
		return true;
	}
	return false;
}

/**
 * Sync forumpost action
 *
 * @param array $data trashcan item data
 * @return bool
 */
function cot_trashforumpost_sync($data)
{
	cot_forum_resynctopic($data['fp_topicid']);
	cot_forum_sectionsetlast($data['fp_sectionid']);
	cot_forum_resync($data['fp_sectionid']);
	return TRUE;
}

/**
 * Sync forumtopic action
 *
 * @param array $data trashcan item data
 * @return bool
 */
function cot_trashforumtopic_sync($data)
{
	cot_forum_resynctopic($res['tr_itemid']);
	cot_forum_sectionsetlast($data['ft_sectionid']);
	cot_forum_resync($data['ft_sectionid']);
	return TRUE;
}

?>