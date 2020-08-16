<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploader
{
    private $targetDirectoryArtist;
    private $targetDirectoryCategory;
    private $targetDirectoryMusic;
    private $targetDirectorySong;
    private $targetDirectoryEvent;


    public function __construct($targetDirectoryArtist, $targetDirectoryCategory, $targetDirectoryMusic, $targetDirectorySong, $targetDirectoryEvent)
    {
        $this->targetDirectoryArtist = $targetDirectoryArtist;
        $this->targetDirectoryCategory = $targetDirectoryCategory;
        $this->targetDirectoryMusic = $targetDirectoryMusic;
        $this->targetDirectorySong = $targetDirectorySong;
        $this->targetDirectoryEvent = $targetDirectoryEvent;
        
    }

    public function uploadArtist(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryArtist(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadCategory(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryCategory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }
    
    public function uploadMusic(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryMusic(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadSong(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectorySong(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadEvent(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryEvent(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectoryArtist()
    {
        return $this->targetDirectoryArtist;
    }

    public function getTargetDirectoryCategory()
    {
        return $this->targetDirectoryCategory;
    }

    public function getTargetDirectoryMusic()
    {
        return $this->targetDirectoryMusic;
    }

    public function getTargetDirectorySong()
    {
        return $this->targetDirectorySong;
    }

    public function getTargetDirectoryEvent()
    {
        return $this->targetDirectoryEvent;
    }
}