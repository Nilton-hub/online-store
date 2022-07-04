<?php

namespace src\support;
use CoffeeCode\Uploader\Image;
use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Media;
use CoffeeCode\Uploader\Send;

class Upload
{
    private ?string $message = null;
    private static array $allowedsImage = ['image/jpeg', 'image/png', 'image/gif', 'image/web'];
    public function __construct() {
        
    }

    public function image(array $image, string $name, int $width = IMAGE_SIZE): ?string
    {
        $upload = new Image(UPLOAD_DIR, UPLOAD_IMAGE_DIR);
        if (empty($image['type']) || !in_array($image['type'], self::$allowedsImage)) {
            $this->message = 'O tipo de arquivo enviado na imagem é inválido! Favor, enviar .gif, .jepg, .png ou .webp. &#128558;';
            return null;
        }
        return $upload->upload($image, $name, $width, IMAGE_QUALITY);
    }

    public function file(array $file, string $name): ?string
    {
        $upload = new File(UPLOAD_DIR, UPLOAD_FILE_DIR);
        if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
            $this->message = 'Tipo de arquivo não permitido!.';
            return null;
        }
        return $upload->upload($file, $name);
    }

    public function media(array $media, string $name): ?string
    {
        $upload = new Media(UPLOAD_DIR, UPLOAD_MEDIA_DIR);
        if (empty($media['type']) || !in_array($media['type'], $upload::isAllowed())) {
            $this->message = 'Tipo de arquivo não permitido!.';
            return null;
        }
        return $upload->upload($media, $name);
    }

    public function send(array $send, string $name, array $types, array $extensions): ?string
    {
        $upload = new Send(UPLOAD_DIR, UPLOAD_SEND_DIR, $types, $extensions);
        if (empty($send['type']) || !in_array($send['type'], $types)) {
            $this->message = 'Tipo de arquivo não permitido!.';
            return null;
        }
        return $upload->upload($send, $name);
    }

    public function remove(string $filePath): bool
    {
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public static function allowedsImages(): array
    {
        return self::$allowedsImages;
    }
}
