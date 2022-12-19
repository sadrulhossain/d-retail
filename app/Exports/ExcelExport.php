<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements ShouldAutoSize, FromView {

    private $viewFile;
    private $data;

    public function __construct($viewFile, $data) {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    /**
     * @return array
     */
//    public function registerEvents(): array {
//        return [
//            AfterSheet::class => function(AfterSheet $event) {
//                
//            }
//        ];
//    }

    public function view(): View {

        return view($this->viewFile, $this->data);
    }

}
