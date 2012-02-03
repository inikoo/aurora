<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.translate.php
 * Type:     block
 * Name:     translate
 * Purpose:  translate a block of text
 * -------------------------------------------------------------
 */
function smarty_block_t($params, $content, &$smarty, &$repeat)
{
    // only output on the closing tag
    if(!$repeat){
        if (isset($content)) {
            
            return _($content);
        }
    }
}
?>
