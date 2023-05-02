<?php
namespace App\Http\Controllers\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Uuids
{ 
    public function initializeUuidTrait(): void
    {
        $this->setIncrementing(false);
        $this->setKeyType('integer');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUuidTrait()
    {
        self::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), time().rand(1000,9999) );
        });
    }
}
?>