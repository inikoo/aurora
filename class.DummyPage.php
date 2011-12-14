<?php
class Dummy_Page {



    public function get($key) {
        return $key;


    }


    function get_found_in() {

        return array(array('link'=>'Parent Page'));

    }

    function get_see_also() {
        return array(array('link'=>'<a href="#">Link 1</a>'),array('link'=>'<a href="#">Link 2</a>'),array('link'=>'<a href="#">Link 3</a>'),array('link'=>'<a href="#">Link 4</a>'));
    }

    function display_title() {
        return 'Header Title';
    }

    function display_label() {
        return 'Code';
    }
    function display_top_bar() {}
    
     function display_search(){
    print $this->site->display_search();
    }
    
    function display_menu(){
     print $this->site->display_menu();
    }
    
}
?>