<?php
include_once __DIR__ . '/../settings.php';

class PHPPage {
    private $p;
    private $previous_page;
    private $next_page;
    private $begin_page;
    private $end_page;
    private $total_page;
    private $url;
    private $begin;

    function __construct() {
        //$this->url = $_SERVER['PHP_SELF'];
    }

    function set_total($total) {
        global $NUMBER_PER_PAGE;

        $this->total_page = ceil($total / $NUMBER_PER_PAGE);
    }

    function get_begin() {
        global $NUMBER_PER_PAGE;

        $p = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
        if (!empty($p)) {
            $this->p = $p;
            if ($this->p < 0 || $this->p == 1) {
                $this->p = 1;
                $this->begin = 0;
            } else if ((int) $this->total_page > 0 && $this->p > (int) $this->total_page) {
                $this->p = $this->total_page;
            } else {
                $this->begin = ($this->p - 1) * $NUMBER_PER_PAGE;
            }
        } else {
            $this->p = 1;
            $this->begin = 0;
        }
        return $this->begin;
    }

    function page_number() {

        $qstr = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            if (strstr($_SERVER['QUERY_STRING'], '&')) {
                $q2 = explode('&', $_SERVER['QUERY_STRING']);
                foreach ($q2 as $q3) {
                    $q4 = explode('=', $q3);
                    if ($q3[0] != 'p') {
                        $qstr .= ($qstr == '') ? $q3 : '&' . $q3;
                    }
                }
            } else if (strstr($_SERVER['QUERY_STRING'], '=') && !strstr($_SERVER['QUERY_STRING'], '&')) {
                $q2 = explode('=', $_SERVER['QUERY_STRING']);
                $qstr = ($q2[0] != 'p') ? $_SERVER['QUERY_STRING'] : '';
            }
        }
        $this->url .= ($qstr == '') ? $_SERVER['PHP_SELF'] . '?' : $_SERVER['PHP_SELF'] . '?' . $qstr . '&';

        global $NUMBER_PAGINATION_OFFSET;
        global $NUMBER_PAGINATION;
        global $NUMBER_PER_PAGE;

        $this->previous_page = ($this->p - 1) > 0 ? ($this->p - 1) : 1;
        $this->next_page = ($this->p + 1) > $this->total_page ? $this->total_page : ($this->p + 1);
        $this->begin_page = ($this->p - $NUMBER_PAGINATION_OFFSET) > 0 ? max(1, $this->p - $NUMBER_PAGINATION_OFFSET) : 1;
        $this->end_page = ($this->total_page - $this->p - $this->begin_page) > $NUMBER_PAGINATION ? ($this->begin_page + $NUMBER_PAGINATION) : min($this->total_page, ($this->p + $NUMBER_PAGINATION - $NUMBER_PAGINATION_OFFSET));

        $ret = '';
        $ret .= '<div class="pagination mx-auto">';
        if ($this->p > 1) {
            $ret .= '<a href="' . $this->url . 'p=1' . '">' . '<span class="oi oi-media-step-backward"></span></a>';
            $ret .= '<a href="' . $this->url . 'p=' . ($this->p - 1) . '">' . '<span class="oi oi-chevron-left"></span></a>';
        }
        $search_type = filter_input(INPUT_GET, "search_type", FILTER_SANITIZE_STRING);
        if (!empty($search_type)) {
            for ($i = $this->begin_page; $i <= $this->end_page; $i++) {
                $page_active = ($i == $this->p) ? 'class="active"' : "";
                $ret .= "<a {$page_active} href=\"" . $this->url . 'p=' . $i . '"&search_type=' . $search_type . '&query=' . $query . '">' . $i . '</a>';
            }
            if ($this->p < $this->total_page) {
                $ret .= '<a href="' . $this->url . 'p=' . $this->total_page . '&search_type=' . $search_type . '&query=' . $query . '">' . '<span class="oi oi-media-step-forward"></span></a>';
            }
        } else {
            for ($i = $this->begin_page; $i <= $this->end_page; $i++) {
                $page_active = ($i == $this->p) ? 'class="active"' : "";
                $ret .= "<a {$page_active} href=\"" . $this->url . 'p=' . $i . '">' . $i . '</a>';
            }
            if ($this->p < $this->total_page) {
                $ret .= '<a href="' . $this->url . 'p=' . ($this->p + 1) . '"><span class="oi oi-chevron-right"></span></a>';
                $ret .= '<a href="' . $this->url . 'p=' . $this->total_page . '"><span class="oi oi-media-step-forward"></span></a>';
            }
        }

        $ret .= '</div>';
        return $ret;
    }

}