<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Laravel\Passport\HasApiTokens;
use App\Notifications\MailResetPasswordNotification;
use \DateTimeInterface;
use Carbon\Carbon;
use Hash;
use Config;

class User extends Authenticatable implements HasMedia
{
    use SoftDeletes, Notifiable, InteractsWithMedia, HasFactory, HasUUID, HasApiTokens;

    public $table = 'users';
    protected $uuidFieldName = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public const TAX_EXEMPT_RADIO = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public const BLOCKED_RADIO = [
        '1' => 'UnBlock',
        '0' => 'Block',
    ];

    public const ACCEPT_MARKETING_RADIO = [
        '1' => 'Subscribe',
        '0' => 'Not subscribed',
    ];

    public const SMS_NOTIFICATION_STATUS_RADIO = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public const IS_VERIFIED_RADIO = [
        '1' => 'Verified',
        '0' => 'Unverified',
    ];

    public const EMAIL_NOTIFICATION_STATUS_RADIO = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public const GENDER_RADIO = [
        'M' => 'Male',
        'F' => 'Female',
        'T' => 'Transgender',
    ];

    protected $appends = [
        'pics',
        'fullname',
        'sociallogin',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'last_name',
        'mobile',
        'country_id',
        'email',
        'otp',
        'email_verified_at',
        'password',
        'gender',
        'google',
        'facebook',
        'is_verified',
        'company',
        'email_notification_status',
        'sms_notification_status',
        'blocked',
        'accept_marketing',
        'total_spent',
        'total_orders',
        'tags',
        'note',
        'tax_exempt',
        'remember_token',
        'image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $email = request()->email;
        $this->notify(new MailResetPasswordNotification($token, $email));
    }

    public function getPicsAttribute()
    {
        $file = $this->getMedia('pics')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getFullnameAttribute()
    {
        if($this->attributes['last_name'] != ''){
            return $this->attributes['name']. ' '. $this->attributes['last_name'];
        } else {
            return $this->attributes['name'];
        }
    }

    public function roles()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function store()
    {
        return $this->hasMany(UserStore::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function getSocialloginAttribute()
    {   
        $sociallogin = false;
        if(isset($this->attributes['password']) && $this->attributes['password'] == null)
        {
            $sociallogin = true;
        }
        return $sociallogin;
    }

    public function getTotalOrdersAttribute()
    {   
        return $this->hasMany(Order::class)->count();
    }

    public function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

    public function getImageAttribute()
    {   
        $profile = "/images/avatars/11-small.png";
        if($this->attributes['image'] != null)
        {
            $path = '/storage/'.Config::get('client_id').'/users/'.$this->attributes['id'].'/'.$this->attributes['image'];
            if(file_exists(public_path($path)))
            {
                $profile = $path;  
            }
        }
        return $profile;
    }

    public function discount_user()
    {
        return $this->hasMany(DiscountUser::class,'user_id','id');
    }

}
