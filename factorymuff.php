<?php

/**
 * Mock an model instance for tests
 */
class FactoryMuff
{

    /**
     * The origin of all words, it'll be shuffled
     */
    private static $wordlist = array();

    /**
     * Possible mail domains
     */
    private static $mail_domains = array(
        'example.com', 'dontexist.com',
        'mockdoman.com', 'emailprovider.com',
        'exampledomain.org'
    );

    /**
     * Creates and saves in db an instance
     * of Model with mock attributes
     */
    public static function create( $model, $attr = array() )
    {
        // Get the factory attributes for that model
        $attr_array =
            static::attributes_for( $model, $attr );

        // Create, save and return instance
        $obj = new $model( $attr_array );
        $obj->save();

        return $obj;
    }

    /**
     * Returns an array of mock attributes
     * for the especified model
     */
    public static function attributes_for( $model, $attr = array() )
    {
        // Prepare word list if empty
        if ( count( static::$wordlist ) == 0 ) {
            static::$wordlist = include 'wordlist.php';
            shuffle( static::$wordlist );
        }

        // Get the $factory static and check for errors
        $static_vars = get_class_vars( $model );

        if ( !$static_vars ) {
            trigger_error( "$model Model is not an valid Class for FactoryMuff" );
            return false;
        }

        if ( !isset( $static_vars['factory'] ) ) {
            trigger_error( "$model Model should have an static \$factory array in order to be created with FactoryMuff" );
            return false;
        }

        // Prepare attributes
        $attr_array = array();
        foreach ( $static_vars['factory'] as $key => $kind ) {
            $attr_array[$key] = static::generate_attr( $kind );
        }

        $attr_array = array_merge( $attr_array, $attr );

        return $attr_array;
    }

    /**
     * 'Generate' an attribute based in the wordlist
     */
    private static function generate_attr( $kind )
    {
        $result = 'muff';

        // If the kind begins with "factory|", then create
        // that object and save the relation.
        if ( substr( $kind, 0, 8 ) == 'factory|' ) {
            $related = static::create( substr( $kind, 8 ) );
            return $related->id;
        }

        // Overwise interpret the kind and 'generate' some
        // crap.
        switch ( $kind ) {

        // Pick a word and append a domain
        case 'email':
            shuffle( static::$mail_domains );
            $result = array_pop( static::$wordlist ).'@'.static::$mail_domains[0];
            break;

        // Pick some words
        case 'text':
            for ( $i=0; $i < ( (int)date( 'U' ) % 8 ) + 2; $i++ ) {
                $result .= array_pop( static::$wordlist )." ";
            }

            $result = trim( $result );
            break;

        // Pick a single word then
        case 'string':
            $result = array_pop( static::$wordlist );
            break;

            /**
             * ITS HERE: The point where you can extend
             * this bundle, and send a pull request of
             * your changes!!!
             */

        // Returns his string or number
        default:
            $result = $kind;
            break;
        }

        return $result;
    }
}
