<?php

function get_status($key, $type = 'success') {
    $data = [];

    $data['icon'] = 'hs-check';
    $data['color'] = 'sui-success';
    $data['buttonClass'] = 'text-white bg-sui-success ring-sui-success-ring';
    $data['textColor'] = 'text-sui-success';
    $data['ringColor'] = 'bg-sui-success-ring';
    $data['bgColor'] = 'bg-sui-success';

    if ($type == 'error') {
        $data['icon'] = 'hs-exclamation';
        $data['color'] = 'sui-danger';
        $data['buttonClass'] = 'text-white bg-sui-danger ring-sui-danger-ring';
        $data['textColor'] = 'text-sui-danger';
        $data['ringColor'] = 'bg-sui-danger-ring';
        $data['bgColor'] = 'bg-sui-danger';
    }
    elseif ($type == 'info') {
        $data['icon'] = 'hs-information-circle';
        $data['color'] = 'sui-info';
        $data['buttonClass'] = 'text-white bg-sui-info ring-sui-info-ring';
        $data['textColor'] = 'text-sui-info';
        $data['ringColor'] = 'bg-sui-info-ring';
        $data['bgColor'] = 'bg-sui-info';
    }

    return $data[$key];
}
