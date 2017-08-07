<?php

/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
    Refurnished: 7 August 2017 at 15:06:04 CEST, Tranava, Slovakia

 Copyright (c) 2013, Inikoo

 Version 2.0
*/


class Sitemap {

    private $compress;
    private $page = 'index';
    private $index = 1;
    private $count = 1;
    private $urls = array();
    private $save_type = 'db';
    private $website_key = 0;


    public function __construct($website_key, $compress = true) {

        global $db;
        $this->db = $db;

        //  ini_set('memory_limit', '75M'); // 50M required per tests
        $this->compress = ($compress) ? '.gz' : '';
        $this->website_key = $website_key;
    }

    public function page($name) {
        $this->save();
        $this->page  = $name;
        $this->index = 1;
    }

    private function save() {

        if ($this->save_type == 'file') {
            $this->save_to_file();
        } else {
            $this->save_to_db();
        }

    }

    private function save_to_file() {
        if (empty($this->urls)) {
            return;
        }
        $file = "sitemap-{$this->page}-{$this->index}.xml{$this->compress}";
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($this->urls as $url) {
            $xml .= '  <url>'."\n";
            if (is_array($url)) {
                foreach ($url as $key => $value) {
                    $xml .= "    <{$key}>{$value}</{$key}>\n";
                }
            } else {
                $xml .= "    <loc>{$url}</loc>\n";
            }
            $xml .= '  </url>'."\n";
        }
        $xml .= '</urlset>'."\n";
        $this->urls = array();
        if (!empty($this->compress)) {
            $xml = gzencode($xml, 9);
        }
        $fp = fopen(BASE_URI.$file, 'wb');
        fwrite($fp, $xml);
        fclose($fp);
        $this->index++;
        $this->count = 1;
        $num         = $this->index; // should have already been incremented
        while (file_exists(
            BASE_URI."sitemap-{$this->page}-{$num}.xml{$this->compress}"
        )) {
            unlink(
                BASE_URI."sitemap-{$this->page}-{$num}.xml{$this->compress}"
            );
            $num++;
        }
        $this->index($file);
    }

    private function index($file = false) {

        if ($this->save_type == 'file') {
            $this->index_to_file($file);
        } else {

        }

    }

    private function index_to_file($file) {
        $sitemaps = array();
        $index    = "sitemap-index.xml{$this->compress}";
        if (file_exists(BASE_URI.$index)) {
            $xml  = (!empty($this->compress))
                ? gzfile(BASE_URI.$index)
                : file(
                    BASE_URI.$index
                );
            $tags = $this->xml_tag(implode('', $xml), array('sitemap'));
            foreach ($tags as $xml) {
                $loc     = str_replace(
                    BASE_URL, '', $this->xml_tag($xml, 'loc')
                );
                $lastmod = $this->xml_tag($xml, 'lastmod');
                $lastmod = ($lastmod) ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d');
                if (file_exists(BASE_URI.$loc)) {
                    $sitemaps[$loc] = $lastmod;
                }
            }
        }
        $sitemaps[$file] = date('Y-m-d');
        $xml             = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($sitemaps as $loc => $lastmod) {
            $xml .= '  <sitemap>'."\n";
            $xml .= '    <loc>'.BASE_URL.$loc.'</loc>'."\n";
            $xml .= '    <lastmod>'.$lastmod.'</lastmod>'."\n";
            $xml .= '  </sitemap>'."\n";
        }
        $xml .= '</sitemapindex>'."\n";
        if (!empty($this->compress)) {
            $xml = gzencode($xml, 9);
        }
        $fp = fopen(BASE_URI.$index, 'wb');
        fwrite($fp, $xml);
        fclose($fp);
    }

    private function xml_tag($xml, $tag, &$end = '') {
        if (is_array($tag)) {
            $tags = array();
            while ($value = $this->xml_tag($xml, $tag[0], $end)) {
                $tags[] = $value;
                $xml    = substr($xml, $end);
            }

            return $tags;
        }
        $pos = strpos($xml, "<{$tag}>");
        if ($pos === false) {
            return false;
        }
        $start  = strpos($xml, '>', $pos) + 1;
        $length = strpos($xml, "</{$tag}>", $start) - $start;
        $end    = strpos($xml, '>', $start + $length) + 1;

        return ($end !== false) ? substr($xml, $start, $length) : false;
    }

    private function save_to_db() {
        if (empty($this->urls)) {
            return;
        }
        $file = "sitemap-{$this->page}-{$this->index}.xml{$this->compress}";
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($this->urls as $url) {
            $xml .= '  <url>'."\n";
            if (is_array($url)) {
                foreach ($url as $key => $value) {
                    $xml .= "    <{$key}>{$value}</{$key}>\n";
                }
            } else {
                $xml .= "    <loc>{$url}</loc>\n";
            }
            $xml .= '  </url>'."\n";
        }
        $xml .= '</urlset>'."\n";
        $this->urls = array();

        //if (!empty($this->compress)) $xml = gzencode($xml, 9);
        // $fp = fopen(BASE_URI . $file, 'wb');
        // fwrite($fp, $xml);
        // fclose($fp);

        $sql = sprintf(
            "INSERT INTO `Sitemap Dimension` (`Sitemap Website Key`,`Sitemap Date`,`Sitemap Name`,`Sitemap Number`,`Sitemap Content`) VALUES (%d,NOW(),%s,%d,%s) ON DUPLICATE KEY UPDATE `Sitemap Date`=NOW(), `Sitemap Content`=%s",
            $this->website_key, prepare_mysql($file), $this->index, prepare_mysql($xml), prepare_mysql($xml)


        );

        $this->db->exec($sql);


        $this->index++;
        $this->count = 1;
        $num         = $this->index; // should have already been incremented

        $sql = sprintf(
            "DELETE FROM `Sitemap Dimension` WHERE `Sitemap Website Key`=%d AND `Sitemap Number`>=%d ", $this->website_key, $this->index
        );
        $this->db->exec($sql);

        //$this->index($file);
    }

    public function url($url, $lastmod = '', $changefreq = '', $priority = '') {
        $url        = htmlspecialchars($url);
        $lastmod    = (!empty($lastmod)) ? date('Y-m-d', strtotime($lastmod)) : false;
        $changefreq = (!empty($changefreq)
            && in_array(
                strtolower($changefreq), array(
                    'always',
                    'hourly',
                    'daily',
                    'weekly',
                    'monthly',
                    'yearly',
                    'never'
                )
            )) ? strtolower($changefreq) : false;
        $priority   = (!empty($priority) && is_numeric($priority)
            && abs(
                $priority
            ) <= 1) ? round(abs($priority), 1) : false;
        if (!$lastmod && !$changefreq && !$priority) {
            $this->urls[] = $url;
        } else {
            $url = array('loc' => $url);
            if ($lastmod !== false) {
                $url['lastmod'] = $lastmod;
            }
            if ($changefreq !== false) {
                $url['changefreq'] = $changefreq;
            }
            if ($priority !== false) {
                $url['priority'] = ($priority < 1) ? $priority : '1.0';
            }
            $this->urls[] = $url;
        }
        if ($this->count == 50000) {
            $this->save();
        } else {
            $this->count++;
        }
    }

    public function close() {
        $this->save();
        if ($this->save_type == 'file') {
            $this->ping_search_engines();
        }
    }

    public function ping_search_engines() {
        $sitemap                        = BASE_URL.'sitemap-index.xml'.$this->compress;
        $engines                        = array();
        $engines['www.google.com']      = '/webmasters/tools/ping?sitemap='.urlencode($sitemap);
        $engines['www.bing.com']        = '/webmaster/ping.aspx?siteMap='.urlencode(
                $sitemap
            );
        $engines['submissions.ask.com'] = '/ping?sitemap='.urlencode($sitemap);
        foreach ($engines as $host => $path) {
            if ($fp = fsockopen($host, 80)) {
                $send = "HEAD $path HTTP/1.1\r\n";
                $send .= "HOST: $host\r\n";
                $send .= "CONNECTION: Close\r\n\r\n";
                fwrite($fp, $send);
                $http_response = fgets($fp, 128);
                fclose($fp);
                list($response, $code) = explode(' ', $http_response);
                if ($code != 200) {
                    trigger_error(
                        "{$host} ping was unsuccessful.<br />Code: {$code}<br />Response: {$response}"
                    );
                }
            }
        }
    }

    public function __destruct() {
        $this->save();
    }

}


?>
