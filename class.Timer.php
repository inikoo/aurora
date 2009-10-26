<?php
class Timer {
  static $milestones;
  function microtime_float() {
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
  }
  function timing_milestone($name) {
    self::$milestones[] = array($name, self::microtime_float());
  }
  function dump_profile($return = false) {
    self::$milestones[] = array('finish', self::microtime_float());
    $output = '<table border="1">'.
              '<tr><th>Milestone</th><th>Diff</th><th>Cumulative</th></tr>';
    foreach (self::$milestones as $elem => $data) {
      $output .= '<tr><td>'.$data[0].'</td>'.
        '<td>'.round(($elem ? $data[1] - self::$milestones[$elem - 1][1]: '0'), 5).'</td>'.
        '<td>'.round(($data[1] - self::$milestones[0][1]), 5).'</td></tr>';
    }
    $output .= '</table>';
    if ($return) return $output;
    echo $output;
  }
}

?>