<?php

namespace App\Models\template;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{

    protected $table = 'templates';

    protected $fillable = ['template_category_id', 'name', 'folder_name', 'thumbnail',  'default_content', 'default_styles', 'status',];

    protected $casts = [
        'default_styles' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }
}
