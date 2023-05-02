<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipments extends Model
{
    use HasFactory,SoftDeletes;

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'In Active',
    ];

    public $table = 'shipments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'order_id',
        'shipment_order_id',
        'shipment_order_number',
        'shipment_id',
        'channel_id',
        'pickup_status',
        'status',
        'status_code',
        'complete',
        'awb_code',
        'courier_id',
        'location_name',
        'cod',
        'tracking_id',
        'shiporder_id',
        'shipment_staus_id',
        'shipping_method_id',
        'data',
        'courier_data',
        'awb_data',
        'pickup_data',
        'cancel_data',
        'pickup_request_data',
        'track_data',
        'generate_manifest_url',
        'print_manifest_url',
        'label_url',
        'invoice_url',
        'track_url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
