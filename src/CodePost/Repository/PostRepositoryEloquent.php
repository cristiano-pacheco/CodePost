<?php


namespace CodePress\CodePost\Repository;


use CodePress\CodeDatabase\AbstractRepository;
use CodePress\CodePost\Models\Post;

class PostRepositoryEloquent extends AbstractRepository implements CategoryRepositoryInterface
{
    public function model()
    {
        return Post::class;
    }
}