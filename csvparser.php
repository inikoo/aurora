<?php
class CSV_PARSER
{
    public

    /**
     * csv parsing default-settings
     *
     * @var array
     * @access public
     */
    $settings = array(
        'delimiter' => "\t",
        'eol' => "\n",
        'length' => 999999,
        'escape' => '"'
    );

    protected

    /**
     * imported data from csv
     *
     * @var array
     * @access protected
     */
    $rows = array(),

    /**
     * csv file to parse
     *
     * @var string
     * @access protected
     */
    $_filename = '',

    /**
     * csv headers to parse
     *
     * @var array
     * @access protected
     */
    $headers = array();

    public function __construct($filename = null)
    {
        $this->load($filename);
    }
	
    public function load($filename)
    {
        $this->_filename = $filename;
        $this->flush();
        return $this->parse();
    }

    public function settings($array)
    {
        $this->settings = array_merge($this->settings, $array);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function countHeaders()
    {
        return count($this->headers);
    }

    public function connect($columns = array())
    {
        if (!$this->isSymmetric()) {
            return array();
        }
        if (!is_array($columns)) {
            return array();
        }
        if ($columns === array()) {
            $columns = $this->headers;
        }

        $ret_arr = array();

        foreach ($this->rows as $record) {
            $item_array = array();
            foreach ($record as $column => $value) {
                $header = $this->headers[$column];
                if (in_array($header, $columns)) {
                    $item_array[$header] = $value;
                }
            }

            // do not append empty results
            if ($item_array !== array()) {
                array_push($ret_arr, $item_array);
            }
        }

        return $ret_arr;
    }

    public function isSymmetric()
    {
        $hc = count($this->headers);
        foreach ($this->rows as $row) {
            if (count($row) != $hc) {
                return false;
            }
        }
        return true;
    }

    public function getAsymmetricRows()
    {
        $ret_arr = array();
        $hc      = count($this->headers);
        foreach ($this->rows as $row) {
            if (count($row) != $hc) {
                $ret_arr[] = $row;
            }
        }
        return $ret_arr;
    }

    public function symmetrize($value = '')
    {
        $max_length = 0;
        $headers_length = count($this->headers);

        foreach ($this->rows as $row) {
            $row_length = count($row);
            if ($max_length < $row_length) {
                $max_length = $row_length;
            }
        }

        if ($max_length < $headers_length) {
            $max_length = $headers_length;
        }

        foreach ($this->rows as $key => $row) {
            $this->rows[$key] = array_pad($row, $max_length, $value);
        }

        $this->headers = array_pad($this->headers, $max_length, $value);
    }

    public function walkGrid($callback)
    {
        foreach (array_keys($this->getRows()) as $key) {
            if (!$this->walkRow($key, $callback)) {
                return false;
            }
        }
        return true;
    }

    public function getColumn($name)
    {
        if (!in_array($name, $this->headers)) {
            return array();
        }
        $ret_arr = array();
        $key     = array_search($name, $this->headers, true);
        foreach ($this->rows as $data) {
            $ret_arr[] = $data[$key];
        }
        return $ret_arr;
    }

    public function hasColumn($string)
    {
        return in_array($string, $this->headers);
    }

    public function appendColumn($column, $values = null)
    {
        if ($this->hasColumn($column)) {
            return false;
        }
        $this->headers[] = $column;
        $length          = $this->countHeaders();
        $rows            = array();

        foreach ($this->rows as $row) {
            $rows[] = array_pad($row, $length, '');
        }

        $this->rows = $rows;

        if ($values === null) {
            $values = '';
        }

        return $this->fillColumn($column, $values);
    }

    public function fillColumn($column, $values = null)
    {
        if (!$this->hasColumn($column)) {
            return false;
        }

        if ($values === null) {
            return false;
        }

        if (!$this->isSymmetric()) {
            return false;
        }

        $y = array_search($column, $this->headers);

        if (is_numeric($values) || is_string($values)) {
            foreach (range(0, $this->countRows() -1) as $x) {
                $this->fillCell($x, $y, $values);
            }
            return true;
        }

        if ($values === array()) {
            return false;
        }

        $length = $this->countRows();
        if (is_array($values) && $length == count($values)) {
            for ($x = 0; $x < $length; $x++) {
                $this->fillCell($x, $y, $values[$x]);
            }
            return true;
        }

        return false;
    }
	
    public function removeColumn($name)
    {
        if (!in_array($name, $this->headers)) {
            return false;
        }

        if (!$this->isSymmetric()) {
            return false;
        }

        $key = array_search($name, $this->headers);
        unset($this->headers[$key]);
        $this->resetKeys($this->headers);

        foreach ($this->rows as $target => $row) {
            unset($this->rows[$target][$key]);
            $this->resetKeys($this->rows[$target]);
        }

        return $this->isSymmetric();
    }

    public function walkColumn($name, $callback)
    {
        if (!$this->isSymmetric()) {
            return false;
        }

        if (!$this->hasColumn($name)) {
            return false;
        }

        if (!function_exists($callback)) {
            return false;
        }

        $column = $this->getColumn($name);
        foreach ($column as $key => $cell) {
            $column[$key] = $callback($cell);
        }
        return $this->fillColumn($name, $column);
    }

    public function getCell($x, $y)
    {
        if ($this->hasCell($x, $y)) {
            $row = $this->getRow($x);
            return $row[$y];
        }
        return false;
    }

    public function fillCell($x, $y, $value)
    {
        if (!$this->hasCell($x, $y)) {
            return false;
        }
        $row            = $this->getRow($x);
        $row[$y]        = $value;
        $this->rows[$x] = $row;
        return true;
    }

    public function hasCell($x, $y)
    {
        $has_x = array_key_exists($x, $this->rows);
        $has_y = array_key_exists($y, $this->headers);
        return ($has_x && $has_y);
    }

    public function getRow($number)
    {
    
        if($number<0){
            return $this->getHeaders();
        }
    
        $raw = $this->rows;
        if (array_key_exists($number, $raw)) {
            return $raw[$number];
        }
        return array();
    }

    public function getRows($range = array())
    {
        if (is_array($range) && ($range === array())) {
            return $this->rows;
        }

        if (!is_array($range)) {
            return $this->rows;
        }

        $ret_arr = array();
        foreach ($this->rows as $key => $row) {
            if (in_array($key, $range)) {
                $ret_arr[] = $row;
            }
        }
        return $ret_arr;
    }

    public function countRows()
    {
        return count($this->rows);
    }

    public function appendRow($values)
    {
        $this->rows[] = array();
        $this->symmetrize();
        return $this->fillRow($this->countRows() - 1, $values);
    }
	
    public function fillRow($row, $values)
    {
        if (!$this->hasRow($row)) {
            return false;
        }

        if (is_string($values) || is_numeric($values)) {
            foreach ($this->rows[$row] as $key => $cell) {
                 $this->rows[$row][$key] = $values;
            }
            return true;
        }

        $eql_to_headers = ($this->countHeaders() == count($values));
        if (is_array($values) && $this->isSymmetric() && $eql_to_headers) {
            $this->rows[$row] = $values;
            return true;
        }

        return false;
    }

    public function hasRow($number)
    {
        return (in_array($number, array_keys($this->rows)));
    }

    public function removeRow($number)
    {
        $cnt = $this->countRows();
        $row = $this->getRow($number);
        if (is_array($row) && ($row != array())) {
            unset($this->rows[$number]);
        } else {
            return false;
        }
        $this->resetKeys($this->rows);
        return ($cnt == ($this->countRows() + 1));
    }

    public function walkRow($row, $callback)
    {
        if (!function_exists($callback)) {
            return false;
        }
        if ($this->hasRow($row)) {
            foreach ($this->getRow($row) as $key => $value) {
                $this->rows[$row][$key] = $callback($value);
            }
            return true;
        }
        return false;
    }

    public function getRawArray()
    {
        $ret_arr   = array();
        $ret_arr[] = $this->headers;
        foreach ($this->rows as $row) {
            $ret_arr[] = $row;
        }
        return $ret_arr;
    }

    public function createHeaders($prefix)
    {
        if (!$this->isSymmetric()) {
            return false;
        }

        $length = count($this->headers) + 1;
        $this->moveHeadersToRows();

        $ret_arr = array();
        for ($i = 1; $i < $length; $i ++) {
            $ret_arr[] = $prefix . "_$i";
        }
        $this->headers = $ret_arr;
        return $this->isSymmetric();
    }
	
    public function setHeaders($list)
    {
        if (!$this->isSymmetric()) {
            return false;
        }
        if (!is_array($list)) {
            return false;
        }
        if (count($list) != count($this->headers)) {
            return false;
        }
        $this->moveHeadersToRows();
        $this->headers = $list;
        return true;
    }

    protected function parse()
    {
        if (!$this->validates()) {
            return false;
        }

        $c = 0;
        $d = $this->settings['delimiter'];
        $e = $this->settings['escape'];
        $l = $this->settings['length'];

        $res = fopen($this->_filename, 'r');

        while ($keys = fgetcsv($res, $l, $d, $e)) {

            if ($c == 0) {
		
                $this->headers = $keys;
            } else {
                array_push($this->rows, $keys);
            }

            $c ++;
        }
        fclose($res);
        $this->removeEmpty();
        return true;
    }

    protected function removeEmpty()
    {
        $ret_arr = array();
        foreach ($this->rows as $row) {
            $line = trim(join('', $row));
            if (!empty($line)) {
                $ret_arr[] = $row;
            }
        }
        $this->rows = $ret_arr;
    }

    protected function validates()
    {
        // file existance
        if (!file_exists($this->_filename)) {
            return false;
        }

        // file readability
        if (!is_readable($this->_filename)) {
            return false;
        }

        return true;
    }
	
    protected function moveHeadersToRows()
    {
        $arr   = array();
        $arr[] = $this->headers;
        foreach ($this->rows as $row) {
            $arr[] = $row;
        }
        $this->rows    = $arr;
        $this->headers = array();
    }

    protected function resetKeys(&$array)
    {
        $arr = array();
        foreach ($array as $item) {
            $arr[] = $item;
        }
        $array = $arr;
    }

    protected function flush()
    {
        $this->rows    = array();
        $this->headers = array();
    }

}

?>
