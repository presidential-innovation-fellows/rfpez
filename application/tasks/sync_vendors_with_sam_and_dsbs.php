<?php

class Sync_Vendors_With_Sam_And_Dsbs_Task {

  public function run() {

    foreach(Vendor::all() as $vendor) {
      $vendor->sync_with_dsbs_and_sam();
    }

  }

}
