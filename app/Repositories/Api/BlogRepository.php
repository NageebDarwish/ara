<?php

namespace App\Repositories\Api;

use App\Models\Blog;
use App\Models\BlogCategory;

class BlogRepository
{
    private $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

       public function all()
    {
          $latestIds = $this->model->orderBy('created_at', 'desc')
        ->take(3)
        ->pluck('id');
    
        return $this->model->orderBy('created_at', 'desc')
            ->when($latestIds->isNotEmpty(), function($query) use ($latestIds) {
                return $query->whereNotIn('id', $latestIds);
            })
        ->get();
    }

    public function latest()
    {
        return $this->model->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
    
     public function find($id)
    {
        return $this->model->findOrFail($id);
    }
    
    public function related($id)
    {
        $blog=$this->model->findOrFail($id);
        return $this->model->where('id','!=',$id)
            ->where('blog_category_id',$blog->blog_category_id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }
    
    
    public function categories()
    {
        return BlogCategory::all();
    }

 
    public function categoryAll($ids)
    {
        $latestIds = $this->model->orderBy('created_at', 'desc')
        ->take(3)
        ->pluck('id');

        return $this->model->whereHas('category',function($q)use($ids){
            $q->whereIn('id',$ids);
        })->orderBy('created_at', 'desc')
            ->when($latestIds->isNotEmpty(), function($query) use ($latestIds) {
                return $query->whereNotIn('id', $latestIds);
            })
        ->get();
    }


}