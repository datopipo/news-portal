<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\ImageConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use InvalidArgumentException;

class FileUploadService
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $uploadDirectory,
        private readonly ImageConstants $imageConstants
    ) {
    }

    /**
     * Handles file upload from a form field
     *
     * @param FormInterface $form The form containing the file
     * @param string $fieldName The name of the file field
     * @param object $entity The entity to update with the file name
     * @param string $setterMethod The name of the method to set the file name on the entity
     * @throws \InvalidArgumentException If the file is invalid
     * @throws FileException If there was an error moving the file
     */
    public function handleFormUpload(FormInterface $form, string $fieldName, object $entity, string $setterMethod): void
    {
        $file = $form->get($fieldName)->getData();
        if (!$file) {
            return;
        }

        $newFilename = $this->upload($file);
        $entity->$setterMethod($newFilename);
    }

    /**
     * Uploads a file to the specified directory after validation
     *
     * @param UploadedFile $file The file to upload
     * @return string The new filename
     * @throws \InvalidArgumentException If the file is invalid
     * @throws FileException If there was an error moving the file
     */
    public function upload(UploadedFile $file): string
    {
        if (!$this->validateFile($file)) {
            throw new \InvalidArgumentException('Invalid file: File size or type not allowed');
        }

        try {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move(
                $this->uploadDirectory,
                $newFilename
            );

            return $newFilename;
        } catch (FileException $e) {
            throw new FileException('Failed to upload file: ' . $e->getMessage());
        }
    }

    /**
     * Validates the file size and type
     *
     * @param UploadedFile $file The file to validate
     * @return bool Whether the file is valid
     */
    private function validateFile(UploadedFile $file): bool
    {
        $maxSize = $this->parseSize($this->imageConstants->getMaxImageSize());
        if ($file->getSize() > $maxSize) {
            throw new InvalidArgumentException(sprintf('File size too large. Maximum allowed: %s', $this->imageConstants->getMaxImageSize()));
        }

        $allowedTypes = $this->imageConstants->getAllowedImageTypes();
        if (!in_array($file->getMimeType(), $allowedTypes, true)) {
            throw new InvalidArgumentException('Invalid file type. Allowed types: ' . implode(', ', $allowedTypes));
        }

        return true;
    }

    private function parseSize(string $size): int
    {
        $unit = strtoupper(substr($size, -1));
        $value = (int) substr($size, 0, -1);

        return match ($unit) {
            'K' => $value * 1024,
            'M' => $value * 1024 * 1024,
            'G' => $value * 1024 * 1024 * 1024,
            default => $value,
        };
    }
} 