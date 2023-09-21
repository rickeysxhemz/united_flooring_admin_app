<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    public function ProjectCategories()
    {
        return $this->belongsToMany(Category::class,'project_categories','project_id','category_id')->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function projectImages()
    {
        return $this->hasMany(ProjectImage::class,'project_id','id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'project_id','id');
    }
}
