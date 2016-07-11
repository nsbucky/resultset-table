<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:16 AM
 */

namespace ResultSetTable\Traits;


trait Configure
{
    public function configure( array $configuration )
    {
        if( ! isset($this->configurableOptions ) ) {
            throw new \RuntimeException('Please set configurableOptions variable');
        }
                
        foreach ($configuration as $key => $value) {

            if (!in_array( $key, $this->configurableOptions )) {

                throw new \RuntimeException(
                    sprintf( '%s is not a configurable option for %s', $key, static::class )
                );

            }

            $this->$key = $value;
        }
    }
}