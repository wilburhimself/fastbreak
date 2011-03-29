<?php
    class Dates {
        const HOUR = 3600;
        const DAY = 86400;
        const WEEK = 604800;


        public $timestamp;

        public function __construct($timestamp=null) {
            if (empty($timestamp)) $timestamp = time();
            $this->timestamp = $timestamp;
        }

        public function get_day() {
            return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        }

        public function get_timestamp() {
            return $this->timestamp;
        }

        public function get_date_from_week()
        {
            $first_day = strtotime(date('Y', $this->timestamp)."-01-01");
            $is_monday = date("w", $first_day) == 1;
            $is_weekone = strftime("%V", $first_day) == 1;
            if($is_weekone)
            {
                $week_one_start = $is_monday ? strtotime("last monday",
                $first_day) : $first_day;
            }
            else
            {
                $week_one_start = strtotime("next monday", $first_day);
            }
            $this->timestamp = $week_one_start+(self::WEEK*(date('W', $this->timestamp)-1));
            return $this->timestamp;
        }

        public function remove_day() {
            $this->timestamp -= self::DAY;
        }
        public function add_day() {
            $this->timestamp += self::DAY;
        }

        public function __tostring() {
            return $this->timestamp;
        }
    }
?>