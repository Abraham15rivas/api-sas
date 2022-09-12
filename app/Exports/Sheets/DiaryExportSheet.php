<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\{
    FromArray,
    WithTitle,
    WithMapping,
    WithHeadings,
    WithStrictNullComparison,
    ShouldAutoSize,
    WithStyles
};

class DiaryExportSheet implements 
    FromArray, 
    WithTitle, 
    WithMapping, 
    WithHeadings, 
    WithStrictNullComparison,
    ShouldAutoSize,
    WithStyles   
{
    private $dairies, $title, $headers;

    public function __construct($dairies = null, $title = null, $headers)
    {
        $this->dairies    = $dairies;
        $this->title      = $title;
        $this->headers    = $headers;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->dairies;
    }
    
    public function title(): string
    {
        return $this->title;
    }

    /**
    * @param mixed $row
    *
    * @return array
    */
    public function map($row): array
    {
        return $row;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        $data[1] = ['font' => [ 'bold' => true ]];
        $data['A'] = ['alignment' => ['horizontal' => 'center']];
        $data['F'] = ['font' => ['bold' => true]];
        $data['G'] = ['font' => [
                'bold' => true,
                'color' => ['argb' => '000000']
            ]
        ];
        $data['H'] = ['font' => [
                'bold' => true,
                'color' => ['argb' => '000000']
            ]
        ];

        return $data;
    }
}
