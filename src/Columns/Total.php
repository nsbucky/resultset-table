<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:32 AM
 */

namespace ResultSetTable\Columns;


/**
 * Class Total
 * @package ResultSetTable\Columns
 */
class Total extends Column
{
    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var string
     */
    protected $format = '%d';

    /**
     * @return mixed|null|string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        if( is_numeric($value) ) {
            $this->total += $value;
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return sprintf( $this->format, $this->total );
    }
}