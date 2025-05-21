<?php

namespace App\Models\template;

use Illuminate\Database\Eloquent\Model;

class TemplateCategory extends Model
{

    protected $table = 'template_categories';

    protected $fillable = ['name', 'slug'];

    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
