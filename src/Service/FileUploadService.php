<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\AppConstants;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $uploadDirectory
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $this->uploadDirectory,
            $newFilename
        );

        return $newFilename;
    }

    public function validateFile(UploadedFile $file): bool
    {
        return $file->getSize() <= AppConstants::getMaxFileSize() &&
            in_array($file->getMimeType(), AppConstants::getAllowedImageTypes(), true);
    }

    public function getUploadDirectory(): string
    {
        return $this->uploadDirectory;
    }
} 