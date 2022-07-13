<?php

namespace App\ApiClasses\Importer;

use App\Models\Product;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

Interface ImportFileInterface
{

    /**
     * @param array $extensions
     */
    public function setValidExtensions(array $extensions);

    /**
     * @return array
     */
    public function getValidExtensions(): array;

    /**
     * @param int $extensions
     */
    public function setMaxFileSize(int $extensions);

    /**
     * @return int
     */
    public function getMaxFileSize(): int;

    /**
     * @param string $key
     */
    public function setCacheKey(string $key);

    /**
     * @return string
     */
    public function getCacheKey(): string;

    /**
     * @return string
     */
    public function getFilePath(): string;

    /**
     * @param $file
     * @return string
     * @throws Exception
     */
    public function importFile($file): string;

    /**
     * @return void
     */
    function processData(): void;

    /**
     * @return bool
     */
    function store(): bool;

}
