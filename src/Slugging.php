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
                $this->{$this->getDestination()} = $this->getLatestSlug($slug);                         
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
        $this->{$this->getDestination()} = $this->getLatestSlug($slug);   
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
     * Returns the last saved generated slug from the given parameter
     * @param  string $slug
     * @return string    
     */
    
    /**
     * Generating unique slug from database
     * @param  [string] $slug [initial slug generated from the source input]
     * @return [string]       
     */
    public function getLatestSlug($slug) 
    {
        $seperator = $this->getSeperator();
        $destination = $this->getDestination();

        // search database if slug exists
        $results = $this->where($destination, 'like', $slug . "%")
            ->pluck($destination)
            ->toArray();

        // return initial slug in no match is found
        if (empty($results)) {
            return $slug;
        }

        $searchString = "/^%s(%s[0-9]+)?$/"; // prepare regexp string to find exact match form return results
  
        $search = sprintf($searchString, $slug, $seperator);
        $matches = preg_grep($search, $results);

        foreach ($matches as $match) {
            $pieces = explode($seperator, $match);
            $endvalues[] = intval(end($pieces));            
        }
        $lastDigit = max($endvalues);
        return $slug . $seperator . ++$lastDigit;           
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

