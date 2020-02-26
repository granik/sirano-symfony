<?php

namespace App\Interactors;


interface FileUploaderInterface
{
    public function upload($icon, string $string, string $directory = ''): string;
}