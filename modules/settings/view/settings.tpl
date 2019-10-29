<div class="row text-left m-b-md">
	<div class="col-xs-12">
		<div class="bs-callout bs-callout-warning">
			<h4 class="h4">Управление общими настройками системы</h4>
			<p>
				В данном разделе вы можете отредактировать глобальные параметры системы. Пожалуйста, будьте предельно внимательны и помните, что неверные параметры могут сделать систему неработоспособной.
			</p>
		</div>
	</div>
</div>

<div class="wrapp no-border m-b-md bg-white r" data-plugin="codemirror">
	<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
		<span><i class="fa fa-file-code-o "></i></span>Очистить кэш и сессии
	</button>

	<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
		<span><i class="fa fa-file-image-o"></i></span>Удалить миниатюры
	</button>

	<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
		<span><i class="fa fa-recycle"></i></span>Удалить все ревизии
	</button>

	<button type="button" class="btn btn-danger btn-label btn-sm btn-responsive m-r-sm">
		<span><i class="fa fa-bar-chart "></i></span>Обнулить подневный счетчик
	</button>
</div>

<form enctype="multipart/form-data" method="post" class="form-horizontal" action="./index.php?route=settings/save" id="FormSettings">
	<div class="tab-container">
		<ul class="nav nav-tabs nav-justified">
			<li class="tabs active">
				<a data-toggle="tab" href="#main">Общие настройки</a>
			</li>
			<li class="tabs">
				<a data-toggle="tab" href="#maintance">Режим обслуживания</a>
			</li>
			<li class="tabs">
				<a data-toggle="tab" href="#permission">Привелегии</a>
			</li>
			<li class="tabs">
				<a data-toggle="tab" href="#mail">Настройки почты</a>
			</li>
		</ul>
		<div class="tab-content p-md">
			<div class="tab-pane active" id="main">

				<div class="row">
					<div class="col-sm-12">

						<div class="form-group">
							<label class="col-sm-2 control-label text-left" for="site_name">
								Наименование сайта
							</label>

							<div class="col-sm-10">
								<div class="item-input">
									<input class="form-control mousetrap input-sm" type="text" name="main[site_name]" id="site_name" value="{$settings.main.site_name}">
								</div>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-left" for="date_format">
								Формат даты
							</label>
							<div class="col-sm-10">
								<select name="main[date_format]" id="date_format" class="form-control mousetrap input-sm w-md">
									{foreach from=$date_formats item=date_format}
										<option value="{$date_format}">
											{$smarty.now|date_format:$date_format|prettyDate}
										</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-left" for="time_format">
								Формат даты и времени
							</label>
							<div class="col-sm-10">
								<select name="main[time_format]" id="time_format" class="form-control mousetrap input-sm w-md">
									{foreach from=$time_formats item=time_format}
										<option value="{$time_format}">
											{$smarty.now|date_format:$time_format|prettyDate}
										</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="public_time">
								Использовать дату публикации документов
							</label>
							<div class="col-sm-10">
								<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
									<input type="checkbox" value="1" name="main[public_time]" id="public_time">
									<i></i>
								</label>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="page_404">
								Страница с ошибкой HTTP 404: Page not found
							</label>
							<div class="col-sm-10">
								<div class="input-group col-sm-1">
									<input name="main[page_404]" id="page_404" type="text" class="form-control mousetrap input-sm" readonly value="2">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-info" type="button">...</button>
									</span>
								</div>
							</div>
						</div>

					</div>
				</div>

			</div>
			<!-- -->
			<div class="tab-pane" id="maintance">

				<div class="row">
					<div class="col-sm-12">

						<div class="form-group">
							<label class="col-sm-2 control-label" for="maintance">
								Режим обслуживания
							</label>
							<div class="col-sm-10">
								<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
									<input type="checkbox" value="1" name="maintance[enable]" id="maintance">
									<i></i>
								</label>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="maintance_content">
								Заменять только содержимое
							</label>
							<div class="col-sm-10">
								<label class="switch switch-sm m-t-xs bg-info check-header bg-primary">
									<input type="checkbox" value="1" name="maintance[content]" id="maintance_content">
									<i></i>
								</label>
							</div>
						</div>
						<div class="line line-dashed b-b line-lg"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="maintance_template">
								Шаблон вывода для режима обслуживания
							</label>
							<div class="col-sm-10" data-codemirror="m_t_">
								<textarea class="form-control mousetrap input-sm" name="maintance[template]" rows="8" id="maintance_template"></textarea>
							</div>
						</div>

					</div>
				</div>

			</div>
			<!-- -->
			<div class="tab-pane" id="permission">

				<div class="row">
					<div class="col-sm-12">

						<div class="form-group">
							<label class="col-sm-2 control-label" for="permission_no_rules">
								Текст сообщения, если пользователь не имеет прав:
							</label>
							<div class="col-sm-10" data-codemirror="p_t_">
								<textarea class="form-control mousetrap input-sm" name="permission[denied]" rows="8" id="permission_denied"></textarea>
							</div>
						</div>

						<div class="line line-dashed b-b line-lg"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="hide_template">
								Текст сообщения при отсутствии прав для просмотра информации скрытой тегом [tag:hide:X,X]...[/tag:hide]
							</label>
							<div class="col-sm-10" data-codemirror="h_t_">
								<textarea class="form-control mousetrap input-sm" name="permission[hide]" rows="8" id="permission_hide"></textarea>
							</div>
						</div>

					</div>
				</div>

			</div>
			<!-- -->
			<div class="tab-pane" id="mail">

				<div class="row">
					<div class="col-sm-12">

						<div class="form-group">
							<label class="col-sm-2 control-label" for="mail_from_name">
								Имя отправителя E-mail:
							</label>
							<div class="col-sm-10" data-codemirror="p_t_">
								<div class="item-input">
									<input class="form-control mousetrap input-sm" type="text" name="mail[from_name]" id="mail_from_name" value="">
								</div>
							</div>
						</div>

						<div class="line line-dashed b-b line-lg"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="mail_from_email">
								E-mail отправителя:
							</label>
							<div class="col-sm-10" data-codemirror="p_t_">
								<div class="item-input">
									<input class="form-control mousetrap input-sm" type="text" name="mail[from_email]" id="mail_from_email" value="">
								</div>
							</div>
						</div>

						<div class="line line-dashed b-b line-lg"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="mail_new_user">
								Cообщение пользователю после создания аккаунта
							</label>
							<div class="col-sm-10" data-codemirror="m_u_">
								<textarea class="form-control mousetrap input-sm" name="mail[new_user]" rows="8" id="mail_new_user"></textarea>
							</div>
						</div>

						<div class="line line-dashed b-b line-lg"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="mail_signature">
								Текст подписи
							</label>
							<div class="col-sm-10" data-codemirror="m_s_">
								<textarea class="form-control mousetrap input-sm" name="mail[signature]" rows="8" id="mail_signature"></textarea>
							</div>
						</div>

						<div class="line line-dashed b-b line-lg"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label" for="mail_word_wrap">
								Принудительный перенос после (знаков)
							</label>
							<div class="col-sm-10" data-codemirror="m_s_">
								<div class="item-input">
									<input class="form-control mousetrap input-sm w-xs" type="text" name="mail[word_wrap]" id="mail_word_wrap" value="">
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>

	<button type="button" class="btn btn-success btn-label btn-sm btn-responsive m-t-xs">
		<span><i class="fa fa-check"></i></span>{#button_save#}
	</button>
</form>