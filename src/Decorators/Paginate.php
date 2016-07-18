<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:22 AM
 */

namespace ResultSetTable\Decorators;


use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use ResultSetTable\Table;

class Paginate extends Decorator
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $wrapper = '<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-right form-inline">
            {downloadTable} {actionButtons} {itemsPerPage}
        </div>
        <div class="panel-title"><i class="fa fa-table"></i> {tableHeaderText}</div>
        <div class="clearfix"></div>
    </div>
    <div class="grid-view-table-wrapper">{table}</div>
    <div class="panel-footer">
    <div class="pull-right">{numberOfItems}</div>
    <div>{pagerLinks}</div>
    <div class="clearfix"></div>
    </div>
    </div>';

    protected $tableHeaderText;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var string
     */
    protected $downloadTable;

    /**
     * @var string
     */
    protected $actionButtons;

    /**
     * Paginate constructor.
     * @param string $baseUrl
     */
    public function __construct( Table $table, Paginator $paginator, $baseUrl = '' )
    {
        $this->paginator = $paginator;
        $this->baseUrl   = $baseUrl;

        parent::__construct( $table );
    }

    /**
     * @return string
     */
    public function decorate()
    {
        $replace = [
            'itemsPerPage'    => $this->paginator->perPage(),
            'tableHeaderText' => $this->getTableHeaderText(),
            'numberOfItems'   => $this->paginator->total(),
            'pagerLinks'      => $this->paginator->links(),
            'table'           => $this->table->render(),
        ];

        return str_replace( array_keys( $replace ), array_values( $replace ), $this->getWrapper() );
    }

    /**
     * @param string $wrapper
     */
    public function setWrapper( $wrapper )
    {
        $this->wrapper = $wrapper;
    }

    /**
     * build div and form for searching
     * @return string
     */
    public function getFilters()
    {
        $html = '<div class="rst-filter-table"><form action="' . $this->getBaseUrl() . '" class="form form-horizontal">';

        foreach ($this->table->getColumns() as $column) {
            $html .= sprintf(
                '<div class="form-group"><label class="form-control col-sm-2">%s</label><div class="col-sm-10">%s</div></div>',
                $column->getHeader(),
                $column->getFilter()
            );
        }

        $html .= '<button type="submit"><em class="glyphicon glyphicon-search"></em> Filter</button>';

        return $html . '</form></div>';
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl( $baseUrl )
    {
        $this->baseUrl = filter_var( $baseUrl, FILTER_SANITIZE_URL );
        $this->paginator->setPath( $this->baseUrl );
    }

    /**
     * @return mixed
     */
    public function getTableHeaderText()
    {
        return $this->tableHeaderText;
    }

    /**
     * @param mixed $tableHeaderText
     */
    public function setTableHeaderText( $tableHeaderText )
    {
        $this->tableHeaderText = $tableHeaderText;
    }

    /**
     * @return mixed
     */
    public function getDownloadTable()
    {
        return $this->downloadTable;
    }

    /**
     * @param mixed $downloadTable
     */
    public function setDownloadTable( $downloadTable )
    {
        $this->downloadTable = $downloadTable;
    }

    /**
     * @return mixed
     */
    public function getActionButtons()
    {
        return $this->actionButtons;
    }

    /**
     * @param mixed $actionButtons
     */
    public function setActionButtons( $actionButtons )
    {
        $this->actionButtons = $actionButtons;
    }

    /**
     * @return string
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

}