<?php

function inject_in_javascript($data) {
    return is_array($data) ? str_replace('"', "'", json_encode($data)) : "`" . htmlentities($data) . "`";
}