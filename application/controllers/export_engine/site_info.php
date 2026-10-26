<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); error_reporting(0);
    /* Export Controller
     * Author      : alen@alenlabs.com
     * Module demo : http://10.0.15.5/0/svn/sims/isr
     *
     * How to use?
     * 1. Define your function name (ex: isr_list)
     * 2. To export a list,Create a button on your form and navigate to http://10.0.15.5/export/?module=isr_list&format=excel
     * 3. Now it suppport excel format, pdf format soon (not promised yet).
     * 4. From the url above it will refer to your isr_list function and export the query to excel file
     * 5. You can add parameter to your function by adding this to your url, example:
                http://10.0.15.5/export/?module=isr_list&format=excel&param[isactive]=1&param[name]=alen
                etc..
       6. Please take a look on my "isr_list" function for example.
       7. Try before ask.
     * 8. If you are developer and don't know what kind of thing i mentioned above, it's time to go home.
    */
    

class Site_info extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->mglobal->is_logged_in();
    }
    function index(){
        $this->exporter->index('excel');
        }
    function sites(){
        return $this->db->query("select * v_site_info");
    }
}