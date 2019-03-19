<?php

namespace Naciri;

class SimplePaginator
{

    /** @var \PDO */
    private $db;
    /** @const FETCH_MODE */
    const FETCH_MODE = \PDO::FETCH_ASSOC;
    /** @var integer */
    private $limit;
    /** @var integer */
    private $page;
    /** @var string */
    private $query;
    /** @var integer */
    public $total;

    /**
     * Initialize with db instance and query to run
     * @param \PDO   $db    DB Instance
     * @param string $query the query to run
     */
    public function __construct(\PDO $db, $query)
    {
        $this->db = $db;
        $this->query = $query;
       
        $res = $this->db->query($this->query);
        $this->total = count($res->fetchAll());
    }

    /**
     * Get records from database
     * @param  integer $limit elements per page
     * @param  integer $page  current page
     * @return \stdClass     elements to return
     */
    public function fetchData($limit = 10, $page = 1)
    {
        $this->limit = $limit;
        $this->page = $page;
        $query = $this->query . " LIMIT " . (($this->page - 1) * $this->limit) . ",".$this->limit;
        $res = $this->db->query($query);
        $results = $res->fetchAll(self::FETCH_MODE);
        $result = new \stdClass();
        $result->page = $this->page;
        $result->limit = $this->limit;
        $result->total = $this->total;
        $result->data = $results;
        return $result;
    }

    /**
     * Generate pagination links
     * @param  integer $links         number of links to show
     * @param  string $listing_class Used class to list pagination
     * @return string               rendered html to display
     */
    public function renderLinks($links, $listing_class)
    {
        $last = ceil($this->total / $this->limit);
        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;
        $end = (($this->page + $links) < $last) ? $this->page + $links : $last;
        $html = '<ul class="' . $listing_class . '">';
        $class = ($this->page == 1) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&page=' . ($this->page - 1) . '">&laquo;</a></li>';
        if ($start > 1) {
            $html .= '<li><a href="?limit=' . $this->limit . '&page=1">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }
        for ($i = $start ; $i <= $end; $i++) {
            $class = ($this->page == $i) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&page=' . $i . '">' . $i . '</a></li>';
        }
        if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="?limit=' . $this->limit . '&page=' . $last . '">' . $last . '</a></li>';
        }
        $class = ($this->page == $last) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->limit . '&page=' . ($this->page + 1) . '">&raquo;</a></li>';
        $html .= '</ul>';
        return $html;
    }
}
