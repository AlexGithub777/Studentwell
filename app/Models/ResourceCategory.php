<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    // table name
    protected $table = 'resource_categories';
    // primary key
    protected $primaryKey = 'ResourceCategoryID';

    // disable timestamps
    public $timestamps = false;

    // fillable fields
    protected $fillable = [
        'ResourceCategoryID',
        'Name'
    ];

    // relationships
    public function resources()
    {
        return $this->hasMany(SupportResource::class, 'ResourceCategoryID');
    }
}
