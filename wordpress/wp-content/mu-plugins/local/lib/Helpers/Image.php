<?php

namespace Local\Helpers;

class Image
{
    public static function resize(\Timber\Image $image, int $width, int $height = 0): string
    {
        if ($width >= $image->width()) {
            return $image->src();
        }

        return \Timber\ImageHelper::resize($image->src(), $width, $height);
    }
}