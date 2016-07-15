<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 11:07 AM
 */

namespace ResultSetTable\Traits;


use Assert\Assertion;

/**
 * Class SortValue
 * @package ResultSetTable\Traits
 */
trait SortValue
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
     * @var string
     */
    protected $sortAscending = 'asc';

    /**
     * @var string
     */
    protected $sortDescending = 'desc';

    /**
     * @var string
     */
    protected $pagingIdentifier = 'page';

    /**
     * @var string
     */
    protected $itemsPerPageIdentifier = 'limit';

    /**
     * @var bool
     */
    protected $sortable = true;

    /**
     * @param $label
     * @return string
     */
    public function createSortableLink( $label )
    {
        if( ! $this->isSortable() ) {
            return $label;
        }

        $direction        = $this->getNextSortDirection();
        $currentDirection = $this->getCurrentSortDirection();

        $sortValues = [
            $this->getSortQueryStringKey()          => $this->sortableName,
            $this->getSortDirectionQueryStringKey() => $direction,
        ];

        $qs = http_build_query( array_merge( (array) $this->input, $sortValues ) );

        $base_url = array_get($_SERVER, 'REQUEST_URI');

        if( function_exists( 'url' )) {
            $base_url = url()->current();
        }

        $url = $base_url . '?' . $qs;

        $icon = '';
        $asc  = '<i class="fa fa-chevron-up"></i>';
        $desc = '<i class="fa fa-chevron-down"></i>';

        if( $this->isBeingSorted() ) {
            if( $currentDirection == $this->getSortAscending() ) {
                $icon = $asc;
            }

            if( $currentDirection == $this->getSortDescending() ) {
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
                $this->input,
                $this->getSortDirectionQueryStringKey(),
                $this->getSortAscending()
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
            return $this->getSortAscending();
        }

        if( $sortDirection == $this->getSortAscending() ) {
            // next direction
            return $this->getSortDescending();
        }

        if( $sortDirection == $this->getSortDescending() ) {
            // next direction
            return $this->getSortAscending();
        }

        return $sortDirection;
    }

    /**
     * @return bool
     */
    public function isBeingSorted()
    {
        if( ! array_has( $this->input, $this->sortQueryStringKey )) {
            return false;
        }

        $currentlyBeingSorted = array_get( $this->input, $this->sortQueryStringKey );

        if( ! $currentlyBeingSorted ) {
            return false;
        }

        return strcasecmp( $this->getSortableName(), $currentlyBeingSorted ) == 0;
    }

    /**
     * @return string
     */
    public function getSortableName()
    {
        return $this->sortableName;
    }

    /**
     * @return string
     */
    public function getSortQueryStringKey()
    {
        return $this->sortQueryStringKey;
    }

    /**
     * @param string $sortQueryStringKey
     */
    public function setSortQueryStringKey( $sortQueryStringKey )
    {
        Assertion::string( $sortQueryStringKey );
        
        $this->sortQueryStringKey = $sortQueryStringKey;
    }

    /**
     * @return string
     */
    public function getSortDirectionQueryStringKey()
    {
        return $this->sortDirectionQueryStringKey;
    }

    /**
     * @param string $sortDirectionQueryStringKey
     */
    public function setSortDirectionQueryStringKey( $sortDirectionQueryStringKey )
    {
        Assertion::string( $sortDirectionQueryStringKey );
        
        $this->sortDirectionQueryStringKey = $sortDirectionQueryStringKey;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * @param string $sortDirection
     */
    public function setSortDirection( $sortDirection )
    {
        Assertion::string( $sortDirection );
        
        $this->sortDirection = $sortDirection;
    }

    /**
     * @return string
     */
    public function getSortAscending()
    {
        return $this->sortAscending;
    }

    /**
     * @param string $sortAscending
     */
    public function setSortAscending( $sortAscending )
    {
        Assertion::string( $sortAscending );
        
        $this->sortAscending = $sortAscending;
    }

    /**
     * @return string
     */
    public function getSortDescending()
    {
        return $this->sortDescending;
    }

    /**
     * @param string $sortDescending
     */
    public function setSortDescending( $sortDescending )
    {
        Assertion::string( $sortDescending );
        
        $this->sortDescending = $sortDescending;
    }

    /**
     * @return string
     */
    public function getPagingIdentifier()
    {
        return $this->pagingIdentifier;
    }

    /**
     * @param string $pagingIdentifier
     */
    public function setPagingIdentifier( $pagingIdentifier )
    {
        Assertion::string( $pagingIdentifier );
        
        $this->pagingIdentifier = $pagingIdentifier;
    }

    /**
     * @return string
     */
    public function getItemsPerPageIdentifier()
    {
        return $this->itemsPerPageIdentifier;
    }

    /**
     * @param string $itemsPerPageIdentifier
     */
    public function setItemsPerPageIdentifier( $itemsPerPageIdentifier )
    {
        Assertion::string( $itemsPerPageIdentifier );
        $this->itemsPerPageIdentifier = $itemsPerPageIdentifier;
    }

    /**
     * @param string $sortableName
     */
    public function setSortableName( $sortableName )
    {
        Assertion::string( $sortableName );
        $this->sortableName = $sortableName;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @param boolean $sortable
     */
    public function setSortable( $sortable )
    {
        $this->sortable = (bool) $sortable;
    }
}