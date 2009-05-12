<?php

/**
   File: DBsession.php 
   

 *  A class to handle sessions by using a mySQL database for session related data storage providing better
 *  security then the default session handler used by PHP.
 *
 *  To prevent session hijacking, don't forget to use the {@link regenerate_id} method whenever you do a
 *  privilege change in your application
 *
 *  <i>Before usage, make sure you use the session_data.sql file from the <b>install</b> folder to set up the table
 *  used by the class</i>
 *
 *  After instantiating the class, use sessions as you would normally
 *
 *  This class is an adaptation of John Herren's code from the "Trick out your session handler" article
 *  ({@link http://devzone.zend.com/node/view/id/141}) and Chris Shiflett's code from Chapter 8, Shared Hosting - Pg 78-80,
 *  of his book - "Essential PHP Security" ({@link http://phpsecurity.org/code/ch08-2})
 *
 *  <i>Note that the class assumes that there is an active connection to a mySQL database and it does not attempt to create
 *  one. This is due to the fact that, usually, there is a config file that holds the database connection related
 *  information and another class, or function that handles database connection. If this is not how you do it, you can
 *  easily adapt the code by putting the database connection related code in the "open" method of the class.</i>
 *
 *  See the documentation for more info.
 *
 *  Read the LICENSE file, provided with the package, to find out how you can use this PHP script.
 *
 *  If you don't find this file, please write an email to noname at nivelzero dot ro and you will be sent a copy of the license file
 *
 *  For more resources visit {@link http://stefangabos.blogspot.com}
 *
 *  Author:     Stefan Gabos <ix@nivelzero.ro>
 *  Version:    1.0.5 (last revision: September 15, 2007)
 *  Copyright:  (c) 2006 - 2007 Stefan Gabos
 *  Package:    dbSession

*/

error_reporting(E_ALL);

class dbSession
{
  var $db;
    /**
     *  Constructor of class
     *
     *  Initializes the class and starts a new session
     *
     *  There is no need to call start_session() after instantiating this class
     *
     *  @param  integer     $gc_maxlifetime     (optional) the number of seconds after which data will be seen as 'garbage' and
     *                                          cleaned up on the next run of the gc (garbage collection) routine
     *
     *                                          Default is specified in php.ini file
     
     *  @param  integer     $gc_probability     (optional) used in conjunction with gc_divisor, is used to manage probability that
     *                                          the gc routine is started. the probability is expressed by the formula
     *
     *                                          probability = $gc_probability / $gc_divisor
     *
     *                                          So if $gc_probability is 1 and $gc_divisor is 100 means that there is
     *                                          a 1% chance the the gc routine will be called on each request
     *
     *                                          Default is specified in php.ini file
     *
     *  @param  integer     $gc_divisor         (optional) used in conjunction with gc_probability, is used to manage probability
     *                                          that the gc routine is started. the probability is expressed by the formula
     *
     *                                          probability = $gc_probability / $gc_divisor
     *
     *                                          So if $gc_probability is 1 and $gc_divisor is 100 means that there is
     *                                          a 1% chance the the gc routine will be called on each request
     *
     *                                          Default is specified in php.ini file
     *
     *  @param  string      $securityCode       the value of this argument is appended to the HTTP_USER_AGENT before creating the
     *                                          md5 hash out of it. this way we'll try to prevent HTTP_USER_AGENT spoofing
     *
     *                                          Default is 'sEcUr1tY_c0dE'
     *
     *  @return void
     */
    function dbSession($gc_maxlifetime = "", $gc_probability = "", $gc_divisor = "", $securityCode = "sfjw8rq3pe28rnqwep8qwn*&P*(P31fne;fa84713847P883pe8qfmwq8efneprm52gxn&^&^&^")
    {
      $this->db=& MDB2::singleton();
      
        // if $gc_maxlifetime is specified and is an integer number
        if ($gc_maxlifetime != "" && is_integer($gc_maxlifetime)) {

            // set the new value
            @ini_set('session.gc_maxlifetime', $gc_maxlifetime);

        }

        // if $gc_probability is specified and is an integer number
        if ($gc_probability != "" && is_integer($gc_probability)) {

            // set the new value
            @ini_set('session.gc_probability', $gc_probability);

        }

        // if $gc_divisor is specified and is an integer number
        if ($gc_divisor != "" && is_integer($gc_divisor)) {

            // set the new value
            @ini_set('session.gc_divisor', $gc_divisor);

        }

        // get session lifetime
        $this->sessionLifetime = ini_get("session.gc_maxlifetime");

        // we'll use this later on in order to try to prevent HTTP_USER_AGENT spoofing
        $this->securityCode = $securityCode;

        // register the new handler
        session_set_save_handler(
            array(&$this, 'open'),
            array(&$this, 'close'),
            array(&$this, 'read'),
            array(&$this, 'write'),
            array(&$this, 'destroy'),
            array(&$this, 'gc')
        );
        register_shutdown_function('session_write_close');

        // start the session
        session_start();

    }

    /**
     *  Deletes all data related to the session
     *
     *  @since 1.0.1
     *
     *  @return void
     */
    function stop()
    {

        $this->regenerate_id();

        session_unset();

        session_destroy();

    }

    /**
     *  Regenerates the session id.
     *
     *  <b>Call this method whenever you do a privilege change!</b>
     *
     *  @return void
     */
    function regenerate_id()
    {

        // saves the old session's id
        $oldSessionID = session_id();

        // regenerates the id
        // this function will create a new session, with a new id and containing the data from the old session
        // but will not delete the old session
        session_regenerate_id();

        // because the session_regenerate_id() function does not delete the old session,
        // we have to delete it manually
        $this->destroy($oldSessionID);

    }

    /**
     *  Get the number of online users
     *
     *  @return integer     number of users currently online
     */
    function get_users_online()
    {

        // call the garbage collector
        $this->gc($this->sessionLifetime);

        // counts the rows from the database
        $result = @mysql_fetch_assoc(@mysql_query("

            SELECT
                COUNT(session_id) as count
            FROM session_data

        "));

        // return the number of found rows
        return $result["count"];

    }

    /**
     *  Custom open() function
     *
     *  @access private
     */
    function open($save_path, $session_name)
    {

        return true;

    }

    /**
     *  Custom close() function
     *
     *  @access private
     */
    function close()
    {

        return true;

    }

    /**
     *  Custom read() function
     *
     *  @access private
     */
    function read($session_id)
    {

        // reads session data associated with the session id
        // but only
        // - if the HTTP_USER_AGENT is the same as the one who had previously written to this session AND
        // - if session has not expired

      $sql = "

            SELECT
                session_data
            FROM
                session_data
            WHERE

                session_id = '".addslashes($session_id)."' AND
                http_user_agent = '".addslashes(md5($_SERVER["HTTP_USER_AGENT"] . $this->securityCode))."' AND
                session_expire > '".time()."'
            LIMIT 1

        ";
      
      $res =& $this->db->query($sql);
      if (PEAR::isError($res)) {
	die($res->getMessage());
      }

      // if anything was found
      if ($res->numRows() > 0) {
	
	// return found data
	$fields = $res->fetchRow();
	// don't bother with the unserialization - PHP handles this automatically
	
	return $fields["session_data"];
	    
        }

        // if there was an error return an empty string - this HAS to be an empty string
        return "";

    }

    /**
     *  Custom write() function
     *
     *  @access private
     */
    function write($session_id, $session_data)
    {

        // insert OR update session's data - this is how it works:
        // first it tries to insert a new row in the database BUT if session_id is already in the database then just
        // update session_data and session_expire for that specific session_id
        // read more here http://dev.mysql.com/doc/refman/4.1/en/insert-on-duplicate.html


      $sql="
            INSERT INTO
                session_data (
                    session_id,
                    http_user_agent,
                    session_data,
                    session_expire
                )
            VALUES (
                '".addslashes($session_id)."',
                '".addslashes(md5($_SERVER["HTTP_USER_AGENT"] . $this->securityCode))."',
                '".addslashes($session_data)."',
                '".addslashes(time() + $this->sessionLifetime)."'
            )
            ON DUPLICATE KEY UPDATE
                session_data = '".addslashes($session_data)."',
                session_expire = '".addslashes(time() + $this->sessionLifetime)."'

        ";


      $result =& $this->db->exec($sql);
      if (PEAR::isError($result)) {
	die($result->getMessage());
      }

        if ($result) {
        
            // note that after this type of queries, mysql_affected_rows() returns
            // - 1 if the row was inserted
            // - 2 if the row was updated
        
            // if the row was updated
            if ($result > 1) {
	      
                // return TRUE
                return true;
                
            // if the row was inserted
            } else {

                // return an empty string
                return "";

            }

        }
        
        // if something went wrong, return false
        return false;

    }

    /**
     *  Custom destroy() function
     *
     *  @access private
     */
    function destroy($session_id)
    {

        // deletes the current session id from the database
      
      $sql="
            DELETE FROM
                session_data
            WHERE
                session_id = '".mysql_real_escape_string($session_id)."'

        ";
      $result =& $this->db->exec($sql);
      if (PEAR::isError($result)) {
	die($result->getMessage());
      }
      // if anything happened
      if ($result) {
            // return true
            return true;

        }

        // if something went wrong, return false
        return false;

    }

    /**
     *  Custom gc() function (garbage collector)
     *
     *  @access private
     */
    function gc($maxlifetime)
    {



      $sql ="

            DELETE FROM
                session_data
            WHERE
                session_expire < '".mysql_real_escape_string(time() - $maxlifetime)."'

        ";
      
      $result =& $this->db->exec($sql);
      if (PEAR::isError($result)) {
	die($result->getMessage());
      }

    }

}
?>
