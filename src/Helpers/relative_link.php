<?php

use Illuminate\Support\Facades\File;

function relative_link($from, $to) {
    $relativeFrom = get_relative_path($to, $from);

    return File::link($relativeFrom, $to);
}