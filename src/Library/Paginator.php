<?php
namespace Lib;

/*
 * sayfalama sınıfı 
 * bu kısım çeşitli testlere göre dışardan eklendi
 * @todo digg gibi değişik seylere de bak
 * @version 1.0
 * @date October 20, 2012
 */
class Paginator
{

    /**
     * set the number of items per page.
     *
     * @var numeric
     */
    private $_perPage;

    /**
     * set get parameter for fetching the page number
     *
     * @var string
     */
    private $_instance;

    /**
     * sets the page number.
     *
     * @var numeric
     */
    private $_page;

    /**
     * set the limit for the data source
     *
     * @var string
     */
    private $_limit;

    /**
     * set the total number of records/items.
     *
     * @var numeric
     */
    private $_totalRows = 0;

    /**
     * __construct
     *
     * pass values when class is istantiated
     *
     * @param numeric $_perPage
     *            sets the number of iteems per page
     * @param numeric $_instance
     *            sets the instance for the GET parameter
     */
    public function __construct($perPage, $instance)
    {
        $this->_instance = $instance;
        $this->_perPage = $perPage;
        $this->setInstance();
    }

    /**
     * getStart
     *
     * creates the starting point for limiting the dataset
     *
     * @return numeric
     */
    public function getStart()
    {
        return ($this->_page * $this->_perPage) - $this->_perPage;
    }

    /**
     * setInstance
     *
     * sets the instance parameter, if numeric value is 0 then set to 1
     *
     * @var numeric
     */
    private function setInstance()
    {
        $this->_page = (int) (! isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);
        $this->_page = ($this->_page == 0 ? 1 : $this->_page);
    }

    /**
     * setTotal
     *
     * collect a numberic value and assigns it to the totalRows
     *
     * @var numeric
     */
    public function setTotal($_totalRows)
    {
        $this->_totalRows = $_totalRows;
    }

    /**
     * getLimit
     *
     * returns the limit for the data source, calling the getStart method and passing in the number of items perp page
     *
     * @return string
     */
    public function getLimit()
    {
        return "LIMIT " . $this->getStart() . ",$this->_perPage";
    }

    /*
     * <ul class="pagination pagination-sm">
     * <li><a aria-label="Önceki" href="#"><span aria-hidden="true">«</span></a></li>
     * <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
     * <li><a href="#">2</a></li>
     * <li><a href="#">3</a></li>
     * <li><a href="#">4</a></li>
     * <li><a href="#">5</a></li>
     * <li><a aria-label="Sonraki" href="#"><span aria-hidden="true">»</span></a></li>
     * </ul>
     */
    
    /**
     * pageLinks
     *
     * create the html links for navigating through the dataset
     *
     * @var sting $path optionally set the path for the link
     * @var sting $ext optionally pass in extra parameters to the GET
     * @return string returns the html menu
     */
    public function pageLinks($path = '?', $ext = null)
    {
        $adjacents = "2";
        $prev = $this->_page - 1;
        $next = $this->_page + 1;
        $lastpage = ceil($this->_totalRows / $this->_perPage);
        $lpm1 = $lastpage - 1;
        

       /* $path1 = parse_url($path);
         $links = $path1['scheme'] . '://' . $path1['host'] . $path1['path'];*/
/*eklendi page olayına ekleme yapar */
        $QueryName = parse_url($path, PHP_URL_QUERY);
        parse_str($QueryName, $query2arrayCiktisi);
        // print_r($query2arrayCiktisi);
        if (! empty($QueryName)) {
            foreach ($query2arrayCiktisi as $key => $value) {
                $paths .= '&' . $key . '=' . $value;
            }
            $path = '?' . $paths . '&';
        }
        
        
        
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination pagination-sm'>";
            if ($this->_page > 1)
                $pagination .= "<li><a href='" . $path . "$this->_instance=$prev" . "$ext'>Önceki</a></li>";
            else
                $pagination .= "<li><span class='disabled'>Önceki</span></li>";
            
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter ++) {
                    if ($counter == $this->_page)
                        $pagination .= '<li class="active"> <a href="#">' . $counter . '<span class="sr-only"></span></li></a>';
                    else
                        $pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($this->_page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter ++) {
                        if ($counter == $this->_page)
                            // $pagination .= "<li><span class='current'>$counter</span></li>";
                            $pagination .= '<li class="active"> <a href="#">' . $counter . '<span class="sr-only"></span></li></a>';
                        else
                            $pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                    // $pagination .= "...";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
                    // $pagination .= "...";
                    for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter ++) {
                        if ($counter == $this->_page)
                            // $pagination .= "<span class='current'>$counter</span>";
                            $pagination .= '<li class="active"> <a href="#">' . $counter . '<span class="sr-only"></span></li></a>';
                        else
                            $pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                    // $pagination .= "..";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li><a href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
                    $pagination .= "<li><a href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
                    // $pagination .= "..";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter ++) {
                        if ($counter == $this->_page)
                            // $pagination .= "<span class='current'>$counter</span>";
                            $pagination .= '<li class="active"> <a href="#">' . $counter . '<span class="sr-only"></span></li></a>';
                        else
                            $pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                }
            }
            
            if ($this->_page < $counter - 1)
                $pagination .= "<li><a href='" . $path . "$this->_instance=$next" . "$ext'>Sonraki</a></li>";
            else
                $pagination .= "<li><span class='disabled'>Sonraki</span></li>";
            $pagination .= "</ul>\n";
        }
        
        return $pagination;
    }
}
