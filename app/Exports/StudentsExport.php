<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return User::orderByDesc('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'RankPro ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Address',
            'School',
            'Class',
            'Section',
            'Father Name',
            'Father Mobile',
            'Mother Name',
            'Mother Mobile',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->rankpro_id,
            $row->first_name,
            $row->last_name,
            $row->email_id,
            $row->mobile_number,
            $row->address,
            $row->school_name,
            $row->class_name,
            $row->section_name,
            $row->father_full_name,
            $row->father_mobile_number,
            $row->mother_full_name,
            $row->mother_mobile_number,
            $row->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
