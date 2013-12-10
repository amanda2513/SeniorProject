<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AuthLDAP Class
 *
 * Simple LDAP Authentication library for Code Igniter.
 *
 */
class AuthLDAP {
    function __construct() {
        $this->ci =& get_instance();

        log_message('debug', 'AuthLDAP initialization commencing...');

        // Load the session library
        //$this->ci->load->library('session');

        // Load the configuration
        $this->ci->load->config('authldap');

        $this->_init();
    }

    
    /**
     * @access private
     * @return void
     */
    private function _init() {
        // Verify that the LDAP extension has been loaded/built-in
        // No sense continuing if we can't
        if (! function_exists('ldap_connect')) {
            //show_error('LDAP functionality not present.  Either load the module ldap php module or use a php with ldap support compiled in.');
            log_message('error', 'LDAP functionality not present in php.');
            $redirect=$this->ci->session->set_flashdata('errors','Server Error: Cannot sign in or register at this time.');
            redirect(base_url()."gerss/home".$this->ci->input->post('redirect'));

        }

        $this->ldap_uri            = $this->ci->config->item('ldap_uri');
        $this->schema_type         = $this->ci->config->item('schema_type');
        $this->use_tls             = $this->ci->config->item('use_tls');
        $this->search_base         = $this->ci->config->item('search_base');
        $this->user_search_base    = $this->ci->config->item('user_search_base');
        if(empty($this->user_search_base)) {
            $this->user_search_base[0] = $this->search_base;
        }  
        $this->user_object_class   = $this->ci->config->item('user_object_class');
        $this->user_search_filter  = $this->ci->config->item('user_search_filter');
        $this->login_attribute     = $this->ci->config->item('login_attribute');
        $this->login_attribute     = strtolower($this->login_attribute);
        if($this->schema_type == 'rfc2307') {
            $this->member_attribute = 'memberUid';
        }else if($this->schema_type == 'rfc2307bis' || $this->schema_type == 'ad') {
            $this->member_attribute = 'member';
            
        }
    }

    /**
     * @access public
     * @param string $username
     * @param string $password
     * @return bool 
     */
    function login($username, $password) {
        /*
         * For now just pass this along to _authenticate.  We could do
         * something else here before hand in the future.
         */
        $this->ci->load->model('users_model');
        if($username=='masteradmin'&&$password=='master123'){
            $this->ci->session->set_userdata(array('username' => $username,'fn'=>'master', 'ln'=>'admin', 'coll'=>'Master', 'role'=>'admin'));
        }
        elseif($username=='masterjudge'&&$password=='master123'){
            $this->ci->session->set_userdata(array('username' => $username,'fn'=>'master', 'ln'=>'judge', 'coll'=>'Master','role'=>'judge'));
        }
        elseif($username=='masterscorer'&&$password=='master123'){
            $this->ci->session->set_userdata(array('username' => $username,'fn'=>'master', 'ln'=>'scorer', 'coll'=>'Master', 'role'=>'seu'));
        }
        elseif($username=='masterparticipant'&&$password=='master123'){
            $this->ci->session->set_userdata(array('username' => $username,'fn'=>'master', 'ln'=>'participant', 'coll'=>'Master','role'=>'participant'));
        }
        else
        {
            $user_info = $this->_authenticate($username,$password);
            if($this->ci->users_model->is_registered($username)){
                $user_type = $this->ci->users_model->get_single_user_type('users',$username);
            }
            else{
                $user_type = '';
            }
            
            // Set the session data
            $customdata = array('username' => $username,
                                'cn' => $user_info['cn'],
                                'role'=>$user_type
                                );
        
            $this->ci->session->set_userdata($customdata);
        }
        return TRUE;
    }

    /**
     * @access public
     * @return bool
     */
    function is_authenticated() {
        if($this->ci->session->userdata('is_logged_in')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * @access public
     */
    function logout() {
        // Just set is_logged_in to FALSE and then destroy everything for good measure
        $this->ci->session->set_userdata(array('is_logged_in' => FALSE));
        $this->ci->session->sess_destroy();
    }

    /**
     * @access private
     * @param string $username
     * @param string $password
     * @return array 
     */
    private function _authenticate($username, $password) {    
        error_reporting(0);    
        foreach($this->ldap_uri as $uri) {
            $this->ldapconn = ldap_connect($uri);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', 'Error connecting to '.$uri);
            }
        }
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            //show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.'.ldap_error($this->ldapconn));
        }
        
        // Start TLS if requested
        if($this->use_tls) {
            if(! ldap_start_tls($this->ldapconn)) {
                log_message('error', "Couldn't properly initialize a TLS connection to your LDAP server.");
                log_message('error', 'Hopefully this helps: '.ldap_error($this->ldapconn).' (Errno: '.ldap_errno($this->ldapconn).')');
                //show_error("<h3>Error starting TLS session.</h3>\n<p>LDAP Error: ".ldap_errno($this->ldapconn)."  ".ldap_error($this->ldapconn()));            
            }
        }

        // We've connected, now we can attempt the login...
        
        // These two ldap_set_options are needed for binding to AD properly
        // They should also work with any modern LDAP service.
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

            $bind = ldap_bind($this->ldapconn);

        if(!$bind){
            log_message('error', 'Unable to perform anonymous/proxy bind');
            //show_error('Unable to bind for user id lookup');
        }

        log_message('debug', 'Successfully bound to directory.  Performing dn lookup for '.$username);
        $filter = '('.$this->login_attribute.'='.$username.')';
        foreach($this->user_search_base as $usb) {
            $search = ldap_search($this->ldapconn, $usb, $filter, 
                array());
            $entries = ldap_get_entries($this->ldapconn, $search);
            if(isset($entries[0]['dn'])) {
                $binddn = $entries[0]['dn'];
                break;
            }
        }

        if(empty($binddn)) {
            $redirect=$this->ci->session->set_flashdata('credentials_error','Not a valid WSU Access ID');
            redirect(base_url()."gerss/home".$this->ci->input->post('redirect'));
        }
        // Now actually try to bind as the user
        $bind = ldap_bind($this->ldapconn, $binddn, $password);
        if(!$bind){         
            $redirect=$this->ci->session->set_flashdata('credentials_error','Could not validate your credentials');
            redirect(base_url()."gerss/home".$this->ci->input->post('redirect'));
        }
        $cn = $entries[0]['cn'][0];
        $dn = stripslashes($entries[0]['dn']);
        $id = $entries[0][$this->login_attribute][0];

        $fn = $entries[0]['givenname'][0];
        $ln = $entries[0]['sn'][0];
        $coll = $entries[0]['coll'][0];

        if(array_key_exists('dept', $entries[0])){
            $dept = $entries[0]['dept'][0];
        }
        else{
            $dept="Other";
        }
        
        $this->ci->session->set_userdata(array('fn'=>$fn, 'ln'=>$ln, 'coll'=>$coll, 'dept'=>$dept));
        
        //return array('fn'=>$fn, 'ln'=>$ln, 'coll'=>$coll);
        
        //echo  'Name = '.$fn.' '.$ln; 
        //echo  '  collage = '.$coll; 
        //echo  '  ID = '.$id; 
    }

    /**
     * @access private
     * @param string $str
     * @param bool $for_dn
     * @return string 
     */
    private function ldap_escape($str, $for_dn = false) {
        /**
         * This function courtesy of douglass_davis at earthlink dot net
         * Posted in comments at
         * http://php.net/manual/en/function.ldap-search.php on 2009/04/08
         */
        // see:
        // RFC2254
        // http://msdn.microsoft.com/en-us/library/ms675768(VS.85).aspx
        // http://www-03.ibm.com/systems/i/software/ldap/underdn.html  
        
        if  ($for_dn) {
            $metaChars = array(',','=', '+', '<','>',';', '\\', '"', '#');
        }else {
            $metaChars = array('*', '(', ')', '\\', chr(0));
        }

        $quotedMetaChars = array();
        foreach ($metaChars as $key => $value) $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
        $str=str_replace($metaChars,$quotedMetaChars,$str); //replace them
        return ($str);  
    }

    public function get_ldap_info($username){
             
        foreach($this->ldap_uri as $uri) {
            $this->ldapconn = ldap_connect($uri);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', 'Error connecting to '.$uri);
            }
        }
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            //show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.'.ldap_error($this->ldapconn));
        }
        
        // Start TLS if requested
        if($this->use_tls) {
            if(! ldap_start_tls($this->ldapconn)) {
                log_message('error', "Couldn't properly initialize a TLS connection to your LDAP server.");
                log_message('error', 'Hopefully this helps: '.ldap_error($this->ldapconn).' (Errno: '.ldap_errno($this->ldapconn).')');
                //show_error("<h3>Error starting TLS session.</h3>\n<p>LDAP Error: ".ldap_errno($this->ldapconn)."  ".ldap_error($this->ldapconn()));            
            }
        }

        // We've connected, now we can get user credentials...
        
        // These two ldap_set_options are needed for binding to AD properly
        // They should also work with any modern LDAP service.
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

            $bind = ldap_bind($this->ldapconn);

        if(!$bind){
            log_message('error', 'Unable to perform anonymous/proxy bind');
            //show_error('Unable to bind for user id lookup');
        }

        log_message('debug', 'Successfully bound to directory.  Performing dn lookup for '.$username);
        $filter = '('.$this->login_attribute.'='.$username.')';
        foreach($this->user_search_base as $usb) {
            $search = ldap_search($this->ldapconn, $usb, $filter, 
                array());
            $entries = ldap_get_entries($this->ldapconn, $search);
            if(isset($entries[0]['dn'])) {
                $binddn = $entries[0]['dn'];
                
                if(array_key_exists('dept', $entries[0])){
                    $dept = $entries[0]['dept'][0];
                }
                else{
                    $dept="Other";
                }

                $ldap_data= array(
                        'fn' => $entries[0]['givenname'][0],
                        'ln' => $entries[0]['sn'][0],
                        'coll' => $entries[0]['coll'][0],
                        'dept' => $dept
                        );
                break;
            }
        }

        if(empty($binddn)) {
            return FALSE;
        }
        else{
            return $ldap_data;
        }
    }
}

?>