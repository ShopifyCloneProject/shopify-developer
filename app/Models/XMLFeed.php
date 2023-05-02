<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Section;

class XMLFeed extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'xmlfeed';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'choose1',
        'choose2',
        'default',
        'createtime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['section_name'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getSectionNameAttribute(){
        return Section::where('id',$this->attributes['choose1'])->first()->columnname;
    }
}