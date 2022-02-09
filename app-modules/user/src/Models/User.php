<?php

namespace Modules\User\Models;

use App\Traits\HasUuid;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [	
        'id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'pin',
        'verified',
        'account_balance',
        'password',
        'pin_hash',
        'wallet_id'
    ];

    protected $hidden = [
    ];
    const pin = 'pin';
    const pin_hash= 'pin_hash';
    const password = 'password';
    const wallet_id = 'wallet_id';

    protected static function boot(){
        parent::boot();
        self::creating(function ($model){
            $r= (int)random_int(1001,99998);
            $model->{self::pin} = $r;
            $model->{self::pin_hash} = Hash::make($r);
            $model->{self::password} = Hash::make($model['password']);
            $model->{self::wallet_id} = (string)rand(999999999,100000000);
        });
    }

    protected $casts = [
        'account_balance' => 'double',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    protected $date = [
        'created_at','updated_at','deleted_at'
    ];

    protected $columns = ['pin']; // add all columns from you table

    public function scopeExclude($query, $value = []) 
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }
   
    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function money_sent(): HasMany
    {
        return $this->hasMany(Transaction::class,'sender_id','id');
    }
    
     /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function money_received(): HasMany
    {
        return $this->hasMany(Transaction::class,'receiver_id','id');
    }
}
