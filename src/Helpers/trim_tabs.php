<?php

use Illuminate\Support\Str;

/**
 * Trim the tabs evenly on a piece of code
 *
 * @param [type] $str
 * @return void
 */
function trim_tabs($str) {
    $lines = explode(PHP_EOL, $str);
    $firstSegment = true;
    $indentation = null;
  
    foreach($lines as $key => &$line) {
        if ($firstSegment && strlen(trim($line)) == 0) {
            unset($lines[$key]);
            continue;
        }
        $firstSegment = false;

        if ($indentation === null) {
            preg_match('/\S/', $line, $matches, PREG_OFFSET_CAPTURE);
            // If there is no indentation found set it to ""
            $indentation = "";

            if (!empty($matches)) {
                $position = $matches[0][1];
                $indentation = substr($line, 0, $position);
            }
        }

        $line = Str::replaceFirst($indentation, "", $line);
    }

    return trim(implode(PHP_EOL, $lines));
}