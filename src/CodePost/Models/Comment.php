<?php


namespace CodePress\CodePost\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

class Comment extends Model
{
    protected $table = "codepress_comments";

    protected $fillable = ['content','post_id'];

    protected $validator;

    public function isValid()
    {
        $validator = $this->validator;

        $validator->setRules([
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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}