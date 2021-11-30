<?php

namespace App;

use App\Http\Traits\TranslatableTrait;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use TranslatableTrait;
    protected $table = 'news';
    protected $fillable = ['category_id', 'thumb_img', 'date','core_responding_id'];
    protected $translatable = ['title', 'description', 'slug', 'tag'];

    public function getNameAttribute()
    {
        return $this->getTranslation('title', '1');
    }

    public function category()
    {
        return $this->belongsTo('Modules\Categories\Models\Category');
    }
}
