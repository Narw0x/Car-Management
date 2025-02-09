<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'registration_number',
        'is_registered',
        'searchName'
    ];

    protected $casts = [
        'is_registered' => 'boolean'
    ];


    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}