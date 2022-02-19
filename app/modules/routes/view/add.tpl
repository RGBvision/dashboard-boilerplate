<!-- Modal -->
<div class="modal fade" id="addRoutesModal" tabindex="-1" aria-labelledby="addRoutesModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addRoutesForm" method="post" action="{$ABS_PATH}routes/add">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoutesModalLabel"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{#button_close#}"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{#button_cancel#}</button>
                    <button type="submit" class="btn btn-primary">{#button_save#}</button>
                </div>
            </form>
        </div>
    </div>
</div>