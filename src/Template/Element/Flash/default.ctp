<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="<?= h($class) ?>" role="alert" style="text-align:center;">
<button class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
<strong><?= $message ?> </strong>
</div>

