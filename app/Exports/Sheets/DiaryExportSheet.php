<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    WithTitle,
    WithMapping,
    WithHeadings,
    WithStrictNullComparison,
    // ShouldAutoSize,
    WithStyles,
    WithCustomStartCell,
    WithEvents
};

class DiaryExportSheet implements 
    FromArray, 
    WithTitle, 
    WithMapping, 
    WithHeadings, 
    WithStrictNullComparison,
    // ShouldAutoSize,
    WithStyles,
    WithCustomStartCell,
    WithEvents
{
    private $diaries, $title, $headers, $mainTitle;

    public function __construct($diaries = null, $title = null, $headers, $mainTitle = null)
    {
        $this->diaries    = $diaries;
        $this->title      = $title;
        $this->headers    = $headers;
        $this->mainTitle  = $mainTitle;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->diaries;
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
        return [
            ['AGENDA SEMANAL'],
            ['MINISTERIO DEL PODER POPULAR PARA LA CIENCIA Y TECNOLOGIA'],
            [$this->mainTitle],
            ['empty'],
            $this->headers
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Titulo
                $event->sheet->mergeCells('A3:K3');
                $event->sheet->mergeCells('A4:K4');
                $event->sheet->mergeCells('A5:K5');

                // Height celda (Altura)
                $event->sheet->getDelegate()->getRowDimension('7')->setRowHeight(100);

                for ($i = 8; $i < (count($this->diaries) + 8); $i++) {
                    $event->sheet->getDelegate()->getRowDimension("$i")->setRowHeight(100);
                }

                // No funcionan cuando la clase ShouldAutoSize esta implementada
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(80);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        
        $countCells = (count($this->diaries) + 7);
        $range      = "A8:K$countCells";
        $rangeBorder= "A7:K$countCells";

        $data['3:5'] = [
            'font' => [
                'name' => 'Comic Sans MS',
                'size' => '20',
                'bold' => true,
                'color' => array('rgb' => '000000')
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ];

        $data['6'] = [
            'font' => [
                'color' => array('rgb' => 'ffffff')
            ]
        ];

        $data["$range"] = [
            'font' => [
                'name' => 'Comic Sans MS',
                'bold' => true,
                'color' => array('rgb' => '000000')
            ]
        ];

        $data['7'] = [
            'fill' => [
                'fillType' => 'solid',
                'color' => array('rgb' => "14B8A6")
            ],
            'font' => [
                'name' => 'Comic Sans MS',
                'bold' => true,
                'color' => array('rgb' => 'ffffff')
            ]
        ];

        $data['L'] = [
            'font' => [
                'color' => array('argb' => 'ffffff')
            ]
        ];

        $data["$rangeBorder"] = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                ],
            ]
        ];

        return $data;
    }
}
