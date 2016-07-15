<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:29 AM
 */

namespace ResultSetTable\Columns;


use ResultSetTable\Contracts\HasScript;

/**
 * Class DateTime
 * @package ResultSetTable\Columns
 */
class DateTime extends Column implements HasScript
{
    /**
     * @var string
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected $css = 'rst-datetime-column';

    /**
     * BooleanValue constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions = [] )
    {
        $this->configurableOptions[] = 'format';

        parent::__construct( $configurableOptions );
    }


    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        if( empty( $value ) ) {
            return null;
        }

        if( function_exists('get_user_timezone')) {
            $timezone = get_user_timezone();

            if( ! $timezone instanceof \DateTimeZone ) {
                return $value;
            }

            if( $value instanceof \DateTime ) {
                $value->setTimezone( $timezone );

                return $value->format( $this->format );
            }
        }


        try {

            $date = new \DateTime( $value );
            return $date->format( $this->format );

        } catch( \Exception $e ) {

            return $e->getMessage();

        }
    }

    /**
     * @return string
     */
    public function getScriptKey()
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function getScript()
    {
        ob_start();
    ?>
        <script type="text/javascript">

            $('.datetimeColumn').daterangepicker({
                format: "YYYY-MM-DD",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                    'Month To Date': [moment().startOf('month'), moment()],
                    'Year To Date': [moment().startOf('year'), moment()]
                }
            });

        </script>

    <?php

        return ob_get_contents();
    }

}