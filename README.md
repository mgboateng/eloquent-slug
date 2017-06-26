# Generate elegant slug when saving Laravel Eloquent Models 
The package provides a trait that allows you to save a unique slugs to your database seamlessly by just specifying the 
seperator, source and destination field to generate a slug. It is very fast and very light on
resources as it makes just a since database call when creating a model and two when updating
as opposed to looping recurcively over the database to generate a unique slug.

## Installation
You can install the package through composer `composer require mgboateng/eloquent-slugs` or 
through your composer json file:
```json
{
    "require": {
        "mgboateng/eloquent-slugs" : "~1.0"          
    }
}
```
##Usage
To use the package you simply add the Slugging trait to you model and set `protected $slugOptions` 
property as below:
```php
<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use MGBoateng\EloquentSlugs\Slugging;

class Post extends Model 
{
    use Slugging;

    protected $slugSettings = [
        'source' => 'title',
        'destination' => 'slug',
        'seperator' => '-'
    ];    
}
```
The `protected $slugSettings` array sets
- The source key which indicate to field to be used as source for making slugs
- The destination key which specifies to field to store generated slugs
- The seperator key specifies the seperator to use when generating slugs. eg. '-', '_'

When you are creating a model with a settings as:
```php
protected $slugSettings = [
        'source' => 'title',
        'destination' => 'slug',
        'seperator' => '-'
    ];    
```
when you craete a model

```php
Post::create([
    'title' => 'Hello World',
    'body' => 'Here comes a great programmer'
]);
```
an output of 
```php
[
    'title' => 'Hello World',
    'slug' => 'hello-world', // or 'hello-world-1' if hello-world already exist
    'body' => 'Here comes a great programmer'
]
```
will be generated.

You could set the destination field (slug in the above example) to generate a unique slug that is
deferent from the source (title in the above example). When the destination field is directly set
it takes precedent over the source field as the source for generating slugs. eg.
```php
Post::create([
    'title' => 'Hello World',
    'slug' => 'Welcome Home'
    'body' => 'Here comes a great programmer'
]);
```
will output:

```php
[
    'title' => 'Hello World',
    'slug' => 'welcome-home', // or welcome-home-1 if welcome-home already exist
    'body' => 'Here comes a great programmer'
]
```

## License
This software is distributed under the [MIT license.](LICENSE)


