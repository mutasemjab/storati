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
