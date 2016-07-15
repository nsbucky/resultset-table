<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:31 AM
 */

namespace ResultSetTable\Columns;


use Assert\Assertion;
use ResultSetTable\Traits\Content;
use ResultSetTable\Traits\Label;
use ResultSetTable\Traits\Title;
use ResultSetTable\Traits\Url;

class PopOver extends Column
{
    use Url;
    use Label;
    use Title;
    use Content;

    /**
     * @var string
     */
    protected $linkCss = 'btn btn-default';

    /**
     * @var string
     */
    protected $css = 'rst-popover-column';

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $trigger = 'hover';

    /**
     * @var mixed
     */
    protected $action;


    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( $url, $label, $title, array $configurableOptions = [ ] )
    {
        $this->configurableOptions = array_merge($this->configurableOptions, [
            'url',
            'label',
            'title',
            'trigger',
            'content',
            'action'
        ]);

        $configurableOptions['url'] = $url;
        $configurableOptions['label'] = $label;
        $configurableOptions['title'] = $title;

        parent::__construct( $configurableOptions );
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        return sprintf( '<button class="%s" data-toggle="popover" data-trigger="%s" data-title="%s" data-content="%s" data-html="true" onclick="%s">%s</button>',
            $this->linkCss,
            $this->trigger,
            $this->getTitle(),
            $this->getContent($this->dataSource),
            $this->getAction(),
            $this->getLabel($this->dataSource, $value)
        );
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        if( $this->action instanceof \Closure ) {
            $func = $this->action;

            return $func($this->dataSource);
        }

        Assertion::nullOrScalar($this->action);

        return $this->action;
    }

}