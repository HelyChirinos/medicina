<?php

namespace App\Imports;

use App\Models\Diario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class DiarioImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Diario([
            'f_operacion'=>($row['f_operacion']) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_operacion']))->format('Y-m-d') : null,
            'f_valor'=>($row['f_valor']) ? Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_valor']))->format('Y-m-d'): null,            
            'codigo'=>$row['codigo'],
            'no_doc'=>substr($row['no_doc'],6),
            'concepto'=>$row['concepto'],
            'importe'=>(float)str_replace(",",".",str_replace(",","",$row['importe'])),
            'oficina'=>substr($row['oficina'],-4),

        ]);
    }
}
