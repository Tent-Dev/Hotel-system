<form method="GET" action="../system_hotel_user_find.php" id="asendSearchUserForm" style="display: contents;">
  <?php
  if (isset($_GET['checkin'])) {
    $checkinsave = date('m/d/Y', strtotime($_GET['checkin']));
    $checkoutsave = date('m/d/Y', strtotime($_GET['checkout']));
  }
  else{
    $checkinsave = date('m/d/Y');
    $checkoutsave = date('m/d/Y', strtotime('+1 Days'));
  }
  
  ?>
<div class="col-12">
  <div class="row text-custom-searchbar searchbar-align">
    <div class="col-md-7 col-lg-6 input-daterange" id="datepicker">
      <div class="row text-custom-detail">
      <div class="col-xs-custom-12 col-6">
        <div class="col-12"><label>Check in</label><br>
          <div class="input-group">
            <input type="text" class="textbox-foruser form-control checkin" id="checkin" name="checkin" required autocomplete="off" value="<?php echo $checkinsave ?>">
            <div class="input-group-prepend">
              <div class="input-group-text badge-textbox-black badge-textbox-black-right-radius"><i class="fas fa-calendar-alt"></i></div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="col-xs-custom-12 col-6">
        <div class="col-12"><label>Check out</label><br>
          <div class="input-group">
            <input type="text" class="textbox-foruser form-control checkout" id="checkout" name="checkout" required autocomplete="off" value="<?php echo $checkoutsave ?>">
            <div class="input-group-prepend">
              <div class="input-group-text badge-textbox-black badge-textbox-black-right-radius"><i class="fas fa-calendar-alt"></i></div>
            </div>
          </div>
        </div>
      </div> 
    </div>
    </div>

    <div class="col-xs-custom-6 col-3 col-md-2 col-lg-2 text-custom-detail">
      <div class="col-12"><label>Guests</label><br>
        <select class="textbox-foruser form-control guests" id="guests" name="guests">
          <?php for($i=1;$i<=10;$i++){ ?>
          <option value="<?php echo $i ?>"><?php echo $i ?></option>
          <?php } ?>
         </select>
      </div>
    </div>

    <div class="col-xs-custom-6 col-3 col-md-2 col-lg-2 text-custom-detail">
      <div class="col-12"><label>Rooms</label><br>
        <select class="textbox-foruser form-control rooms" id="rooms" name="rooms">
          <?php for($i=1;$i<=10;$i++){ ?>
          <option value="<?php echo $i ?>"><?php echo $i ?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="col-xs-custom-12 col-1 row row-xs-custom align-content-end align-items-end-safari">
      <input type="submit" id="search" class="btn button-dark" value="Search">
    </div>
</div>
</div>
  </form>