<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function ErrorNotification() {
?>       
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Error</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <p class="toast-body"></p>
  </div>
</div>
<?php } ?>