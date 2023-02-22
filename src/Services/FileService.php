<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public static function saveFile(string $where_save, UploadedFile $temp_path):string{

        $newFilename = uniqid().'.'.$temp_path->guessExtension();
        try{
            $temp_path->move(
                $where_save,
                $newFilename
            );
            return $newFilename;
        }catch (FileException $e){
            throw new FileException($e);
        }

    }


}