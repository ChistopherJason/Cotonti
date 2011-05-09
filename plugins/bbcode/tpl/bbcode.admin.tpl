<!-- BEGIN: MAIN -->
		<h2>BBCodes</h2>
		{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			<h3>{PHP.L.editdeleteentries}:</h3>
			<table class="cells">
				<tr>
					<td class="coltop width35">{PHP.L.Name}<br />{PHP.L.adm_bbcodes_mode} / {PHP.L.Enabled} / {PHP.L.adm_bbcodes_container}</td>
					<td class="coltop width20">{PHP.L.adm_bbcodes_pattern}</td>
					<td class="coltop width20">{PHP.L.adm_bbcodes_replacement}</td>
					<td class="coltop width15">{PHP.L.Plugin}<br />{PHP.L.adm_bbcodes_priority}<br />{PHP.L.adm_bbcodes_postrender}</td>
					<td class="coltop width10">{PHP.L.Action}</td>
				</tr>
<!-- BEGIN: ADMIN_BBCODE_ROW -->
				<form action="{ADMIN_BBCODE_ROW_UPDATE_URL}" method="post">
				<tr>
					<td class="centerall">
						{ADMIN_BBCODE_ROW_NAME}
						{ADMIN_BBCODE_ROW_MODE} {ADMIN_BBCODE_ROW_ENABLED} {ADMIN_BBCODE_ROW_CONTAINER}
					</td>
					<td class="centerall">
						{ADMIN_BBCODE_ROW_PATTERN}
					</td>
					<td class="centerall">
						<textarea name="bbc_replacement" rows="2" cols="20">{ADMIN_BBCODE_ROW_REPLACEMENT}</textarea>
					</td>
					<td class="centerall">
						<span style="display:block;">{ADMIN_BBCODE_ROW_PLUG}</span>
						{ADMIN_BBCODE_ROW_PRIO}
						{ADMIN_BBCODE_ROW_POSTRENDER}
					</td>
					<td class="centerall">
						<input type="submit" value="{PHP.L.Update}" /><br />
						<input type="button" value="{PHP.L.Delete}" onclick="if(confirm('{PHP.L.adm_bbcodes_confirm}')) location.href='{ADMIN_BBCODE_ROW_DELETE_URL}'" />
					</td>
				</tr>
			</form>
<!-- END: ADMIN_BBCODE_ROW -->
			</table>
			<p class="paging">{ADMIN_BBCODE_PAGINATION_PREV} {ADMIN_BBCODE_PAGNAV} {ADMIN_BBCODE_PAGINATION_NEXT}<span>{PHP.L.Total}: {ADMIN_BBCODE_TOTALITEMS}, {PHP.L.Onpage}: {ADMIN_BBCODE_COUNTER_ROW}</span></p>
			<h3>{PHP.L.adm_bbcodes_new}:</h3>
			<table class="cells">
				<tr>
					<td class="coltop width35">{PHP.L.Name}<br />{PHP.L.adm_bbcodes_mode} / {PHP.L.Enabled} / {PHP.L.adm_bbcodes_container}</td>
					<td class="coltop width20">{PHP.L.adm_bbcodes_pattern}</td>
					<td class="coltop width20">{PHP.L.adm_bbcodes_replacement}</td>
					<td class="coltop width15">{PHP.L.Plugin}<br />{PHP.L.adm_bbcodes_priority}<br />{PHP.L.adm_bbcodes_postrender}</td>
					<td class="coltop width10">{PHP.L.Action}</td>
				</tr>
				<form action="{ADMIN_BBCODE_FORM_ACTION}" method="post">
				<tr>
					<td class="centerall">
						{ADMIN_BBCODE_NAME}<br />
						{ADMIN_BBCODE_MODE}
					</td>
					<td class="centerall">
						{ADMIN_BBCODE_PATTERN}<br />
						{ADMIN_BBCODE_PRIO} &nbsp;
						{ADMIN_BBCODE_CONTAINER}
					</td>
					<td class="centerall">{ADMIN_BBCODE_REPLACEMENT}</td>
					<td class="centerall">{ADMIN_BBCODE_POSTRENDER}</td>
					<td class="centerall"><input type="submit" value="{PHP.L.Add}" /></td>
				</tr>
				<tr>
					<td class="strong textcenter" colspan="5"><a href="{ADMIN_BBCODE_URL_CLEAR_CACHE}" onclick="return confirm('{PHP.L.adm_bbcodes_clearcache_confirm}')">{PHP.L.adm_bbcodes_clearcache}</a></td>
				</tr>
				</form>
			</table>
<!-- END: MAIN -->