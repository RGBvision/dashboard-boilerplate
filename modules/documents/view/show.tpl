{if ! $_is_ajax}
  <div id="main-holder" class="row">
    <div class="col-lg-9 col-xl-8 mx-auto">
      <div class="card">
        <div class="card-block">
          {$doc_data.content|htmlspecialchars_decode}
        </div>
      </div>
    </div>
  </div>
{else}
  {$doc_data.content|htmlspecialchars_decode}
{/if}