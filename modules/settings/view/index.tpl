<div class="row text-left m-b-md" data-plugin="codemirror">
	<div class="col-xs-12">
		<div class="bs-callout bs-callout-warning">
			<h4 class="h4">{#settings_help_header#}</h4>
			<p>
				{#settings_help_descr#}
			</p>
		</div>
	</div>
</div>

<div class="row m-b-md">
	<div class="col-xs-12">
		<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
			<span><i class="fa fa-file-code-o "></i></span>{#settings_button_cache_clear#}
		</button>

		<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
			<span><i class="fa fa-file-image-o"></i></span>{#settings_button_delete_thumbs#}
		</button>

		<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
			<span><i class="fa fa-recycle"></i></span>{#settings_button_delete_revisions#}
		</button>

		<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
			<span><i class="fa fa-bar-chart "></i></span>{#settings_button_clear_day_counter#}
		</button>
	</div>
</div>

{if $permission}<form id="SettingsForm" action="./index.php?route=settings/save" method="post">{/if}
<div class="row text-left m-b-md">
	<div class="col-xs-12">
		<div class="panel p-b-0 m-b-none n-b">
			<div class="panel-heading bg-gray">
				{#settings_tabs_header_main#}
				<i class="pull-left icon-settings i-panel m-r-sm"></i>
			</div>
			<table class="table table-bordered table-bordered table-striped m-b-n">
				<colgroup>
					<col width="250" class="hidden-xs hidden-sm">
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="site_name">
								Наименование сайта
							</label>
						</td>
						<td class="text-l">
							<label class="visible-xs visible-sm" for="site_name">
								Наименование сайта
							</label>
							<div class="item-input">
								<input class="form-control mousetrap input-sm" type="text" name="settings[main][site_name]" id="site_name" value="{$settings.main.site_name}">
							</div>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="date_format">
								Формат даты
							</label>
						</td>
						<td class="text-l">
							<select name="settings[main][date_format]" id="date_format" class="form-control mousetrap input-sm w-md">
								{foreach from=$date_formats item=date_format}
									<option value="{$date_format}" {if $settings.main.date_format == $date_format}selected{/if}>
										{$smarty.now|date_format:$date_format|prettyDate}
									</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="time_format">
								Формат даты и времени
							</label>
						</td>
						<td class="text-l">
							<select name="settings[main][time_format]" id="time_format" class="form-control mousetrap input-sm w-md">
								{foreach from=$time_formats item=time_format}
									<option value="{$time_format}" {if $settings.main.time_format == $time_format}selected{/if}>
										{$smarty.now|date_format:$time_format|prettyDate}
									</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="public_time">
								Использовать дату публикации документов
							</label>
						</td>
						<td class="text-l">
							<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
								<input type="checkbox" value="1" name="settings[main][public_time]" id="public_time" {if $settings.main.public_time}checked{/if}>
								<i></i>
							</label>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="page_404">
								Страница с ошибкой HTTP 404: Page not found
							</label>
						</td>
						<td class="text-l">
							<div class="input-group w-xs">
								<input name="settings[main][page_404]" id="page_404" type="text" class="form-control mousetrap input-sm" readonly value="2">
								<span class="input-group-btn">
									<button class="btn btn-sm btn-info" type="button">...</button>
								</span>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row text-left m-b-md">
	<div class="col-xs-12">
		<div class="panel p-b-0 m-b-none n-b">
			<div class="panel-heading bg-gray">
				{#settings_tabs_header_maintance#}
				<i class="pull-left icon-settings i-panel m-r-sm"></i>
			</div>
			<table class="table table-bordered table-bordered table-striped m-b-n">
				<colgroup>
					<col width="250">
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="maintance_eneable">
								Режим обслуживания
							</label>
						</td>
						<td class="text-l">
							<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
								<input type="checkbox" value="1" name="settings[maintance][enable]" id="maintance_eneable" {if $settings.maintance.enable}checked{/if}>
								<i></i>
							</label>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="maintance_content">
								Заменять только содержимое
							</label>
						</td>
						<td class="text-l">
							<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
								<input type="checkbox" value="1" name="settings[maintance][content]" id="maintance_content" {if $settings.maintance.content}checked{/if}>
								<i></i>
							</label>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="maintance_template">
								Шаблон вывода для режима обслуживания
							</label>
						</td>
						<td class="text-l" data-codemirror="m_t_">
							<textarea class="form-control mousetrap input-sm" name="settings[maintance][template]" rows="8" id="maintance_template">{$settings.maintance.template}</textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row text-left m-b-md">
	<div class="col-xs-12">
		<div class="panel p-b-0 m-b-none n-b">
			<div class="panel-heading bg-gray">
				{#settings_tabs_header_permision#}
				<i class="pull-left icon-settings i-panel m-r-sm"></i>
			</div>
			<table class="table table-bordered table-bordered table-striped m-b-n">
				<colgroup>
					<col width="250">
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="permission_denied">
								Текст сообщения, если пользователь не имеет прав:
							</label>
						</td>
						<td class="text-l" data-codemirror="p_t_">
							<textarea class="form-control mousetrap input-sm" name="settings[permission][denied]" rows="8" id="permission_denied">{$settings.permission.denied}</textarea>
						</td>
					</tr>
					<tr>
						<td class="p-h-md hidden-xs hidden-sm">
							<label class="m-n" for="permission_hide">
								Текст сообщения при отсутствии прав для просмотра информации скрытой тегом [tag:hide:X,X]...[/tag:hide]
							</label>
						</td>
						<td class="text-l" data-codemirror="h_t_">
							<textarea class="form-control mousetrap input-sm" name="settings[permission][hide]" rows="8" id="permission_hide">{$settings.permission.hide}</textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row text-left m-b-md">
	<div class="col-xs-12">
		<div class="panel p-b-0 m-b-none n-b">
			<div class="panel-heading bg-gray n-b">
				{#settings_tabs_header_mail#}
				<i class="pull-left icon-settings i-panel m-r-sm"></i>
			</div>
			<table class="table table-bordered table-bordered table-striped m-b-n">
				<colgroup>
					<col width="250">
					<col>
				</colgroup>
				<tbody>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_from_name">
							Имя отправителя E-mail
						</label>
					</td>
					<td class="text-l">
						<div class="item-input">
							<input class="form-control mousetrap input-sm" type="text" name="settings[mail][from_name]" id="mail_from_name" value="{$settings.mail.from_name}">
						</div>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_from_email">
							E-mail отправителя
						</label>
					</td>
					<td class="text-l">
						<div class="item-input">
							<input class="form-control mousetrap input-sm" type="text" name="settings[mail][from_email]" id="mail_from_email" value="{$settings.mail.from_email}">
						</div>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_content_type">
							Тип письма
						</label>
					</td>
					<td class="text-l">
						<select name="settings[mail][content_type]" id="mail_content_type" class="form-control mousetrap input-sm w-md">
							<option value="text/plain" {if $settings.mail.content_type == 'text/plain'}selected{/if}>text/plain</option>
							<option value="text/html" {if $settings.mail.content_type == 'text/html'}selected{/if}>text/html</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_new_user">
							Cообщение пользователю после создания аккаунта
						</label>
					</td>
					<td class="text-l" data-codemirror="m_u_">
						<textarea class="form-control mousetrap input-sm" name="settings[mail][new_user]" rows="8" id="mail_new_user">{$settings.mail.new_user}</textarea>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_signature">
							Текст подписи
						</label>
					</td>
					<td class="text-l" data-codemirror="m_s_">
						<textarea class="form-control mousetrap input-sm" name="settings[mail][signature]" rows="8" id="mail_signature">{$settings.mail.signature}</textarea>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_word_wrap">
							Принудительный перенос после (знаков)
						</label>
					</td>
					<td class="text-l">
						<div class="item-input">
							<input class="form-control mousetrap input-sm w-xs" type="text" name="settings[mail][word_wrap]" id="mail_word_wrap" value="{$settings.mail.word_wrap}">
						</div>
					</td>
				</tr>
				<tr>
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="mail_type">
							Метод отправки почты
						</label>
					</td>
					<td class="text-l">
						<select name="settings[mail][mail_type]" id="mail_type" class="form-control mousetrap input-sm w-md">
							<option value="mail" {if $settings.mail.mail_type == 'mail'}selected{/if}>Mail</option>
							<option value="smtp" {if $settings.mail.mail_type == 'smtp'}selected{/if}>SMTP</option>
							<option value="sendmail" {if $settings.mail.mail_type == 'sendmail'}selected{/if}>Sendmail</option>
						</select>
					</td>
				</tr>
				<tr class="smtp_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="smtp_host">
							Сервер SMTP
						</label>
					</td>
					<td class="text-l">
						<input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_host]" id="smtp_host" value="{$settings.mail.smtp_host}">
					</td>
				</tr>
				<tr class="smtp_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="smtp_port">
							Порт SMTP
						</label>
					</td>
					<td class="text-l">
						<input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_port]" id="smtp_port" value="{$settings.mail.smtp_port}">
					</td>
				</tr>
				<tr class="smtp_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="smtp_login">
							Пользователь
						</label>
					</td>
					<td class="text-l">
						<input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_login]" id="smtp_login" value="{$settings.mail.smtp_login}">
					</td>
				</tr>
				<tr class="smtp_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="smtp_pass">
							Пароль
						</label>
					</td>
					<td class="text-l">
						<input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_pass]" id="smtp_pass" value="{$settings.mail.smtp_pass}">
					</td>
				</tr>
				<tr class="smtp_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="smtp_encrypt">
							Шифрование
						</label>
					</td>
					<td class="text-l">
						<select name="settings[mail][smtp_encrypt]" id="smtp_encrypt" class="form-control mousetrap input-sm w-md">
							<option value="" {if $settings.mail.smtp_encrypt == ''}selected{/if}>Без шифрования</option>
							<option value="tls" {if $settings.mail.smtp_encrypt == 'tls'}selected{/if}>TLS</option>
							<option value="ssl" {if $settings.mail.smtp_encrypt == 'ssl'}selected{/if}>SSL</option>
						</select>
					</td>
				</tr>
				<tr class="sendmail_group">
					<td class="p-h-md hidden-xs hidden-sm">
						<label class="m-n" for="sendmail_path">
							Путь до папки sendmail
						</label>
					</td>
					<td class="text-l">
						<input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][sendmail_path]" id="sendmail_path" value="{$settings.mail.sendmail_path}">
					</td>
				</tr>

				</tbody>
			</table>
		</div>
	</div>
</div>
{if $permission}</form>{/if}