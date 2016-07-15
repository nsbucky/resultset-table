<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:30 AM
 */

namespace ResultSetTable\Columns;


use Assert\Assertion;
use ResultSetTable\Traits\Label;
use ResultSetTable\Traits\Url;

/**
 * Class Link
 * @package ResultSetTable\Columns
 */
class Link extends Column
{
    use Url;
    use Label;

    /**
     * @var string
     */
    protected $linkCss = '';

    /**
     * @var string
     */
    protected $css = 'rst-link-column';

    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( $url, $label, array $configurableOptions = [ ] )
    {
        $this->configurableOptions[] = 'url';
        $this->configurableOptions[] = 'label';
        $this->configurableOptions[] = 'linkCss';

        $configurableOptions['url'] = $url;
        $configurableOptions['label'] = $label;

        parent::__construct( $configurableOptions );
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        return sprintf(
            '<a href="%s" class="%s">%s</a>',
            $this->getUrl($this->dataSource),
            $this->linkCss,
            $this->getLabel($this->dataSource, $value)
        );
    }

}