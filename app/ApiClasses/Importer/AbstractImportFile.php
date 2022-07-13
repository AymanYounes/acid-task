<?php

namespace App\ApiClasses\Importer;

use App\Models\Product;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

abstract class AbstractImportFile implements ImportFileInterface
{

    /**
     * @var int
     */
    protected int $fileSize;

    /**
     * @var string
     */
    protected string $filePath;

    /**
     * @var string
     */
    protected string $extension;

    /**
     * @var string
     */
    protected string $filename;

    /**
     * @var array|string[]
     * Only want csv files for now and can be extended
     */
    protected array $validExtensions = ['csv'];

    /**
     * @var int
     * Uploaded file size default limit 2mb
     */
    protected int $maxFileSize = 2097152;

    /**
     * @var array
     */
    protected array $parsedData;


    protected string $cacheKey;


    /**
     * @param array $extensions
     */
    public function setValidExtensions(array $extensions)
    {
        $this->validExtensions = $extensions;
    }

    /**
     * @return array
     */
    public function getValidExtensions(): array
    {
        return $this->validExtensions;
    }

    /**
     * @param int $extensions
     */
    public function setMaxFileSize(int $extensions)
    {
        $this->maxFileSize = $extensions;
    }

    /**
     * @return int
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * @param string $key
     */
    public function setCacheKey(string $key)
    {
        $this->cacheKey = $key;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param $file
     * @return string
     * @throws Exception
     */
    public function importFile($file): string
    {
        $this->uploadFile($file);
        $this->processData();
        $store = $this->store();
        Cache::forget('allProducts');
        return $store;
    }

    /**
     * @param $file
     * @return void
     * @throws Exception
     */
    protected function uploadFile($file): void
    {
        $filename = $file->getClientOriginalName();
        $this->extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $this->fileSize = $file->getSize(); //Get size of uploaded file in bytes
        $this->validateFile();
        $location = 'uploads';
        $file->move($location, $filename);
        $this->filePath = public_path($location . "/" . $filename);
    }

    private function validateFile()
    {
        if (in_array(strtolower($this->extension), $this->validExtensions)) {
            if ($this->fileSize <= $this->maxFileSize) {
            } else {
                throw new Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

    /**
     * @return void
     */
    abstract function processData(): void;

    /**
     * @return bool
     */
    abstract function store(): bool;

}
