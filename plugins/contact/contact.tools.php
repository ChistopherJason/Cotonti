<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin interface for Contact plugin
 *
 * @package contact
 * @version 2.1.0
 * @author Cotonti Team
 * @copyright (c) 2008-2011 Cotonti Team
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('contact', 'plug');

$a = cot_import('a','G','TXT');
$id = (int) cot_import('id','G','INT');
list($pg, $d, $durl) = cot_import_pagenav('d', $cfg['maxrowsperpage']);
$rtext = cot_import('rtext','P','TXT');

if ($a == 'del')
{
	$db->delete($db_contact, "contact_id = $id");
	cot_message('Deleted');
}
elseif ($a == 'val')
{
	$db->update($db_contact, array('contact_val' => 1), "contact_id = $id");
	cot_message('Updated');
}
elseif ($a == 'unval')
{
    $db->update($db_contact, array('contact_val' => 0), "contact_id = $id");
	cot_message('Updated');
}
elseif ($a == 'send' && $rtext != '')
{
    $row = $db->query("SELECT contact_email FROM $db_contact WHERE contact_id = $id")->fetch();
    cot_mail($row['contact_email'], $cfg['mainurl'], $rtext);
	$db->update($db_contact, array('contact_reply' => $rtext), "contact_id = $id");
	cot_message('Done');
}

$tuman = new XTemplate(cot_tplfile('contact.tools', 'plug'));
$totallines = $db->query("SELECT COUNT(*) FROM $db_contact")->fetchColumn();
$sql = $db->query("SELECT * FROM $db_contact ORDER BY contact_val ASC, contact_id DESC LIMIT $d, ".$cfg['maxrowsperpage']);

$pagnav = cot_pagenav('admin', 'm=other&p=contact', $d, $totallines, $cfg['maxlistsperpage']);

$i = 0;
while ($row = $sql->fetch())
{
    $i++;

    $shorttext = $row['contact_text'];
    $shorttext = cot_string_truncate($shorttext, 150);
    $shorttext .= '...';
    
    $tuman->assign(array(
        'CONTACT_DATE' => cot_date('date_full', $row['contact_date']),
        'CONTACT_DATE_STAMP' => $row['contact_date'] + $usr['timezone'] * 3600,
        'CONTACT_USER' => ($row['contact_authorid'] > 0) ? cot_build_user($row['contact_authorid'], $row['contact_author']) : $row['contact_author'],
        'CONTACT_EMAIL' => $row['contact_email'],
        'CONTACT_ID' => $row['contact_id'],
        'CONTACT_DELLINK' => cot_url('admin', 'm=other&p=contact&a=del&id='.$row['contact_id']),
        'CONTACT_VIEWLINK' => cot_url('admin', 'm=other&p=contact&id='.$row['contact_id']),
        'CONTACT_VAL' => ($row['contact_val'] == 1) ? 'unval' : 'val',
        'CONTACT_VALLINK' => cot_url('admin', 'm=other&p=contact&a='.$val.'&id='.$row['contact_id']),
        'CONTACT_READLINK' => cot_url('admin', 'm=other&p=contact&a=val&id='.$row['contact_id']),
        'CONTACT_UNREADLINK' => cot_url('admin', 'm=other&p=contact&a=unval&id='.$row['contact_id']),
        'CONTACT_SUBJECT' => $row['contact_subject'],
        'CONTACT_TEXT' => $row['contact_text'],
        'CONTACT_REPLY' => !empty($row['contact_reply']),
        'CONTACT_TEXTSHORT' => $shorttext,
        'CONTACT_ODDEVEN' => cot_build_oddeven($i),
        'CONTACT_I' => $i,
    ));
    $tuman->parse('MAIN.DATA');
}
$sql->closeCursor();

if(($a == '') && !empty($id))
{
    $row = $db->query("SELECT * FROM $db_contact WHERE contact_id = $id")->fetch();

    $tuman->assign(array(
        'CONTACT_DATE' => cot_date('date_full', $row['contact_date']),
        'CONTACT_DATE_STAMP' => $row['contact_date'] + $usr['timezone'] * 3600,
        'CONTACT_USER' => ($row['contact_authorid'] > 0) ? cot_build_user($row['contact_authorid'], $row['contact_author']) : $row['contact_author'],
        'CONTACT_EMAIL' => $row['contact_email'],
        'CONTACT_ID' => $row['contact_id'],
        'CONTACT_DELLINK' => cot_url('admin', 'm=other&p=contact&a=del&id='.$row['contact_id']),
        'CONTACT_VAL' => ($row['contact_val'] == 1) ? 'unval' : 'val',
        'CONTACT_VALLINK' => cot_url('admin', 'm=other&p=contact&a='.$val.'&id='.$row['contact_id']),
        'CONTACT_READLINK' => cot_url('admin', 'm=other&p=contact&a=val&id='.$row['contact_id']),
        'CONTACT_UNREADLINK' => cot_url('admin', 'm=other&p=contact&a=unval&id='.$row['contact_id']),
        'CONTACT_SUBJECT' => $row['contact_subject'],
        'CONTACT_TEXT' => $row['contact_text'],
        'CONTACT_REPLY' => $row['contact_reply'],
        'CONTACT_FORM_SEND' => cot_url("admin", 'm=other&p=contact&a=send&id='.$row['contact_id']),
        'CONTACT_FORM_TEXT' => cot_textarea('rtext', $rtext, 8, 64),
    ));
    $tuman->parse('MAIN.VIEW');

}

cot_display_messages($tuman);

$tuman->assign(array(
    'CONTACT_PAGINATION' => $pagnav['main'],
    'CONTACT_PREV'=> $pagenav['prev'],
    'CONTACT_NEXT'=> $pagenav['next'],
));
$tuman->parse('MAIN');
$plugin_body .= $tuman -> text('MAIN');


?>