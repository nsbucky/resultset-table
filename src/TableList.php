<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 11:51 AM
 */

namespace ResultSetTable;


use Assert\Assertion;

class TableList extends Table
{

    /**
     * @return string
     */
    public function render()
    {
        $tbody = [ ];

        foreach( $this->columns as $column ) {

            $column->setDataSource($this->dataSource);

            $tbody[] = sprintf('<tr><th>%s</th><td>%s</td></tr>', $column->getHeader(), $column->render());

        }

        return sprintf(
            '<table class="%s" id="%s"><thead><tr><th>Label</th><th>Value</th></tr></thead><tbody>%s</tbody></table>%s',
            $this->getTableCss(),
            $this->getTableId(),
            implode( PHP_EOL, $tbody ),
            implode( PHP_EOL, $this->buildScripts() )
        );
    }
}