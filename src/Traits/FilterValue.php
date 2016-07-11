<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 11:26 AM
 */

namespace ResultSetTable\Traits;

use Illuminate\Support\Collection;

trait FilterValue
{
    /**
     * @return string
     */
    public function getFilter()
    {
        $value = array_get( $this->queryString, $this->name );

        if( ! is_scalar( $value ) ) {
            $value = '';
        }

        if( $this->filter === true ) {
            return sprintf(
                '<div class="grid-view-filter-container">
                <input type="text" name="%s" style="width:100%%" class="grid-view-filter input-small form-control" value="%s">
                </div>',
                $this->name,
                e( $value )
            );
        }
        
        if( is_scalar($this->filter)) {
            return $this->filter;
        }
        
        if( $this->filter instanceof \Closure ) {
            $func = $this->filter;
            return $func( $value );
        }

        if( $this->filter instanceof Collection ) {
            $this->filter = $this->filter->all();
        }

        return sprintf(
            '<select name="%s" class="form-control">%s</select>',
            $this->name,
            $this->buildDropDownList( (array) $this->filter, $value )
        );
        
    }

    /**
     * build a drop down list (select) from an array
     * @param array $options
     * @param string $selectedValue
     * @return string
     */
    protected function buildDropDownList( array $options, $selectedValue = null )
    {
        $optionsHtml = '';
        foreach( $options as $key => $value ) {
            if( is_array( $value ) ) {
                $optionsHtml .= sprintf(
                    '<optgroup label="%s">%s</optgroup>',
                    $key,
                    $this->listOptions( $value, $selectedValue )
                );
                continue;
            }

            $optionsHtml .= $this->listOptions( [ $key => $value ], $selectedValue );
        }

        return $optionsHtml;
    }

    /**
     * create options tags from array
     * @param array $options
     * @param string $selectedValue
     * @return string
     */
    protected function listOptions( array $options, $selectedValue = null )
    {
        $optionsHtml = '';

        foreach( $options as $key => $value ) {
            $selected = null;

            if( $key === '' ) {
                $key = null;
            }

            if( strcmp( $selectedValue, $key ) == 0 ) {
                $selected = 'selected="selected"';
            }

            $optionsHtml .= sprintf(
                '<option value="%s" %s>%s</option>',
                e( $key ),
                $selected,
                e( $value )
            );
        }

        return $optionsHtml;
    }
}