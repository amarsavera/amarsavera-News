<?php

class UploadService
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath =
        dirname(__DIR__,2)
        .'/assets/uploads/';
    }

    public function upload(
        array $file,
        string $folder='news'
    ): array
    {

        if(
            !isset($file['tmp_name']) ||
            empty($file['tmp_name'])
        ){
            return [
                'status'=>false,
                'message'=>'No file selected'
            ];
        }

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/pdf'
        ];

        if(
            !in_array(
                $file['type'],
                $allowed
            )
        ){
            return [
                'status'=>false,
                'message'=>'Invalid file type'
            ];
        }

        $directory =
        $this->uploadPath.$folder.'/';

        if(!is_dir($directory))
        {
            mkdir(
                $directory,
                0777,
                true
            );
        }

        $extension =
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        );

        $filename =
        time().'_'.
        uniqid().
        '.'.$extension;

        $destination =
        $directory.$filename;

        if(
            move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ){

            return [
                'status'=>true,
                'file'=>$folder.'/'.$filename,
                'message'=>'Upload Success'
            ];
        }

        return [
            'status'=>false,
            'message'=>'Upload Failed'
        ];
    }
}