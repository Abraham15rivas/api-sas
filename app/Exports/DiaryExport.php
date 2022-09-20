<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\DiaryExportSheet;
use Maatwebsite\Excel\Excel;

class DiaryExport implements WithMultipleSheets
{
    use Exportable;

    private $diaries;
    private $headerReport;
    private $nameSheets = ['Ejecutada', 'planificada'];

    public function __construct($diaries = null, $headerReport, $mainTitle = null)
    {
        $this->diaries      = $diaries;
        $this->headerReport = $headerReport;
        $this->mainTitle    = $mainTitle;
    }

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'Agenda VICEPRESIDENCIA SOCIAL_DVIAC.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function sheets(): array 
    {
        $sheets = [];
        $key = 0;
        foreach($this->diaries as $diary) {
            $sheets[] = new DiaryExportSheet($diary, $this->nameSheets[$key], $this->headerReport, $this->mainTitle);
            $key++;
        }
        return $sheets;
    }
}
