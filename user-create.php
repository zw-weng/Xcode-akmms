<?php
include('mysession.php');

if (!session_id()) {
    session_start();
}

// Include database connection
include('dbconnect.php');

// Get id for current user
$suid = intval($_SESSION['suid']);  // Convert to integer

// CRUD: Retrieve current user using prepared statement
$sqlc = "SELECT * FROM tb_user INNER JOIN tb_usertype ON tb_user.type_id = tb_usertype.type_id WHERE tb_user.user_id = ?";
$stmt = mysqli_prepare($con, $sqlc);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $suid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    // Fetch user types
    $sqlUserTypes = "SELECT * FROM tb_usertype";
    $resultUserTypes = mysqli_query($con, $sqlUserTypes);

    if (!$resultUserTypes) {
        die("Query failed: " . mysqli_error($con));
    }

    // Display result
    include 'headeradmin.php';
} else {
    die("Prepare statement failed: " . mysqli_error($con));
}
?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>User Management</h1>
      <div class="d-flex justify-content-between align-items-center">
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="adminmain.php">Home</a></li>
              <li class="breadcrumb-item"><a href="user.php">User Management</a></li>
              <li class="breadcrumb-item active">Add User</li>
          </ol>
      </nav>

      <div class="btn-group me-2">
          <a href="user.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add User</h5>

              <?= alertMessage(); ?>

              <form method="POST" action="user-createprocess.php">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <label for="fid" class="form-label mt-4">User ID</label>
                          <input type="text" name="fid" class="form-control" id="fid" placeholder="" required>
                      </div>
                      <div class="col-md-6">
                          <label for="fic" class="form-label mt-4">Identity Card No.</label>
                          <input type="text" name="fic" class="form-control" id="fic" placeholder="" required>
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-6">
                          <label for="ffname" class="form-label mt-4">First Name</label>
                          <input type="text" name="ffname" class="form-control" id="ffname" placeholder="" required>
                      </div>
                      <div class="col-md-6">
                          <label for="flname" class="form-label mt-4">Last Name</label>
                          <input type="text" name="flname" class="form-control" id="flname" placeholder="" required>
                      </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-6">
                          <label for="fpwd" class="form-label mt-4">Password</label>
                          <input type="password" name="fpwd" class="form-control" id="fpwd" aria-describedby="emailHelp" placeholder="" autocomplete="off" required>
                          <small id="emailHelp" class="form-text text-muted">Please include with symbol</small>
                      </div>

                      <div class="col-md-6">
                          <label for="fphone" class="form-label mt-4">Phone No</label>
                          <input type="text" name="fphone" class="form-control" id="fphone" placeholder="" required>
                      </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1" class="form-label mt-4">Email address</label>
                    <input type="email" name="femail" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleTextarea" class="form-label mt-4">Address</label>
                    <textarea class="form-control" name="fadd" id="exampleTextarea" rows="3" required></textarea>
                  </div>

                  <div class="form-group row">
                      <div class="col-md-6">
                          <label for="fposition" class="form-label mt-4">Position</label>
                          <input type="text" name="fposition" class="form-control" id="fposition" placeholder="" required>
                      </div>

                      <div class="col-md-6">
                          <label for="ftype" class="form-label mt-4">User Type</label>
                          <select name="ftype" class="form-control" id="ftype" required>
                              <?php
                                  while ($rowType = mysqli_fetch_array($resultUserTypes)) {
                                      echo "<option value='" . $rowType['type_id'] . "'>" . $rowType['type_desc'] . "</option>";
                                  }
                              ?>
                          </select>
                      </div>
                  </div>

                  <br><br>
                  <div class="d-flex justify-content-end">
                      <button type="submit" name="saveuser" class="btn btn-primary">Save</button>
                  </div>
              </form>
            </div>
          </div>
          </div>

        </div>
      </div>
    </section>

    <?php mysqli_close($con); ?>
  </main><!-- End #main -->

<?php include 'footer.php'; ?>