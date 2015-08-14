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
                     * Utilities class.
                     * Utilities class providing diverse methods . 
                     *
                     * @author Wouter van den Brink <wouter@van-den-brink.net>
                     */


                     class ServerPokerUtilities {
                        /**
                         * Utility function: Test presence of command on this server.
                         * 
                         * @see http://stackoverflow.com/questions/12424787/how-to-check-if-a-shell-command-exists-from-php
                         * @param string $cmd
                         * @return boolean
                         */
                        protected function command_exist($cmd) {
                            //echo "method command_exists activated";
                            $returnVal = shell_exec("which $cmd");
                            return (empty($returnVal) ? false : true);
                        }
                        /**
                         * Tests if gawk of awk command is present of not on server.
                         * 
                         * @return boolean|string false if no gawk or awk is present,or gawk or awk depending
                         */
                        protected function testGawk(){
                            //test for gawk
                            //echo "Method testGawk activated";
                            $gawk='';
                            if($this->command_exist('gawk')){
                                $gawk='gawk';
                            }elseif($this->command_exist('awk')){
                                $gawk = 'awk';
                            }else{
                                ;$gawk=false;
                            }
                            return $gawk;
                        }
                     }
