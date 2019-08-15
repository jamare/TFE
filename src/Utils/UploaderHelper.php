<?php

namespace App\Utils;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadProviderLogo(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadsPath.'/logos';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }
}