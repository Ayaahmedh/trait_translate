<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Languages\Models\Local;
use \Modules\Languages\Models\Translation;
use \LaravelLocalization;

trait TranslatableTrait
{
    public $locale;
    var $translationRow = [];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $toAppend = ['ar', 'en'];
        $this->appends = ($this->appends != null) ? array_merge($this->appends, $toAppend) : $toAppend;
    }


    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($model, $locale = null) {
            if (!$locale) {
                $locale = (new self)->defaultLocale();
            }
            $model->translationRow = Translation::where("model", get_class($model))->where("model_id", $model->id)->get();
        });

    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'model_id')->where('model', get_class($this));
    }

    public function getEnAttribute()
    {
        $res = [];
        foreach ($this->translatable as $key) {
            $res[$key] = $this->getTranslation($key, 1);
        }
        return $res;
    }

    public function getArAttribute()
    {
        $res = [];
        foreach ($this->translatable as $key) {
            $res[$key] = $this->getTranslation($key, 2);
        }
        return $res;
    }

    public function defaultLocale()
    {
        return locales()->where('code', app()->getLocale())->first()->id;
    }

    public function __get($key)
    {
        if (isset($this->translatable) && in_array($key, $this->translatable) && $key != 'translationRow') {
            //translate and return
            return $this->getTranslation($key);
        }
        return parent::__get($key);
        //don't translate, call parent

    }

    public function getTranslation($key, $locale = NULL)
    {
        if (!$locale) {
            $locale = $this->defaultLocale();
        }
        if (count($this->translationRow) == 0) {
            return "";
        }
        if (!$this->translationRow->where('attribute', $key)->first())
            return "";

        return $this->translationRow->where('attribute', $key)->where('locale_id', $locale)->first()->value;
    }

    public function setTranslation($key, $value, $locale = NULL)
    {
        if (!in_array($key, $this->translatable))
            return false;
        if (!$locale) {
            $locale = $this->defaultLocale();
        }
        $model_id = $this->id;

        $translation = Translation::where("model", get_class($this))->where("model_id", $model_id)->where("attribute", $key)->where("locale_id", $locale)->first();

        if (!$translation) {
            $translation = new Translation;
            $translation->model = get_class($this);
            $translation->model_id = $model_id;
            $translation->attribute = $key;
            $translation->locale_id = $locale;
            $translation->value = $value;
        } else {
            $translation->value = $value;
        }

        return $translation->save();
    }

    public function toJson($locale = NULL)
    {
        if (!$locale) {
            $locale = $this->defaultLocale();
        }
        $array = $this->toArray();
        if (isset($this->translatable)) {
            foreach ($this->translatable as $value) {
                $array[$value] = $this->getTranslation($value, $locale);
            }
        }
        return json_encode($array);
    }

    public function translateAttributes(array $values)
    {
        $locals = locales()->pluck('id', 'code')->toArray();
        foreach ($values as $localeName => $items) {
            if (in_array($localeName, array_keys($locals))) {
                foreach ($items as $attribute => $value) {
                    $this->setTranslation($attribute, $value, $locals[$localeName]);
                }
            }
        }
    }

    public function deleteTranslations($locale = null)
    {
        $model_id = $this->id;
        $translations = Translation::where("model", get_class($this))->where("model_id", $model_id);
        if ($locale != null) {
            $translations->whereIn("locale_id", $locale);
        }
        $translations->delete();
    }

    public static function makeSlug($string, $locale = null)
    {
        if (!$locale) {
            $locale = (new self)->defaultLocale();
        }
        $slug = make_slug($string);
        $slug = preg_replace('/-[0-9]*$/', '', $slug);
        $record = Translation::where('model', get_class(new self))
            ->where('locale_id', $locale)
            ->where('attribute', 'slug')
            ->where('value', 'REGEXP', $slug . '?-?([0-9]*$)')
            ->latest('id')->first();
        if ($record)
            $slug = increment_string($record->value);

        return $slug;
    }

    public function getCasts()
    {
        return $this->casts;
    }

    public function getTranslatable()
    {
        return $this->translatable;
    }
}
