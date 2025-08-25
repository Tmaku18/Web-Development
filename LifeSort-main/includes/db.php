<?php
function save_score($name, $age, $wealth) {
  $data = "$name,$age,$wealth\n";
  file_put_contents("leaderboard.txt", $data, FILE_APPEND);
}

function get_leaderboard() {
  if (!file_exists("leaderboard.txt")) return [];
  $scores = file("leaderboard.txt");
  return array_map(function($line) {
    return explode(',', trim($line));
  }, $scores);
}
?>
