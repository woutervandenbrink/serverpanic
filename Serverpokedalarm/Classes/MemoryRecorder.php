<?php
                    /**
                     * Part of Serverpoked Alarm system.
                     * System to monitor a linux server running gawk,apache,mysql and php
                     * on memory use, disc space and… .
                     * 
                     * @author Wouter van den Brink <wouter@van-den-brink.net>
                     */

                    namespace Serverpokedalarm\Classes;

                    /**
                     * Memory recording class.
                     * Memory recording class that also reads recorded data to generate
                     * a mean memory usage datum . 
                     *
                     * @author Wouter van den Brink <wouter@van-den-brink.net>
                     */
                    class MemoryRecorder {

                        protected $memstatdbdir;//directory for memory usage info data files
                        function __construct() {
                            $this->memstatdbdir=__DIR__.'/../memstatdb';
                            
                        }
                        /**
                         * Records memory info to file.
                         * Performs linux command line command 'free' and gawk script line to collect
                         * memory info and writes this info to a file
                         * 
                         * @return boolean true | false on success | failure
                         */
                        public function recordMemory(){
                            //string to perform as linux command line command
                            $freememquerystring = "free -m | gawk '/Mem:/ {print $2;};/buffers\/cache:/{print \"\$test=array(\\\"used_mb\\\"=>\"$3\",\\\"free_mb\\\"=>\"$4\",\\\"timestamp\\\"=>".time().")\"}'";
                            //execution of command
                            if(exec($freememquerystring,$outputarray)){
                                //print_r($outputarray);
                                eval($outputarray[1] . ";");//to create array $test from the exec - $outputarray 
                                //print_r($test,true);
                                //add item to $test array with item from $outputarray
                                 $test['total_mb'] = $outputarray[0];
                                if(isset($test['total_mb'])&&$test['total_mb']!=0){
                                        $test['percent']= 100*($test['free_mb']/$test['total_mb']);
                                }
                                
                               // echo "testarray: ". print_r($test,true);
                                //%A	A full textual representation of the day	Sunday through Saturday
                                //%H	Two digit representation of the hour in 24-hour format	00 through 23
                                //echo __DIR__.'/../memstatdb/'. strftime ( '%A_%H',time()) .      'freememstat.php';
                                if(file_put_contents(  __DIR__.'/../memstatdb/'. strftime ( '%A_%H',time()) .      'freememstat.php',serialize($test))) {
                                    //todo: change owner and group to www-data: tis in case of cronjob: or run cronjob for www-data
                                    return true;
                                
                                }else {
                                    return false;
                                    
                                };
                            
                            }else{return false;}//how to improve test?: isset($outputarray) perhaps?
                        }
                        
                        /**
                         * Determine if mean memory use is not to great.
                         * 
                         * Reads all available records (made by method recordMemory) 
                         * and determines mean used memory  percentage. If this is greater than 80
                         * it returns false, else it returns true
                         * 
                         * @return boolean true | false false if mean memory use percentage is greater than 80, else true
                         */
                        public function getMemoryUse(){
                            $dircontentlist=scandir($this->memstatdbdir);
                            //echo "hola"; print_r($dircontentlist);echo "obolo";
                            $testherstelarray = array();
                            foreach($dircontentlist as $keydir=>$valuedir){
                                if(mb_ereg('freememstat.php',$valuedir)){
                                    // echo $valuedir;
                                    // ++$counterhier;
                                    // echo $counterhier;
                                    $testherstelarray[]=unserialize(file_get_contents( __DIR__.'/../memstatdb/'.$valuedir));
                                 };
                            }
                            //print_r($testherstelarray);
                           if(is_array($testherstelarray)){
                               $itemscount = count($testherstelarray);
                               $accumulatedpercentage=(float)0;
                               foreach($testherstelarray as $itemvalue){
                                   $accumulatedpercentage +=$itemvalue['percent'];
                               }
                               //echo $accumulatedpercentage;
                               $meanpercentage= $accumulatedpercentage / $itemscount;
                               if ($meanpercentage>80){
                                   return false;
                               }else{
                                   return true;
                               }
                           }
                        }
                    }
?>