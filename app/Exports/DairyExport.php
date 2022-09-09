<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\DairyExportSheet;

class DairyExport implements WithMultipleSheets
{
    use Exportable;

    private $dairies;
    private $headers;
    private $nameSheets = ['Pendientes', 'Ejecutadas'];

    public function __construct($dairies = null, $headers)
    {
        $this->dairies = $dairies;
        $this->headers = $headers;
    }

    public function sheets(): array 
    {
        $sheets = [];
        $key = 0;
        foreach($this->dairies as $dairy) {
            $sheets[] = new DairyExportSheet($dairy, $this->nameSheets[$key], $this->headers);
            $key++;
        }
        return $sheets;
    }
}
