<?php

function uploadImage($folder, $image)
{
  $extension = strtolower($image->getClientOriginalExtension());

  // Generate unique filename
  $filename = uniqid() . '_' . time() . '.' . $extension;
  $image->move($folder, $filename);
  return $filename;
}


function uploadFile($file, $folder)
{
  $path = $file->store($folder);
  return $path;
}

if (!function_exists('currency')) {
    /**
     * Resolve the active CurrencyService from the container.
     * Usage:
     *   currency()->convert(99.99)   → 374.63  (if SAR, rate=3.75)
     *   currency()->format(99.99)    → "﷼ 374.63"
     *   currency()->meta()           → ['code'=>'SAR', 'symbol'=>'﷼', 'rate'=>3.75]
     */
    function currency(): \App\Services\CurrencyService
    {
        return app(\App\Services\CurrencyService::class);
    }
}