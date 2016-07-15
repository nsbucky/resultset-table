<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 7:21 AM
 */

namespace ResultSetTable\Buttons;


/**
 * Class View
 * @package ResultSetTable\Buttons
 */
class View extends Link
{
    /**
     * @var string
     */
    protected $css = 'btn btn-xs btn-primary';

    /**
     * Link constructor.
     */
    public function __construct( $url, $label = '<em class="fa fa-search-plus"></em>', array $configurableOptions = [ ] )
    {
        $configurableOptions['url']   = $url;
        $configurableOptions['label'] = $label;

        parent::__construct( $configurableOptions );
    }
}