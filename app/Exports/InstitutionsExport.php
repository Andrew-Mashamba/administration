<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InstitutionsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $institutions;

    public function __construct($institutions)
    {
        $this->institutions = $institutions;
    }

    public function collection()
    {
        return $this->institutions;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Location',
            'Contact Person',
            'Phone',
            'Email',
            'Institution Type',
            'Status',
            'Alias',
            'Database Name',
            'Institution ID',
            'Manager Email',
            'Manager Phone',
            'IT Email',
            'IT Phone',
            'Created At',
            'Updated At',
            'Deleted At'
        ];
    }

    public function map($institution): array
    {
        return [
            $institution->id,
            $institution->name,
            $institution->location,
            $institution->contact_person,
            $institution->phone,
            $institution->email,
            $institution->institution_type,
            $institution->status,
            $institution->alias,
            $institution->db_name,
            $institution->institution_id,
            $institution->manager_email,
            $institution->manager_phone_number,
            $institution->it_email,
            $institution->it_phone_number,
            $institution->created_at->format('Y-m-d H:i:s'),
            $institution->updated_at->format('Y-m-d H:i:s'),
            $institution->deleted_at ? $institution->deleted_at->format('Y-m-d H:i:s') : ''
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER, // ID
            'E' => NumberFormat::FORMAT_TEXT,  // Phone
            'K' => NumberFormat::FORMAT_TEXT,  // Institution ID
            'M' => NumberFormat::FORMAT_TEXT,  // Manager Phone
            'O' => NumberFormat::FORMAT_TEXT,  // IT Phone
        ];
    }
} 