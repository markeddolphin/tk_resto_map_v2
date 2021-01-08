<?php
/**
 * Yii html compressor filter
 *
 * @author Marc Oliveras Galvez <oligalma@gmail.com> 
 * @link http://www.ho96.com
 * @copyright 2015 Hosting 96
 * @license New BSD License
 */

class HtmlCompressorFilter extends CFilter
{
    protected function preFilter($filterChain)
    {
        if(!YII_DEBUG)
            ob_start();
        
        return true;
    }
 
    protected function postFilter($filterChain)
    {
        if(!YII_DEBUG)
        {
            /*$contents = preg_replace_callback(
                '/<style\\b[^>]*>(.*?)<\\/style>/s',
                function ($matches) {
                return self::compressCSS($matches[0]);                
                },
                ob_get_clean()
            );*/
            
            $contents=ob_get_clean();
  
            $contents = self::compressHTML($contents);
            
            echo $contents;
        }
    }

    public static function compressHTML($buffer)
    {    
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
    
        $replace = array(
            '>',
            '<',
            '\\1'
        );
    
        $buffer = preg_replace($search, $replace, $buffer);
    
        return $buffer;
    }
    
    public static function compressCSS($buffer)
    {
        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // Remove space after colons
        $buffer = str_replace(': ', ':', $buffer);
        // Remove whitespace
        $buffer = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        
        return $buffer;
    }
}