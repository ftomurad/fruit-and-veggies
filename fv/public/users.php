<?php
/**
 * USER MANAGEMENT (ADMIN ONLY)
 * -------------------------------------------------
 * 1. Header and admin check
 * 2. If POST — sanitize, validate, INSERT/UPDATE/DELETE
 * 3. If GET — get data for edit
 * 4. Get list and sort pages
 * 5. Show form and table below
 */

require_once '../template/header.php';
require_once '../src/dbconnect.php';
require_once '../common.php';

if (getUserRole() !== 'admin') {
  header('Location: index.php');
  exit;
}

$errors = [];
$editUser = null;

// Pages
$perPage = 10; // rows per page
// figure out what page we are on
if (isset($_GET['page'])) {
    $page = (int) $_GET['page'];         // converts to int
} else {
    $page = 1;                           // default page
}
$offset = ($page - 1) * $perPage;

// Handle POST
if (isset($_POST['action'])) {

    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id     = isset($_POST['id'])     ? $_POST['id']     : '';

    // sanitize input
    $user = [
        'username'       => escape($_POST['username'] ?? ''), //SAME AS 'username' => isset($_POST['username']) ? escape($_POST['username']) : ''
        'password'       => escape($_POST['password'] ?? ''),
        'role'           => escape($_POST['role'] ?? 'CUSTOMER'),
        'name'           => escape($_POST['name'] ?? ''),
        'surname'        => escape($_POST['surname'] ?? ''),
        'email'          => escape($_POST['email'] ?? ''),
        'phone'          => escape($_POST['phone'] ?? ''),
        'address_line1'  => escape($_POST['address_line1'] ?? ''),
        'address_line2'  => escape($_POST['address_line2'] ?? ''),
        'eircode'        => escape($_POST['eircode'] ?? '')
    ];

    // Required field check
    $required = ['username','password','name','surname','email','phone','address_line1','eircode'];
      foreach ($required as $field) {
          if ($user[$field] === '') {
          $errors[] = 'All fields are required';
          break; // stop after first missing field
          }
      }

    // Check for duplicate username, but only when saving
    if ($action === 'save' && !$errors) {
      $sql = "SELECT id FROM users WHERE username = :username";
      $params = ['username' => $user['username']];

      if ($id !== '') { //skip current user with this ID, ignore current row
          $sql .= " AND id <> :id";
          $params['id'] = $id;
      }
      $stmt = $connection->prepare($sql);
      $stmt->execute($params);
      if ($stmt->fetch()) {
          $errors[] = 'Username already exists.';
      }
    }

    // INSERT / UPDATE / DELETE
    if (!$errors) {
      try {
          // SAVE (Insert or Update)
          if ($action === 'save') {
              if ($id === '') {
                  // INSERT
                  $columns = array_keys($user);
                  $sql = "INSERT INTO users (" . implode(", ", $columns) . ") 
                          VALUES (:" . implode(", :", $columns) . ")";
              } else 
              {
                  // UPDATE
                  $user['id'] = $id;
                  $pairs = [];
  
                  foreach ($user as $key => $value) {
                      if ($key !== 'id') {
                          $pairs[] = "$key = :$key";
                      }
                  }
  
                  $sql = "UPDATE users SET " . implode(", ", $pairs) . " WHERE id = :id";
              }
  
              $stmt = $connection->prepare($sql);
              $stmt->execute($user);
              header("Location: users.php");
              exit;
          }
          // DELETE
          if ($action === 'delete' && $id !== '') {
              $stmt = $connection->prepare("DELETE FROM users WHERE id = :id");
              $stmt->execute(['id' => $id]);
              header("Location: users.php");
              exit;
          }
  
      } catch (PDOException $e) {
          $errors[] = "Database error: " . $e->getMessage();
      }
  }  
}

// When EDIT is pressed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $connection->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    //fetch single row from DB and return as an associative array in this format: 'id' => 1, 'username' => 'john', etc...
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC); 
}

// list and pages
$stmt = $connection->prepare("SELECT * FROM users ORDER BY id ASC LIMIT $offset, $perPage");
$stmt->execute();
$users = $stmt->fetchAll();

$total = $connection->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pages = ceil($total / $perPage);
?>

<!-- HTML PART -->
<div class="container py-4">
  <h2 class="mb-4">User Management</h2>

  <!-- errors -->
  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $err): ?>
        <p class="mb-0"><?php echo $err; ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- FORM part -->
  <form method="post"
        class="border rounded p-4 bg-light mb-4">

    <!-- hidden ID -->
    <input type="hidden"
           name="id"
           value="<?php echo (isset($editUser['id']) ? $editUser['id'] : ''); ?>">

    <!-- name and surname -->
    <div class="row mb-2">
      <div class="col">
        <label>First&nbsp;Name</label>
        <input  name="name"
                class="form-control"
                value="<?php echo escape(isset($editUser['name']) ? $editUser['name'] : ''); ?>"
                minlength="3"
                required>
      </div>
      <div class="col">
        <label>Last&nbsp;Name</label>
        <input  name="surname"
                class="form-control"
                value="<?php echo escape(isset($editUser['surname']) ? $editUser['surname'] : ''); ?>"
                minlength="3"
                required>
      </div>
    </div>

    <!-- username and password -->
    <div class="row mb-2">
      <div class="col">
        <label>Username</label>
        <input  name="username"
                class="form-control"
                value="<?php echo escape(isset($editUser['username']) ? $editUser['username'] : ''); ?>"
                required>
      </div>
      <div class="col">
        <label>Password</label>
        <input  name="password"
                type="password"
                class="form-control"
                minlength="3"
                value="<?php echo escape(isset($editUser['password']) ? $editUser['password'] : ''); ?>"
                required>
      </div>
    </div>

    <!-- email and phone -->
    <div class="row mb-2">
      <div class="col">
        <label>Email</label>
        <input  name="email"
                type="email"
                class="form-control"
                value="<?php echo escape(isset($editUser['email']) ? $editUser['email'] : ''); ?>"
                required>
      </div>
      <div class="col">
        <label>Phone</label>
        <input  name="phone"
                class="form-control"
                pattern="^[0-9+\-\s]+$"
                value="<?php echo escape(isset($editUser['phone']) ? $editUser['phone'] : ''); ?>"
                required>
      </div>
    </div>

    <!-- address lines -->
    <div class="mb-2">
      <label>Address&nbsp;Line&nbsp;1</label>
      <input  name="address_line1"
              class="form-control"
              value="<?php echo escape(isset($editUser['address_line1']) ? $editUser['address_line1'] : ''); ?>"
              required>
    </div>
    <div class="mb-2">
      <label>Address&nbsp;Line&nbsp;2</label>
      <input  name="address_line2"
              class="form-control"
              value="<?php echo escape(isset($editUser['address_line2']) ? $editUser['address_line2'] : ''); ?>">
    </div>

    <!-- eircode and role -->
    <div class="row mb-3">
      <div class="col">
        <label>Eircode</label>
        <input  name="eircode"
                class="form-control"
                value="<?php echo escape(isset($editUser['eircode']) ? $editUser['eircode'] : ''); ?>"
                required>
      </div>
      <div class="col">
        <label>Role</label>
        <select name="role" class="form-select" required>
          <?php
          $roles = ['ADMIN', 'USER', 'CUSTOMER'];
          foreach ($roles as $r) {
              $sel = (isset($editUser['role']) && $editUser['role'] === $r) ? 'selected' : '';
              echo "<option value=\"$r\" $sel>$r</option>";
          }
          ?>
        </select>
      </div>
    </div>

    <!-- add, delete and save buttons -->
    <div class="d-flex justify-content-between">
      <a href="users.php" class="btn orange-btn">Add&nbsp;New</a>
      <?php if ($editUser): ?>
        <button type="submit"
                name="action"
                value="delete"
                class="btn orange-btn"
                onclick="return confirm('Are you sure?')">
          Delete
        </button>
      <?php endif; ?>
      <button type="submit" name="action" value="save" class="btn orange-btn">Save</button>
    </div>
  </form>

  <!-- table -->
  <table class="table table-bordered text-center table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr class="<?php echo (isset($_GET['id']) && $_GET['id'] == $u['id']) ? 'table-success' : ''; ?>">
          <td><?php echo $u['id']; ?></td>
          <td><?php echo escape($u['username']); ?></td>
          <td><?php echo $u['role']; ?></td>
          <td><?php echo escape($u['name']); ?></td>
          <td><?php echo escape($u['surname']); ?></td>
          <td><?php echo escape($u['email']); ?></td>
          <td>
            <a href="users.php?id=<?php echo $u['id']; ?>" class="btn btn-sm orange-btn">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Page numbers -->
  <ul class="pagination justify-content-center">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
        <a class="page-link page-link-custom" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</div>

<?php require_once '../template/footer.php'; ?>
