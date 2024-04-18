<?ob_start();?>
<?php
    class counter {

        public function getAddressCnt() {
            $filename = "counterAddress.txt";
            if (file_exists($filename)) {
                $fp = fopen($filename, "r");
                $counter = fgets($fp, 99);
                fclose($fp);
            } else {
                $counter = 0;
            }

            $fp = fopen($filename, "w");
            $counter += 1;
            fwrite($fp, $counter);
            fclose($fp);

            $cnt = "A" . $counter;
            return $cnt;
        }

        public function getOrderCnt() {
            $filename = "counterOrder.txt";
            if (file_exists($filename)) {
                $fp = fopen($filename, "r");
                $counter = fgets($fp, 99);
                fclose($fp);
            } else {
                $counter = 0;
            }

            $fp = fopen($filename, "w");
            $counter += 1;
            fwrite($fp, $counter);
            fclose($fp);

            $OrderID = "OR" . $counter;
            return $OrderID;
        }
    }
?>