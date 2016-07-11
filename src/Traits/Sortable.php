<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 11:07 AM
 */

namespace ResultSetTable\Traits;


trait Sortable
{
    /**
     * What variable name to send back to server for field to sort on
     * @var string
     */
    protected $sortQueryStringKey = 'sort';

    /**
     * variable name to send back to server to indicate sorting direction
     * @var string
     */
    protected $sortDirectionQueryStringKey = 'sort_dir';

    /**
     * @var string
     */
    protected $sortableName = '';

    /**
     * @var string
     */
    protected $sortDirection = 'asc';

    /**
     * @param $label
     * @return string
     */
    protected function createSortableLink( $label )
    {
        $direction        = $this->getNextSortDirection();
        $currentDirection = $this->getCurrentSortDirection();
        $limit            = $this->table->getItemsPerPageIdentifier();

        $sortValues = [
            $this->table->getSortQueryStringKey()          => $this->sortableName,
            $this->table->getSortDirectionQueryStringKey() => $direction,
            $this->table->getPagingIdentifier()            => $this->table->getCurrentPage(),
            $limit                                         => array_get( $this->queryString, $limit ),
        ];

        $qs = http_build_query( array_merge( $this->queryString, $sortValues ) );

        $url = $this->table->getBaseUrl() . '?' . $qs;

        $icon = '';
        $asc  = '<i class="fa fa-chevron-up"></i>';
        $desc = '<i class="fa fa-chevron-down"></i>';

        if( $this->isBeingSorted() ) {
            if( $currentDirection == self::SORT_ASCENDING ) {
                $icon = $asc;
            }

            if( $currentDirection == self::SORT_DESCENDING ) {
                $icon = $desc;
            }
        }

        return sprintf( '<a href="%s" class="grid-view-sort-%s">%s %s</a>', $url, $currentDirection, $icon, $label );
    }

    /**
     * @return string
     */
    public function getCurrentSortDirection()
    {
        return strtolower(
            array_get(
                $this->queryString,
                $this->table->getSortDirectionQueryStringKey(),
                self::SORT_ASCENDING
            )
        );
    }

    /**
     * Get sort direction for column based on input
     * @return string
     */
    public function getNextSortDirection()
    {
        $sortDirection = $this->getCurrentSortDirection();

        if( !$this->isBeingSorted() ) {
            return self::SORT_ASCENDING;
        }

        if( $sortDirection == self::SORT_ASCENDING ) {
            // next direction
            return self::SORT_DESCENDING;
        }

        if( $sortDirection == self::SORT_DESCENDING ) {
            // next direction
            return self::SORT_ASCENDING;
        }

        return $sortDirection;
    }

    /**
     * @return bool
     */
    public function isBeingSorted()
    {
        $currentlyBeingSorted = array_get( $this->queryString, $this->sortQueryStringKey );

        return strcasecmp( $this->getSortableName(), $currentlyBeingSorted ) == 0;
    }

    /**
     * @return string
     */
    public function getSortableName()
    {
        return $this->sortableName;
    }
}