<?php

function flag($isoCode, $size = 32, $flat = false)
{
    $isoCode = strtoupper($isoCode);
    $isoCode = empty($isoCode) ? '_unknown' : $isoCode;
    $type = $flat ? 'flat' : 'shiny';
    $fileName = '/assets/flags/' . $type . '/' . $size . '/' . $isoCode . '.png';

    // ### TODO: Need to check if file exsits and default to generic flag
    // Also check these missing:
    return asset($fileName);
}

function countryName($isoCode)
{
    $isoCode = strtoupper($isoCode);
    return trans('countries.'.$isoCode);
}