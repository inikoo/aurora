<?php

/*

  About:get  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2012, Inikoo

  Version 2.0
*/

class FTP {


    var $host;
    var $user;
    var $password;
    var $source;
    var $destination;
    var $mode;
    var $str;
    var $write;
    var $protocol;
    var $passive;
    var $error;

    // SET THE CONNECTION VARIABLES
    function FTP($host, $user = "anonymous", $password = "nobody@nobody.com", $protocol = 'SFTP', $port = false, $passive = true) {
        $this->port     = $port;
        $this->protocol = $protocol;
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;
        $this->passive  = $passive;
        $this->connect();
        if ($this->error) {
            return;
        }
        $this->login();


    }


    function connect() {

        switch ($this->protocol) {
            case 'FTPS':
                if (!$this->port) {
                    $this->port = 21;
                }
                if (!function_exists('ftp_ssl_connect')) {
                    $this->error = true;
                    $this->msg   = _('FTP not supported by server');

                    return;

                }

                $this->connection = ftp_ssl_connect($this->host, $this->port);
                break;
            case 'FTP':
                if (!$this->port) {
                    $this->port = 21;
                }

                $this->connection = ftp_connect($this->host, $this->port);
                break;
            case 'SFTP':
                if (!$this->port) {
                    $this->port = 22;
                }
                $this->connection = ssh2_connect($this->host, $this->port);
                break;
            default:
                $this->connection = false;
                $this->error      = true;
                $this->msg        = 'Unknown protocol: '.$this->protocol;

                return;
        }


        if (!$this->connection) {
            $this->error = true;
            $this->msg   = _('Error').': '._('Can not connect').' '.$this->host;

            $this->error("ERROR FTP->CONNECT [$this->connection:$this->host]");
        }


    }

    function error($str, $write = 0) {
        error_log($str, $write);
    }

    // UPLOAD SOURCE FILE TO DESTINATION

    function login() {
        switch ($this->protocol) {
            case 'FTPS':
            case 'FTP':

                $this->logged = ftp_login(
                    $this->connection, $this->user, $this->password
                );
                if (!$this->logged) {
                    $this->error = true;
                    $this->msg
                                 = $this->msg = _('Error').': '._('Can not login').' '.$this->host;
                }

                $this->pasv = ftp_pasv($this->connection, true);

                if (!$this->pasv) {
                    $this->error = true;
                    $this->msg   = _('Server do not support passive mode');
                }
                break;

            default:
                $this->error = true;
                $this->msg   = 'Unknown protocol';

        }


    }

    //---------------------------------------------------------------------------------------------------------

    function upload($source_file, $destination_file, $type = "") {
        $this->source      = $source_file;
        $this->destination = $destination_file;
        $this->type        = $type;

        switch ($this->type) // MODE = 'FTP_ASCII' or 'FTP_BINARY'
        {
            case"image/gif":
            case"image/png":
            case"image/jpeg":
                $this->mode = FTP_BINARY;
                break;
            default:
                $this->mode = FTP_ASCII;
        }


        $contents = ftp_nlist($this->connection, ".");

        // output $contents
        var_dump($contents);

        $this->put = ftp_put(
            $this->connection, $this->destination, $this->source, $this->mode
        );

        if (!$this->put) {
            $this->error = true;
            $this->msg   = _('Can not upload file').' '.$this->source.' -> '.$this->destination;
        }


    }
    //---------------------------------------------------------------------------------------------------------
    // DELETE A FILE

    function download($source_file, $destination_file, $type = "") {
        $this->source      = $source_file;
        $this->destination = $destination_file;
        $this->type        = $type;

        switch ($this->type) // MODE = 'FTP_ASCII' or 'FTP_BINARY'
        {
            case"image/gif":
            case"image/png":
            case"image/jpeg":
                $this->mode = FTP_BINARY;
                break;
            default:
                $this->mode = FTP_ASCII;
        }

        $this->get = ftp_get(
            $this->connection, $this->destination, $this->source, $this->mode
        );

        if (!$this->get) {
            $this->error(
                "ERROR FTP->PUT [$this->connection:$this->source:$this->mode]"
            );
        }
    }

    // CLEAN ILLEGAL CHARACTERS

    function delete($source_file) {
        $this->source  = $source_file;
        $this->deleted = ftp_delete($this->connection, $this->source);

        if (!$this->deleted) {
            $this->error("ERROR FTP->DELETE [$this->connection:$this->source]");
        }

    }

    // DETERMINE IF FILE EXISTS AND RETURN SIZE

    function clean_filename($source_file) {
        $search[] = " ";
        $search[] = "&";
        $search[] = "$";
        $search[] = ",";
        $search[] = "!";
        $search[] = "@";
        $search[] = "#";
        $search[] = "^";
        $search[] = "(";
        $search[] = ")";
        $search[] = "+";
        $search[] = "=";
        $search[] = "[";
        $search[] = "]";

        $replace[] = "_";
        $replace[] = "and";
        $replace[] = "S";
        $replace[] = "_";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";
        $replace[] = "";

        return str_replace($search, $replace, $source_file);

    }

    // WRITE ERROR TO LOG

    function check_file($source_file) {
        $this->source = $source_file;

        return ftp_size($this->connection, $this->source);
    }

    // CLOSE CONNECTION

    function start() {
        $this->connect();
        $this->login();
    }

    // QUICK START COMMAND

    function end() {
        $this->close();
    }

    // QUICK END COMMAND

    function close() {
        ftp_close($this->connection);
    }

    function upload_string($source_file, $destination_file, $type = "") {

        $this->source      = $source_file;
        $this->destination = $destination_file;
        $this->type        = $type;

        switch ($this->type) // MODE = 'FTP_ASCII' or 'FTP_BINARY'
        {
            case"image/gif":
            case"image/png":
            case"image/jpeg":
                $this->mode = FTP_BINARY;
                break;
            default:
                $this->mode = FTP_ASCII;
        }


        $contents = ftp_nlist($this->connection, ".");

        // output $contents
        var_dump($contents);
        $file_name = explode('/', $destination_file);
        $file      = array_pop($file_name);

        $destination_path = dirname($destination_file);


        $folders = explode('/', $destination_path);

        if ($folders[0] == '.') {
            array_shift($folders);
        }

        foreach ($folders as $folder) {
            if (ftp_chdir($this->connection, $folder)) {
                continue;
            } else {
                ftp_mkdir($this->connection, $folder);
                ftp_chdir($this->connection, $folder);
            }
        }

        $temp = tmpfile();
        fwrite($temp, $this->source);
        fseek($temp, 0);
        //echo fread($temp, 1024);

        $this->put = ftp_fput($this->connection, $file, $temp, $this->mode);

        if (!$this->put) {
            $this->error = true;
            $this->msg   = _('Can not upload file').' '.$this->source.' -> '.$this->destination;
        }

        fclose($temp);

    }
}

?>
