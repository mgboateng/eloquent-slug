<?php
namespace MGBoateng\EloquentSlugs;

trait Slugging {

    public static function bootSlugging()
    {
        static::creating(function ($model) {
            $model->generateSlugWhenCreating();         
        });

        static::updating(function ($model) {
            $model->generateSlugWhenUpdating();
        });
    }

    /**
     * Generates a slug form source to a specified destination column from options set out in $sugOptions property
     * when ever an eloquent model is updating.
     * @return void
     */
    public function generateSlugWhenUpdating() 
    {
        $slug = $this->generateSlug();

        if ($slug) {       
            // check if check if the given slug is a modification of the existing
            $slugModified = $this->find($this->id);
      
            if ($slugModified->{$this->getDestination()} != $slug) {                             
                $latestSlug = $this->getLatestSlug($slug);
                if ($latestSlug) {
                    $slug = $this->newSlug($latestSlug, $slug);
                    $this->{$this->getDestination()} = $slug;
                } else {
                    $this->{$this->getDestination()} = $slug;
                }                                  
            }
        }
    }


    /**
     * Generates a slug form source to a specified destination column from options set out in $sugOptions property
     * when ever an eloquent model is creating.
     * @return void
     */
    public function generateSlugWhenCreating () 
    {
        $slug = $this->generateSlug();

        $latestSlug = $this->getLatestSlug($slug); 
        
        if ($latestSlug) {
            $slug = $this->newSlug($latestSlug, $slug);
        }
        $this->{$this->getDestination()} = $slug;   
    }

    /**
     * Determining with input source to use in generating slugs
     * @return string
     */
    public function generateSlug() 
    {         
        if ($this->{$this->getDestination()}) {
            $slug = $this->slugInput($this->{$this->getDestination()});
        } else {
            $slug = $this->slugInput($this->{$this->getSource()});
        }
        return $slug;
    }

    /**
     * Generates a new unique slug from inputs by 
     * @param  string $latestSlug latest insert slug 
     * @param  sting $slug     
     * @return sting             
     */
    public function newSlug($latestSlug, $slug) 
    {
        $pieces = explode($this->getSeperator(), $latestSlug);
        $number = intval(end($pieces));
        $slug .= $this->getSeperator() . ($number + 1);
        return $slug;
    }

    /**
     * Returns the last saved generated slug from the given parameter
     * @param  string $slug
     * @return string    
     */
    public function getLatestSlug($slug) 
    {
        $seperator = $this->getSeperator();
        $destination = $this->getDestination();
        return $this->whereRaw("$destination RLIKE '^$slug({$seperator}[0-9]+)?$'")
            ->latest('id')
            ->pluck('slug')
            ->first();
    }

    /**
     * Generates a slug from the input given input
     * @param  string $input 
     * @return string      
     */
    public function slugInput($input)
    {
        return str_slug($input, $this->getSeperator());
    }

    /**
     * Returns the value of source field
     * @return string
     */
    public function getSource() 
    {  
        return $this->slugSettings['source'];        
    }

    /**
     * Returns the value of destination field
     * @return string
     */
    public function getDestination() 
    {
        return $this->slugSettings['destination'];
    }

    /**
     * Returns the seperator to be used in generationg slugs
     * @return string
     */
    public function getSeperator() 
    {
        return $this->slugSettings['seperator'];
    }
}

