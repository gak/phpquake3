<?

/*
PHP Quake 3 Library
Copyright (C) 2006-2007 Gerald Kaszuba

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class q3query {

        private $rconpassword;
        private $fp;
        private $cmd;
        private $lastcmd;

        public function __construct($address, $port) {
                $this->cmd = str_repeat(chr(255), 4);
                $this->fp = fsockopen("udp://$address", $port, $errno, $errstr, 30);
                if (!$this->fp)
                        die("$errstr ($errno)<br />\n");
        }

        public function set_rconpassword($p) {
                $this->rconpassword = $p;
        }

        public function rcon($s) {
                sleep(1);
                $this->send('rcon '.$this->rconpassword.' '.$s);
        }

        public function get_response($timeout=5) {
                $s = '';
                $bang = time() + $timeout;
                while (!strlen($s) and time() < $bang) {
                        $s = $this->recv();
                }
                if (substr($s, 0, 4) != $this->cmd) {
                }
                return substr($s, 4);
        }

        private function send($string) {
                fwrite($this->fp, $this->cmd . $string . "\n");
        }

        private function recv() {
                return fread($this->fp, 9999);
        }

}

?>
