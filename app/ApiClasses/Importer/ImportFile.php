<?php

namespace App\ApiClasses\Importer;

use App\Models\Product;
use Exception;

class ImportFile extends AbstractImportFile
{

    /**
     * @return void
     * @throws Exception
     */
    public function processData(): void
    {
        if ($this->extension == 'csv') {
            $this->csvToArray();
        } else {
            // if there is other extensions need to add in the future
        }
    }


    private function csvToArray(): void
    {
        $file = fopen($this->filePath, "r");
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            $num = count($filedata);
            // Skip first row
            if ($i == 0) {
                $i++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file); //Close after reading

        $this->parsedData = $importData_arr;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function store(): bool
    {
        $j = 0;
        foreach ($this->parsedData as $importedRecord) {
            $j++;
            try {
                Product::create([
                    'name' => $importedRecord[0],
                    'part_number' => $importedRecord[1],
                    'articel_group_Id' => $importedRecord[2],
                    'prize' => $importedRecord[3],
                ]);

            } catch (Exception $e) {
                throw new Exception($e);
            }
        }
        return true;
    }


}
