<?php 
namespace MGBoateng\EloquentSlugs\Test;

use Illuminate\Database\Eloquent\Model;
use MGBoateng\EloquentSlugs\Slugging;

class Post extends Model 
{
    use Slugging;

    protected $slugSettings = [
        'source' => 'title',
        'destination' => 'slug',
        'seperator' => '_'
    ];    
    protected $guarded = [];
    
    protected $table = 'posts';
}