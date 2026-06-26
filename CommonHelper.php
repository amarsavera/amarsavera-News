<?php

class CommonHelper
{
    public static function slug(
        string $text
    ): string
    {
        return strtolower(
            preg_replace(
                '/[^A-Za-z0-9]+/',
                '-',
                trim($text)
            )
        );
    }
}