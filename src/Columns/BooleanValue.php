<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:23 AM
 */

namespace ResultSetTable\Columns;

use Assert\Assertion;

/**
 * Class BooleanValue
 * @package ResultSetTable\Columns
 */
class BooleanValue extends Column
{
    /**
     * @var string
     */
    protected $trueLabel = 'Yes';

    /**
     * @var string
     */
    protected $falseLabel = 'No';

    /**
     * @var array
     */
    protected $trueValues = [ 'yes', 'true', '1', 'on' ];

    /**
     * @var array
     */
    protected $falseValues = [ 'no', 'false', '0', 'off', 'null' ];

    /**
     * @var array
     */
    protected $filter = [ '' => '', '0' => 'No', '1' => 'Yes' ];

    /**
     * @var bool
     */
    protected $asHtmlLabel = true;

    /**
     * @var bool
     */
    protected $raw = true;

    /**
     * BooleanValue constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions = [] )
    {
        foreach(['trueLabel','falseLabel','trueValues','falseValues','filter','asHtmlLabel'] as $allowed) {
            $this->configurableOptions[] = $allowed;
        }

        parent::__construct( $configurableOptions );
    }


    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        Assertion::isArray($this->trueValues);
        Assertion::isArray($this->falseValues);
        Assertion::scalar($value);

        // check and see if value is one of these:
        // 1, true, yes
        $labelHtml = '<span class="label %s">%s</span>';
        $labelCss  = 'label-default';

        if( in_array( strtolower( (string) $value ), $this->trueValues, true ) ) {
            $value    = $this->trueLabel;
            $labelCss = 'label-success';
        }

        if( in_array( strtolower( (string) $value ), $this->falseValues, true ) || empty( $value ) ) {
            $value    = $this->falseLabel;
            $labelCss = 'label-danger';
        }

        if( !$this->asHtmlLabel ) {
            return $value;
        }

        return sprintf( $labelHtml, $labelCss, e( $value ) );
    }

}