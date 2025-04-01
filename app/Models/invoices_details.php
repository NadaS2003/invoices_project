<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoices_details extends Model
{
    use HasFactory;

    protected $fillable =[
        'id_Invoice',
        'Invoices_number',
        'product',
        'Section',
        'Status',
        'Value_Status',
        'note',
        'user' ,
        'Payment_Date'
    ];
}
