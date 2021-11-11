<!-- Modal -->
<div class="modal fade" id="avatarEditModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div>
                    <img src="{$ABS_PATH}assets/images/placeholder.jpg" class="w-100" style="max-height: 70vh" id="croppingImage" alt="cropper">
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{#button_cancel#}</button>
                <button type="button" id="applyNewAvatar" class="btn btn-primary">{#button_apply#}</button>
            </div>
        </div>
    </div>
</div>