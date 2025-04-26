<?php
function displayMessages($messages, $type = 'error') {
    if (!empty($messages)) {
        $class = $type === 'error' ? 'message-error' : 'message-success';
        echo "<div class='$class'><ul>";
        foreach ($messages as $message) {
            echo "<li>" . htmlspecialchars($message) . "</li>";
        }
        echo "</ul></div>";
    }
}
