<?php
/**
 * Part of Serverpoked Alarm system.
 * System to monitor a linux server running gawk,apache,mysql and php
 * on memory use, disc space and… .
 * 
 * @author Wouter van den Brink <wouter@van-den-brink.net>
 */

//namespace Autoloader;

/**
 * An namespace based  Autoloader.
 * 
 * @see https://thomashunter.name/blog/simple-php-namespace-friendly-autoloader-class/
 *
 * @author or better modifier: Wouter van den Brink, wouter@van-den-brink.net
 * 
 */
class Autoloader {

    /**
     * Autoloader class.
     * Finds classes in Serverpokedalarm/Classes, interfaces in Serverpokedalarm/Interfaces and traits in Serverpokedalarm/Traits
     * 
     * @param string $className name, including namespaces, of class, interface or trait to load
     * @return boolean
     */
    static public function loader($className) {
        
      if(mb_substr($className,0,mb_strlen('Serverpokedalarm\\Classes\\'))=='Serverpokedalarm\\Classes\\'){
          //echo 'Class';
         // echo "\n";
         $filename = "".str_replace('\\','/', $className).".php";
          if (file_exists($filename)) {
            include($filename);
            //echo $className;
            if (class_exists($className)) {
                return true;
            }
         }
      }elseif(mb_substr($className,0,mb_strlen('Serverpokedalarm\\Interfaces\\'))=='Serverpokedalarm\\Interfaces\\'){
          //echo 'Interface';
          $filename = "".str_replace('\\','/', $className).".php";
          
          if (file_exists($filename)) {
            
            include($filename);
            //echo $className
            if (interface_exists($className)) {
                
                return true;
            }
         }
      }elseif(mb_substr($className,0,mb_strlen('Serverpokedalarm\\Traits\\'))=='Serverpokedalarm\\Traits\\'){
          //echo 'Trait';
           $filename = "".str_replace('\\','/', $className).".php";
          if (file_exists($filename)) {
            
            include($filename);
            //echo $className;
            if (trait_exists($className)) {
                
                return true;
            }
         }
      }else{return false;}
        
    }
}
