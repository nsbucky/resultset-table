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

}