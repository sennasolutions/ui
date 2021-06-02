<?php

function senna_ui_asset($path, $prependSennaDir = true) {
    $path = $prependSennaDir ? config('senna.ui.asset_dir') . '/' . $path : $path;
    $filepath = public_path($path);

    return asset($path . "?v=" . filectime(realpath($filepath)));
}