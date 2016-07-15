<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:22 AM
 */

namespace ResultSetTable\Decorators;


class Paginate extends Decorator
{
    protected $baseUrl ='';

    protected $wrapper = '<div class="panel panel-{panelType}">
    <div class="panel-heading">
        <div class="pull-right form-inline">
            {filter} {downloadTable} {actionButtons} {itemsPerPage}
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

    public function decorate()
    {

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
        $html = '<div class="rst-filter-table"><form action="'.$this->getBaseUrl().'" class="form form-horizontal">';

        foreach( $this->table->getColumns() as $column ) {
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
        $this->baseUrl = filter_var($baseUrl, FILTER_SANITIZE_URL);
    }


}