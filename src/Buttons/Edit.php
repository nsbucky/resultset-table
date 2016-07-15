<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 7:20 AM
 */

namespace ResultSetTable\Buttons;


/**
 * Class Edit
 * @package ResultSetTable\Buttons
 */
class Edit extends Link
{
    /**
     * @var string
     */
    protected $css = 'btn btn-xs btn-success';

    /**
     * Link constructor.
     */
    public function __construct( $url, $label = '<em class="fa fa-pencil"></em>', array $configurableOptions = [ ] )
    {
        $configurableOptions['url']   = $url;
        $configurableOptions['label'] = $label;

        parent::__construct( $configurableOptions );
    }

}