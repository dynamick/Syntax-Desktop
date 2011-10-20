<?php
/**
* @author Alan T. Miller <alan@alanmiller.com>
* @copyright Copyright (C) 2010, Alan T Miller, All Rights Reserved.
 *
 * Creates a properly formatted XML sitemap
 *
 * Sample Usage:
 *
 * // Create an array of xml_sitemap_entry objects
 * $entries[] = new xml_sitemap_entry('/', '1.00', 'weekly');
 * $entries[] = new xml_sitemap_entry('/somepage.html', '0.95', 'weekly');
 * $entries[] = new xml_sitemap_entry('/otherpage.html', '0.90', 'monthly');
 *
 * // set up the xml generator config object
 * $conf = new xml_sitemap_generator_config();
 * $conf->setDomain('www.somedomainsomewhere.com');
 * $conf->setPath('/var/tmp/');
 * $conf->setFilename('sitemap.xml');
 * $conf->setEntries($entries);
 *
 * // instantiate and execute
 * $generator = new xml_sitemap_generator($conf);
 * $generator->write(); // or $generator->toString();
 *
 */
class xml_sitemap_generator_config
{
    private $_domain;
    private $_path;
    private $_filename;
    private $_entries = array();

    public function get($arg)
    {
        switch ($arg) {
            case 'domain':
                return $this->_domain;
            case 'path':
                return $this->_path;
            case 'filename':
                return $this->_filename;
            case 'filepath':
                return $this->_getFilepath();
            case 'entries':
                return $this->_entries;
        }
    }

    public function setDomain($domain)
    {
        $this->_domain = trim($domain);
        return $this;
    }

    public function setPath($path)
    {
        $path = trim($path);
        // clean trailing slash if exists
        if (substr($path,-1) == '/') {
            $path = substr($path, 0, -1);
        }

        // check if write directory is valid
        if (!is_dir($path)) {
            exit(sprintf('write directory does not exist: %s'."\n",$path));
        }

        // check if write dir is writable
        if (!is_writable($path)) {
            exit(sprintf('write directory not writable: %s'."\n", $path));
        }

        $this->_path = $path;
        return $this;
    }

    public function setFilename($filename)
    {
        $filename = trim($filename);
        if (strtolower(substr($filename,-3)) != 'xml') {
            exit(sprintf('filename must end with: xml: %s'."\n", $filename));
        }

        // remove leading slash if exists
        if (substr($filename, 0, 1) == '/') {
            $filename = substr($filename, 1, 0);
        }

        $this->_filename = $filename;
        return $this;
    }

    public function setEntries($entries)
    {
        if (!is_array($entries)) {
            throw new exception('setEntries() method expecs an array of objects');
        }

        foreach ($entries AS $entry) {
            if (!is_object($entry) || !get_class($entry) == 'xml_sitemap_entry') {
                throw new exception('setEntries() method expects an aray of xml_sitemap_entry objects');
            }
        }
        $this->_entries = $entries;
        return $this;
    }

    /**
     * make sure we can proceed without issues
     *
     */
    public function sanityCheck()
    {
        if (!strlen($this->_filename) > 0) {
            exit('Error: sitemap filename not set in configuration object');
        }

        if (!strlen($this->_domain) > 0) {
            exit('Error: domain not set in configuration object');
        }
    }

    private function _getFilepath()
    {
        return sprintf('%s/%s',$this->_path, $this->_filename);
    }
}

class xml_sitemap_entry
{
    private $_loc;
    private $_priority;
    private $_changefreq;
    private $_lastmod;

    private $_frequencies = array('always','hourly','daily','weekly','monthly','yearly','never');
    private $_priorities = array('0.0','0.1','0.2','0.3','0.4','0.5','0.6','0.7','0.8','0.9','1.0');

    public function __construct($loc, $priority, $changefreq="", $lastmod='')
    {
        $this->_setLoc($loc);
        $this->_setPriority($priority);

        if (strlen($changefreq)> 0) {
            $this->_setChangefreq($changefreq);
        }
        if (strlen($lastmod)> 0) {
            $this->_setLastmod($lastmod);
        }
    }

    public function get($arg)
    {
        switch($arg)
        {
            case 'loc':
                return $this->_getLoc();
            case 'priority':
                return $this->_getPriority();
            case 'changefreq':
                return $this->_getChangefreq();
            case 'lastmod':
                return $this->_getLastmod();
            case 'frequencies':
                return $this->_frequencies;
            case 'priorities':
                return $this->_priorities;
            default:
                throw new Exception('get() method in class: '.__CLASS__.' did not recognize the argument');
        }
    }

    private function _setLoc($loc)
    {
        $this->_loc = $loc;
        return $this;
    }

    private function _setPriority($priority)
    {
        $priority = trim($priority);
        if (!in_array($priority, $this->_priorities)) {
            throw new Exception('setPriority() method is expecting a value between 0.0 and 1.0');
        } else {
            $this->_priority = trim($priority);
        }
        return $this;
    }

    private function _setChangefreq($changefreq)
    {
        $changefreq = strtolower(trim($changefreq));

        if (!in_array($changefreq, $this->_frequencies)) {
            throw new Exception('setChangefreq() method is expecting a value such as hourly daily, weekly etc');
        } else {
            $this->_changefreq = $changefreq;
        }
        return $this;
    }

    private function _setLastmod($lastmod)
    {
        $arr = date_parse($lastmod);
        if (!checkdate($arr['month'], $arr['day'], $arr['year'])) {
            throw new Exception('setLastmod() method expects a valid date');
        } else {
            $this->_lastmod =
                sprintf('%s-%s-%s',$arr['year'], $arr['month'], $arr['day']);
        }
        return $this;
    }

    private function _getLoc()
    {
        return $this->_loc;
    }

    private function _getPriority()
    {
        if (!strlen($this->_priority) > 0) {
            return  '0.5';
        } else {
            return $this->_priority;
        }
    }

    private function _getLastmod()
    {
        // set default values
        if (!strlen($this->_lastmod) > 0) {
             return date('Y-m-d');
        } else {
            return $this->_lastmod;
        }
    }

    private function _getChangefreq()
    {
        if (!strlen($this->_changefreq) > 0) {
            return 'monthly';
        } else {
            return $this->_changefreq;
        }
    }
}

class xml_sitemap_generator
{
    private $_conf;
    private $_blocks;
    private $_xml;

    public function __construct(xml_sitemap_generator_config $conf)
    {
        $this->_conf = $conf;
    }

    /**
     * exists to maintain legacy interface,
     * other than that, it is not needed.
     *
     */
    public function build()
    {
        $this->_build();
    }

    /**
     * retrieve XML sitemap as a string
     *
     * @return unknown
     */
    public function getXml()
    {
        $this->_build();
        return $this->_xml;
    }

    public function toString()
    {
        $this->_build();
        return $this->_xml;
    }

    /**
     * write XML sitemap to disk
     *
     */
    public function write($echo=true)
    {
        $this->_build();

        if($echo) print_r($this->_xml);
        if (!file_put_contents($this->_conf->get('filepath'), $this->_xml)){
            throw new Exception('cound not write file: '.$this->_conf->get('filepath')."\n");
        } else{
            return true;
        } 
    }

    private function _append($xml)
    {
        $this->_xml .= $xml;
    }

    private function _build()
    {
        $this->_conf->sanityCheck();
        $this->_append($this->_buildHeader());
        $this->_append($this->_buildBlocks());
        $this->_append($this->_buildFooter());
    }

    private function _buildHeader()
    {
        $header  = '<'.'?'.'xml version="1.0" encoding="UTF-8"?'.'>'."\n";
        $header .= "\t".'<urlset ';
        $header .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        return $header;
    }

    private function _buildFooter()
    {
        return '</urlset>'."\n";
    }

    private function _buildBlocks()
    {
        foreach ($this->_conf->get('entries') AS $entry) {
            $this->_blocks .= $this->_buildEntry($entry);
        }
        return $this->_blocks;
    }

    private function _buildEntry(xml_sitemap_entry $entry)
    {
        $loc = sprintf("http://%s%s",
                       $this->_conf->get('domain'),$entry->get('loc'));

        return sprintf("<url>\n%s%s%s%s</url>\n",
                $this->_buildLine('loc', $loc),
                $this->_buildLine('priority',$entry->get('priority')),
                $this->_buildLine('changefreq',$entry->get('changefreq')),
                $this->_buildLine('lastmod', $entry->get('lastmod')));
    }

    private function _buildLine($tagname, $content)
    {
        if(!$this->_is_utf8($content)) {
            $content = trim(utf8_encode($content));
        }
        return sprintf("\t<%s>%s</%s>\n",
                       $tagname, $content, $tagname);
    }

    private function _is_utf8($str)
    {
        // function borrowed from:
        // http://w3.org/International/questions/qa-forms-utf-8.html

        return preg_match('%^(?:
              [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $str);
    }


}