<?php

class Increment_Deploy_Timestamp_Task extends Task {

	public function run() {
    print file_put_contents("deploy_timestamp.txt", time());
  }

}
