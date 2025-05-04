<?php
require_once '../template/header.php';  // session_start
require_once '../private/pr_users.php';  // php part
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
