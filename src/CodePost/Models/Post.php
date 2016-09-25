<?php


namespace CodePress\CodePost\Models;


use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

class Post extends Model implements SluggableInterface
{

    use SluggableTrait;

    protected $table = "codepress_posts";

    protected $fillable = ['title', 'content', 'slug'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
        'unique' => true
    ];

    protected $validator;

    public function isValid()
    {
        $validator = $this->validator;

        $validator->setRules([
            'title' => 'required|max:255',
            'content' =>'required'
        ]);

        $validator->setData($this->getAttributes());

        if($validator->fails()){
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function getValidator()
    {
        return $this->validator;
    }


    public function categories()
    {
        return $this->morphToMany('\CodePress\CodeCategory\Models\Category', 'categorizable', 'codepress_categorizables');
    }

    public function tags()
    {
        return $this->morphToMany('\CodePress\CodeTag\Models\Tag', 'taggable', 'codepress_taggables');
    }
}