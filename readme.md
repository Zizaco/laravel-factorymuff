FactoryMuff (Laravel Bundle)
===========================

![factory muff poster](http://img846.imageshack.us/img846/3576/factorymuffposter.jpg)

[![ProjectStatus](http://stillmaintained.com/Zizaco/laravel-factorymuff.png)](http://stillmaintained.com/Zizaco/laravel-factorymuff)

-----------

**This package is outdated.** Even thought if you are using Laravel 3, I strongly recommend you to use the [new FactoryMuff](https://github.com/Zizaco/factory-muff) with composer.

-----------


The goal of this [Laravel Bundle](http://bundles.laravel.com/) is to enable the rapid creation of objects for the purpose of testing. Basically a "[factory\_girl\_rails](https://github.com/thoughtbot/factory_girl_rails)" simplified for use with Laravel.

### License

_Do What The Fuck You Want To Public License, Version 2, as published by Sam Hocevar. http://sam.zoy.org/wtfpl/COPYING_

Instalation
-----------

### Artisan

```
php artisan bundle:install factorymuff
```

### Bundle Registration

add the following to __application/bundles.php__

```php
'factorymuff' => array('auto' => true),
```

How it works
------------

FactoryMuff (which stands for Factory Muffin) uses a list of 2300+ words with at least 4 characters. These words are scrambled at every execution and will not repeat unless you use all the words. In this case the list is re-started and scrambled again.

Theoretically you will not need to worry about repeating values​​, unless your application has ALOT of tests to run which may cause the wordlist to restart. If this is your case, you can simply increase wordlist in _wordlist.php_

### Usage

Declare a __public static__ array called __$factory__ in your model. This array should contain the kind of values you want for the attributes.

Example:
```php
class Message extends Eloquent
{
    // Array that determines the kind of attributes
    // you would like to have
    public static $factory = array(
        'user_id' => 'factory|User',
        'subject' => 'string',
        'address' => 'email',
        'message' => 'text',
    );

    // Relashionship with user
    public function user()
    {
        return $this->belongs_to('User');
    }
```

To create model instances do the following:
```php
<?php

class TestUserModel extends PHPUnit_Framework_TestCase {

    public function __construct()
    {
        // Load FactoryMuff bundle
        Bundle::start( 'factorymuff' );
    }

    public function testSampleFactory()
    {
        // Creates a new instance
        $message = FactoryMuff::create( 'Message' );

        // Access the relationship, because attributes
        // with kind "factory|<ModelName> creates and
        // saves the <ModelName> object and return the
        // id. And now, because of eloquent we can do
        // this:
        $message->user->username;

        // And you can also get attributes for a new
        // instance
        $new_message = new Message( FactoryMuff::attributes_for( 'Message' ) )

        // For both methods (create and attributes_for
        // you can pass fixed attributes. Those will be
        // merged into the object before save.
        $muffin_message = FactoryMuff::create(
            'Message', array(
                'subject' => 'About Muffin',
                'message' => 'Its tasty!',
            ),
        );
    }
```

### Kinds of attribute suported

* string
 * Grab a random word from the wordlist. Ex: "bucket","mouse","laptop","America"
* email
 * An word from the wordlist + domain. Ex: "smart@example.com", "Brasil@nonexist.org"
* text
 * A text of about 7 words from the list. Ex: "something table underrated blackboard"
* factory|ModelName
 * Will trigger the __FactoryMuff::create__ for the given model and return it's id.
* Any thing else
 * Will be returned. Ex: kind "tuckemuffin" will become the value of the attribute in the instantiated object.


More help
---------

Read the source code. There is alot of comments there. __;)__

or contact me.
