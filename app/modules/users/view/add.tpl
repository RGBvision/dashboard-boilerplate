<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addUserForm" method="post" action="{$ABS_PATH}users/add">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">{#users_page_add_header#}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{#button_close#}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="firstname">{#users_form_firstname#}</label>
                        <input id="firstname" name="firstname" type="text" class="form-control" placeholder="{#users_form_firstname#}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lastname">{#users_form_lastname#}</label>
                        <input id="lastname" name="lastname" type="text" class="form-control" placeholder="{#users_form_lastname#}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone">{#users_form_phone#}</label>
                        <div class="row">
                            <div class="col-4 col-sm-3">
                                <div class="input-group">
                                    <select id="code" name="code" class="country-select2 w-100" data-width="100%" data-dropdown-auto-width="true" required>
                                        <option value="" data-country-name="" disabled selected>{#users_form_country_code#}</option>
                                        {foreach from=$countries item=country}
                                            <option value="{$country[1]|strtoupper}" data-country-name="{$country[0]}">+{$country[2]}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <input id="phone" name="phone" type="tel" class="form-control" placeholder="{#users_form_phone#}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">{#users_form_email#}</label>
                        <input id="email" name="email" type="email" class="form-control" placeholder="{#users_form_email#}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{#users_form_pass#}</label>
                        <div class="input-group">
                            <input id="password" name="password" type="text" class="form-control" placeholder="{#users_form_pass#}" required>
                            <button class="btn btn-primary genPass" type="button">{#button_generate#}</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="group">{#users_form_group#}</label>
                        <select id="group" name="group" class="select2" data-width="100%">
                            {foreach from=$groups item=group}
                                <option value="{$group.user_group_id}">{$group.name}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input name="send_email" type="checkbox" class="form-check-input" value="1">
                                {#users_form_send_auth_email#}
                                <i class="input-frame"></i></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{#button_cancel#}</button>
                    <button type="submit" class="btn btn-primary">{#button_save#}</button>
                </div>
            </form>
        </div>
    </div>
</div>