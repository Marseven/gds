<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = $message;
}
?>
<div class="alert alert-success" role="alert" style="text-align:center;">
<button class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
<strong><?= $message ?> </strong>
</div>
