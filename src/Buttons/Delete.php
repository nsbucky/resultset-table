<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 7:20 AM
 */

namespace ResultSetTable\Buttons;


/**
 * Class Delete
 * @package ResultSetTable\Buttons
 */
class Delete extends Submit
{
    /**
     * @var string
     */
    protected $css = 'btn btn-xs btn-danger';

    /**
     * @var string
     */
    protected $label = '<em class="fa fa-times-circle"></em>';

    /**
     * @var string
     */
    protected $confirm = 'Are you sure?';

    /**
     * Submit constructor.
     * @param array $url
     * @param string $label
     * @param string $method
     * @param array $configurableOptions
     */
    public function __construct( $url, $label = 'Delete', $method = 'post', array $configurableOptions = [ ] )
    {
        $configurableOptions['hiddenFields']['_method'] = 'DELETE';

        parent::__construct($url, $label, $method, $configurableOptions);
    }

}