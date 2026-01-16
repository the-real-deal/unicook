<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

function ErrorNotification() {
?>       
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
  <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Error</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <p class="toast-body" />
  </div>
</div>
<?php } ?>