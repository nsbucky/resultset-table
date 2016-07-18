<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:22 AM
 */

namespace ResultSetTable\Decorators;

use Assert\Assertion;
use Illuminate\Contracts\Pagination\Presenter;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use ResultSetTable\Table;
use ResultSetTable\Traits\Tokenize;

class Paginate extends Decorator
{
    use Tokenize;
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
    <div class="pull-right">{numberOfItems} total items</div>
    <div>{pagerLinks}</div>
    <div class="clearfix"></div>
    </div>
    </div>';

    /**
     * @var string
     */
    protected $tableHeaderText;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var Presenter
     */
    protected $presentor;

    /**
     * @var boolean
     */
    protected $downloadTable = false;

    /**
     * @var string
     */
    protected $downloadKey = '_download';

    /**
     * @var string
     */
    protected $actionButtons;

    /**
     * @var string
     */
    protected $itemsPerPageIdentifier = 'limit';

    /**
     * Paginate constructor.
     * @param Paginator $paginator
     * @param string $baseUrl
     */
    public function __construct( Table $table, Paginator $paginator, $baseUrl = '' )
    {
        $this->paginator = $paginator;

        $this->setBaseUrl( $baseUrl );

        parent::__construct( $table );
    }

    /**
     * @return string
     */
    protected function decorate()
    {
        $replace = [
            'itemsPerPage'    => $this->renderItemsPerPage(),
            'tableHeaderText' => $this->getTableHeaderText(),
            'numberOfItems'   => $this->paginator->total(),
            'pagerLinks'      => $this->paginator->links( $this->getPresentor() ),
            'table'           => $this->table->render(),
            'downloadTable'   => $this->buildDownloadLink(),
            'actionButtons'   => $this->actionButtons,
        ];

        $this->createTokens($replace);

        return $this->replace( $this->getWrapper() );
    }

    /**
     * @param string $wrapper
     */
    public function setWrapper( $wrapper )
    {
        Assertion::scalar( $wrapper );
        $this->wrapper = $wrapper;
    }

    /**
     * build div and form for searching
     * @return string
     */
    public function renderFilters()
    {
        $html = '<div class="rst-filter-table"><form action="' . $this->getBaseUrl() . '" class="form form-horizontal">';

        foreach ($this->table->getColumns() as $column) {
            if( ! $column->isVisible() ) {
                continue;
            }
            
            $filter = $column->getFilter();
            
            if( empty($filter)) {
                continue;
            }
            
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
     * @return string
     */
    public function getTableHeaderText()
    {
        return $this->tableHeaderText;
    }

    /**
     * @param string $tableHeaderText
     */
    public function setTableHeaderText( $tableHeaderText )
    {
        Assertion::scalar( $tableHeaderText );
        $this->tableHeaderText = $tableHeaderText;
    }

    /**
     * @return boolean
     */
    public function isDownloadable()
    {
        return $this->downloadTable;
    }

    /**
     * @param boolean $downloadTable
     */
    public function setDownloadable( $downloadTable )
    {
        $this->downloadTable = (bool)$downloadTable;
    }

    /**
     * @return string|void
     */
    public function buildDownloadLink()
    {
        if (!$this->isDownloadable()) {
            return;
        }

        return sprintf( '<a href="%s">Download Results</a>', $this->currentPageUrl( [ $this->downloadKey => 1 ] ) );
    }

    /**
     * @param array $params
     * @param array $except
     * @return string
     */
    public function currentPageUrl( array $params = [ ], array $except = [ ] )
    {
        $get   = array_except( $_GET, $except );
        $query = array_merge( $get, $params );

        return $this->getBaseUrl() . '?' . http_build_query($query);
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

    /**
     * @return Presenter
     */
    public function getPresentor()
    {
        return $this->presentor;
    }

    /**
     * @param Presenter $presentor
     */
    public function setPresentor( Presenter $presentor )
    {
        $this->presentor = $presentor;
    }

    /**
     * @param string $downloadKey
     */
    public function setDownloadKey( $downloadKey )
    {
        $this->downloadKey = $downloadKey;
    }

    /**
     * create the string of html for the drop down list using the $itemsPerPage
     * variable. It also creates some boilerplate javascript that depends on jQuery
     * to build a url to send to server.
     * @return string
     */
    public function renderItemsPerPage()
    {
        $url = $this->currentPageUrl( [ ], [ 'limit' ] );

        $itemsPerPage = range( 20, 100, 20 );

        ob_start();
        ?>

        <select name="<?php echo $this->getItemsPerPageIdentifier() ?>"
                class="form-control rst-limit">
            <?php
            $limitSelected = array_get( $_GET, $this->getItemsPerPageIdentifier(), 20 );
            foreach ((array)$itemsPerPage as $limit) {
                $selected = null;
                if (strcmp( $limitSelected, $limit ) == 0) {
                    $selected = 'selected="selected"';
                }
                printf( '<option value="%d" %s>%d</option>' . PHP_EOL, $limit, $selected, $limit );
            }
            ?>
        </select>

        <script>
            jQuery(function () {
                $(".rst-limit").change(function () {
                    window.location = "<?php echo $url . '&' . $this->getItemsPerPageIdentifier()?>=" + $(this).val();
                });
            });
        </script>
        <?php
        return ob_get_clean();
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
        $this->itemsPerPageIdentifier = $itemsPerPageIdentifier;
    }

}