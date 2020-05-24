<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="error message m-3 text-danger" onclick="this.classList.add('hidden');"><?= $message ?></div>
