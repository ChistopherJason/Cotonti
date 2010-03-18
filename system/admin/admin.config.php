<?php
/**
 * Administration panel - Configuration
 *
 * @package Cotonti
 * @version 0.7.0
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

(defined('SED_CODE') && defined('SED_ADMIN')) or die('Wrong URL.');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');
sed_block($usr['isadmin']);

$t = new XTemplate(sed_skinfile('admin.config'));

$adminpath[] = array(sed_url('admin', 'm=config'), $L['Configuration']);

$sed_select_charset = sed_loadcharsets();
$sed_select_doctypeid = sed_loaddoctypes();
$sed_select_rss_charset = sed_loadcharsets();

/* === Hook === */
$extp = sed_getextplugins('admin.config.first');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

switch($n)
{
	case 'edit':
		$o = sed_import('o', 'G', 'ALP');
		$p = sed_import('p', 'G', 'ALP');
		$v = sed_import('v', 'G', 'ALP');
		$o = empty($o) ? 'core' : $o;
		$p = empty($p) ? 'global' : $p;

		if ($a == 'update')
		{
			
			$sql = sed_sql_query("SELECT config_name FROM $db_config
				WHERE config_owner='$o' AND config_cat='$p'");
			while ($row = sed_sql_fetcharray($sql))
			{
				$cfg_value = trim(sed_import($row['config_name'], 'P', 'NOC'));
				if ($o == 'core' && $p == 'users'
					&& ($cfg_name == 'av_maxsize' || $cfg_name == 'sig_maxsize' || $cfg_name == 'ph_maxsize'))
				{
						$cfg_value = min($cfg_value, sed_get_uploadmax() * 1024);
				}
				sed_sql_query("UPDATE $db_config SET config_value='" . sed_sql_prep($cfg_value) . "'
					WHERE config_name='" . $row['config_name'] . "' AND config_owner='$o' AND config_cat='$p'");
			}

			$cot_cache && $cot_cache->db->remove('cot_cfg', 'system');
			$adminwarnings = $L['Updated'];
		}
		elseif ($a == 'reset' && !empty($v))
		{
			sed_sql_query("UPDATE $db_config SET config_value=config_default WHERE config_name='$v' AND config_owner='$o'");
			$cot_cache && $cot_cache->db->remove('cot_cfg', 'system');
		}

		$sql = sed_sql_query("SELECT * FROM $db_config WHERE config_owner='$o' AND config_cat='$p' ORDER BY config_cat ASC, config_order ASC, config_name ASC");
		sed_die(sed_sql_numrows($sql) == 0);

		if ($o == 'core')
		{
			$adminpath[] = array(sed_url('admin', 'm=config&n=edit&o='.$o.'&p='.$p), $L["core_".$p]);
		}
		else
		{
			$adminpath[] = array(sed_url('admin', 'm=plug&a=details&pl='.$p), $L['Plugin'].' ('.$o.':'.$p.')');
			$adminpath[] = array(sed_url('admin', 'm=config&n=edit&o='.$o.'&p='.$p), $L['Edit']);
		}

		if ($o == 'plug')
		{
			$path_lang_def = $cfg['plugins_dir']."/$p/lang/$p.en.lang.php";
			$path_lang_alt = $cfg['plugins_dir']."/$p/lang/$p.$lang.lang.php";
			if (file_exists($path_lang_def))
			{
				require_once($path_lang_def);
			}
			if (file_exists($path_lang_alt) && $lang !='en')
			{
				require_once($path_lang_alt);
			}
		}

		/* === Hook - Part1 : Set === */
		$extp = sed_getextplugins('admin.config.edit.loop');
		/* ===== */
		while ($row = sed_sql_fetcharray($sql))
		{
			$config_owner = $row['config_owner'];
			$config_cat = $row['config_cat'];
			$config_name = $row['config_name'];
			$config_value = htmlspecialchars($row['config_value']);
			$config_default = $row['config_default'];
			$config_type = $row['config_type'];
			$config_title = $L['cfg_'.$row['config_name']][0];
			$config_text = htmlspecialchars($row['config_text']);
			$config_more = $L['cfg_'.$row['config_name']][1];
			$if_config_more = (!empty($config_more)) ? true : false;

			if ($config_type == 1)
			{
				$t->assign(array(
					"ADMIN_CONFIG_ROW_CONFIG_NAME" => $config_name,
					"ADMIN_CONFIG_ROW_CONFIG_VALUE" => $config_value,
				));
				$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_1");
			}
			elseif ($config_type == 2)
			{
				if (!empty($row['config_variants']))
				{
					$cfg_params = explode(',', $row['config_variants']);
				}

				if (is_array($cfg_params))
				{
					foreach ($cfg_params as $x)
					{
						$x = trim($x);

						$t->assign(array(
							"ADMIN_CONFIG_ROW_CONFIG_OPTION_VALUE" => $x,
							"ADMIN_CONFIG_ROW_CONFIG_OPTION_SELECTED" => ($x == $config_value) ? " selected=\"selected\"" : '',
							"ADMIN_CONFIG_ROW_CONFIG_OPTION_NAME" => $config_name
						));
						$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_2.ADMIN_CONFIG_ROW_TYPE_2_SELECT.ADMIN_CONFIG_ROW_TYPE_2_OTP");
					}
					$t->assign(array(
						"ADMIN_CONFIG_ROW_CONFIG_NAME" => $config_name
					));
					$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_2.ADMIN_CONFIG_ROW_TYPE_2_SELECT");
				}
				// FIXME seems like obsolete code
				/*elseif ($cfg_params == "userlevels")
				{
					$t->assign(array(
						"ADMIN_CONFIG_ROW_CONFIG_OPTION" => sed_selectboxlevels(0, 99, $config_value, $config_name)
					));
					$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_2.ADMIN_CONFIG_ROW_TYPE_2_SELECT");
				}*/
				else
				{
					$t->assign(array(
						"ADMIN_CONFIG_ROW_CONFIG_OPTION_VALUE" => $config_value,
						"ADMIN_CONFIG_ROW_CONFIG_OPTION_NAME" => $config_name
					));
					$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_2.ADMIN_CONFIG_ROW_TYPE_2_TEXT");
				}
				$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_2");
			}
			elseif ($config_type == 3)
			{
				$t->assign(array(
					"ADMIN_CONFIG_ROW_CONFIG_NAME" => $config_name
				));
				$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_3");
			}
			elseif ($config_type == 4)
			{
				$varname = "sed_select_".$config_name;
				reset($$varname);
				while (list($i, $x) = each($$varname))
				{
					$t->assign(array(
						"ADMIN_CONFIG_ROW_CONFIG_OPTION_VALUE" => $x[0],
						"ADMIN_CONFIG_ROW_CONFIG_OPTION_SELECTED" => ($config_value == $x[0]) ? " selected=\"selected\"" : '',
						"ADMIN_CONFIG_ROW_CONFIG_OPTION_NAME" => $x[1]
					));
					$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_4.ADMIN_CONFIG_ROW_TYPE_4_OTP");
				}
				$t->assign(array(
					"ADMIN_CONFIG_ROW_CONFIG_NAME" => $config_name,
				));
				$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_4");
			}
			else
			{
				$t->assign(array(
					"ADMIN_CONFIG_ROW_CONFIG_NAME" => $config_name,
					"ADMIN_CONFIG_ROW_CONFIG_VALUE" => $config_value
				));
				$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW.ADMIN_CONFIG_ROW_TYPE_5");
			}

			$t->assign(array(
				"ADMIN_CONFIG_ROW_CONFIG_TITLE" => (empty($L['cfg_'.$row['config_name']][0]) && !empty($config_text)) ? $config_text : $config_title,
				"ADMIN_CONFIG_ROW_CONFIG_MORE_URL" => sed_url('admin', "m=config&n=edit&o=".$o."&p=".$p."&a=reset&v=".$config_name),
				"ADMIN_CONFIG_ROW_CONFIG_MORE" => $config_more
			));
			/* === Hook - Part2 : Include === */
			foreach ($extp as $pl)
			{
				include $pl;
			}
			/* ===== */
			$t->parse("CONFIG.EDIT.ADMIN_CONFIG_ROW");
		}

		$t->assign(array(
			"ADMIN_CONFIG_FORM_URL" => sed_url('admin', "m=config&n=edit&o=".$o."&p=".$p."&a=update")
		));
		/* === Hook  === */
		$extp = sed_getextplugins('admin.config.edit.tags');
		foreach ($extp as $pl)
		{
			include $pl;
		}
		/* ===== */
		$t->parse("CONFIG.EDIT");
	break;

	default:
		$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='core' ORDER BY config_cat ASC");
		while ($row = sed_sql_fetcharray($sql))
		{
			if($L["core_".$row['config_cat']])
			{
				$t->assign(array(
					"ADMIN_CONFIG_ROW_CORE_URL" => sed_url('admin', "m=config&n=edit&o=core&p=".$row['config_cat']),
					"ADMIN_CONFIG_ROW_CORE_NAME" => $L["core_".$row['config_cat']]
				));
				$t->parse("CONFIG.DEFAULT.ADMIN_CONFIG_ROW_CORE");
			}
		}
		$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='plug' ORDER BY config_cat ASC");
		while ($row = sed_sql_fetcharray($sql))
		{
			$t->assign(array(
				"ADMIN_CONFIG_ROW_PLUG_URL" => sed_url('admin', "m=config&n=edit&o=plug&p=".$row['config_cat']),
				"ADMIN_CONFIG_ROW_PLUG_NAME" => $row['config_cat']
			));
			$t->parse("CONFIG.DEFAULT.ADMIN_CONFIG_ROW_PLUG");
		}
		/* === Hook  === */
		$extp = sed_getextplugins('admin.config.default.tags');
		foreach ($extp as $pl)
		{
			include $pl;
		}
		/* ===== */
		$t->parse("CONFIG.DEFAULT");
	break;
}

$is_adminwarnings = isset($adminwarnings);

$t->assign(array(
	"ADMIN_CONFIG_ADMINWARNINGS" => $adminwarnings
));

/* === Hook  === */
$extp = sed_getextplugins('admin.config.tags');
foreach ($extp as $pl)
{
	include $pl;
}
/* ===== */

$t->parse('CONFIG');
if (SED_AJAX)
{
	$t->out('CONFIG');
}
else
{
	$adminmain = $t->text('CONFIG');
}
?>