<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {#settings_tabs_header_mail#}
            </div>
            <div class="card-body">
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_from_name">
                            Имя отправителя E-mail
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <div class="item-input">
                            <input class="form-control mousetrap input-sm" type="text" name="settings[mail][from_name]" id="mail_from_name" value="{$settings.mail.from_name}">
                        </div>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_from_email">
                            E-mail отправителя
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <div class="item-input">
                            <input class="form-control mousetrap input-sm" type="text" name="settings[mail][from_email]" id="mail_from_email" value="{$settings.mail.from_email}">
                        </div>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_content_type">
                            Тип письма
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <select name="settings[mail][content_type]" id="mail_content_type" class="form-control mousetrap input-sm w-md">
                            <option value="text/plain" {if $settings.mail.content_type == 'text/plain'}selected{/if}>text/plain</option>
                            <option value="text/html" {if $settings.mail.content_type == 'text/html'}selected{/if}>text/html</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_new_user">
                            Cообщение пользователю после создания аккаунта
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <textarea class="form-control mousetrap input-sm" name="settings[mail][new_user]" row align-items-center py-1 mx-0 bg-highlight-hover border-bottoms="8" id="mail_new_user">{$settings.mail.new_user}</textarea>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_signature">
                            Текст подписи
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <textarea class="form-control mousetrap input-sm" name="settings[mail][signature]" row align-items-center py-1 mx-0 bg-highlight-hover border-bottoms="8" id="mail_signature">{$settings.mail.signature}</textarea>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_word_wrap">
                            Принудительный перенос после (знаков)
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <div class="item-input">
                            <input class="form-control mousetrap input-sm w-xs" type="text" name="settings[mail][word_wrap]" id="mail_word_wrap" value="{$settings.mail.word_wrap}">
                        </div>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="mail_type">
                            Метод отправки почты
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <select name="settings[mail][mail_type]" id="mail_type" class="form-control mousetrap input-sm w-md">
                            <option value="mail" {if $settings.mail.mail_type == 'mail'}selected{/if}>Mail</option>
                            <option value="smtp" {if $settings.mail.mail_type == 'smtp'}selected{/if}>SMTP</option>
                            <option value="sendmail" {if $settings.mail.mail_type == 'sendmail'}selected{/if}>Sendmail</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="smtp_host">
                            Сервер SMTP
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_host]" id="smtp_host" value="{$settings.mail.smtp_host}">
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="smtp_port">
                            Порт SMTP
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_port]" id="smtp_port" value="{$settings.mail.smtp_port}">
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="smtp_login">
                            Пользователь
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_login]" id="smtp_login" value="{$settings.mail.smtp_login}">
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="smtp_pass">
                            Пароль
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][smtp_pass]" id="smtp_pass" value="{$settings.mail.smtp_pass}">
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover border-bottom">
                    <div class="col-lg-4">
                        <label class="m-n" for="smtp_encrypt">
                            Шифрование
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <select name="settings[mail][smtp_encrypt]" id="smtp_encrypt" class="form-control mousetrap input-sm w-md">
                            <option value="" {if $settings.mail.smtp_encrypt == ''}selected{/if}>Без шифрования</option>
                            <option value="tls" {if $settings.mail.smtp_encrypt == 'tls'}selected{/if}>TLS</option>
                            <option value="ssl" {if $settings.mail.smtp_encrypt == 'ssl'}selected{/if}>SSL</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center py-1 mx-0 bg-highlight-hover">
                    <div class="col-lg-4">
                        <label class="m-n" for="sendmail_path">
                            Путь до папки sendmail
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control mousetrap input-sm w-md" type="text" name="settings[mail][sendmail_path]" id="sendmail_path" value="{$settings.mail.sendmail_path}">
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{$smarty.server.HTTP_REFERER}" class="btn btn-secondary btn-icon-text"><i class="mdi mdi-arrow-left btn-icon-prepend"></i> {#button_return#}</a>
                <button type="submit" class="SaveSettingsBtn btn btn-primary btn-icon-text"><i class="mdi mdi-check btn-icon-prepend"></i> {#button_save#}</button>
            </div>
        </div>
    </div>
</div>