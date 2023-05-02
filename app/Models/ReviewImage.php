<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class ReviewImage extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'review_images';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'review_id',
        'src',
        'status',
    ];

    public function getSrcAttribute()
    {
        $client_id = Config::get('client_id');
        return "/storage/".$client_id."/review/".$this->attributes['src'];
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
