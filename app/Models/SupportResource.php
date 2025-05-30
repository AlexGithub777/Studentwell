<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportResource extends Model
{
    // table name
    protected $table = 'support_resources';

    // primary key
    protected $primaryKey = 'SupportResourceID';

    // disable timestamps
    public $timestamps = false;

    // fillable fields
    protected $fillable = [
        'ResourceTitle',
        'ResourceCategory',
        'Phone',
        'Location',
        'Description'
    ];

    // relationships
    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'ResourceCategory', 'ResourceCategoryID');
        //                                                ^ foreign key in support_resources
        //                                                                   ^ local key in resource_categories
    }
}
