<?php

function get_status($key, $type = 'success') {
    $data = [];

    $data['icon'] = 'hs-check';
    $data['color'] = 'success';
    $data['buttonClass'] = 'text-white bg-success ring-success-ring';
    $data['textColor'] = 'text-success';
    $data['ringColor'] = 'bg-success-ring';
    $data['bgColor'] = 'bg-success';

    if ($type == 'error') {
        $data['icon'] = 'hs-exclamation';
        $data['color'] = 'danger';
        $data['buttonClass'] = 'text-white bg-danger ring-danger-ring';
        $data['textColor'] = 'text-danger';
        $data['ringColor'] = 'bg-danger-ring';
        $data['bgColor'] = 'bg-danger';
    }
    elseif ($type == 'info') {
        $data['icon'] = 'hs-information-circle';
        $data['color'] = 'info';
        $data['buttonClass'] = 'text-white bg-info ring-info-ring';
        $data['textColor'] = 'text-info';
        $data['ringColor'] = 'bg-info-ring';
        $data['bgColor'] = 'bg-info';
    }

    return $data[$key];
}
