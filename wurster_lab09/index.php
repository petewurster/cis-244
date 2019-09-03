<?php
//include validation features
require_once 'valid.php';

//package up and validate GET & POST for PDO binding
$userinput = stripPost();
$userget = validate([$_GET['employee_id'] ?? null]);
$error = array_pop($userinput);
$employeeDb = new PDO('sqlite:employees.db');

if (isset($_POST['add'])) {
    $queryString = "INSERT INTO employee (employee_id, first_name, last_name, email, extension) VALUES (:id, :fname, :lname, :email, :ext)";
    $query = $employeeDb->prepare($queryString);
    $query->execute($userinput);
    unset($_GET);
}

if (isset($_POST['delete'])) {
    $queryString = "DELETE FROM employee WHERE employee_id = :id";
    $query = $employeeDb->prepare($queryString);
    $query->execute($userinput);
    unset($_GET);
}

if (isset($_POST['update'])) {
    $queryString = "UPDATE employee SET first_name = :fname, last_name = :lname, email = :email, extension = :ext WHERE employee_id = :id";
    $query = $employeeDb->prepare($queryString);
    $query->execute($userinput);
    unset($_GET);
}

if (isset($_GET['employee_id'])) {
    $queryString = "SELECT * FROM employee WHERE employee_id = :id";
    $query = $employeeDb->prepare($queryString);
    $query->execute($userget);
    $employeeToEdit = $query->fetch(PDO::FETCH_ASSOC);
}

$query = $employeeDb->prepare('SELECT * FROM employee ORDER BY last_name ASC');
$query->execute();
$employees = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee Directory</title>
</head>
<body>
    <h1>Employee Directory</h1>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Extension</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach($employees as $employee): ?>
<!-- output sanitized -->
        <tr>
            <td><?php print san($employee['first_name']); ?></td>
            <td><?php print san($employee['last_name']); ?></td>
            <td><a href="mailto:<?php print san($employee['email']); ?>"><?php print san($employee['email']); ?></a></td>
            <td><?php print san($employee['extension']); ?></td>
            <td>
                <form method="GET">
                    <input type="hidden" name="employee_id" value="<?php print san($employee['employee_id']); ?>">
                    <input type="submit" value="Edit" name="edit">
                </form>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="employee_id" value="<?php print san($employee['employee_id']); ?>">
                    <input type="submit" value="Delete" name="delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if(isset($employeeToEdit)): ?>
<!-- output sanitized -->
    <form method="POST">
        <h2>Edit Employee</h2>
        <div>
            <label for="first_name">First name:</label>
            <input required type="text" id="first_name" name="first_name" value="<?php san(print $employeeToEdit['first_name']); ?>">
        </div>
        <div>
            <label for="last_name">Last name:</label>
            <input required type="text" id="last_name" name="last_name" value="<?php print san($employeeToEdit['last_name']); ?>">
        </div>
        <div>
            <label for="email">Email address:</label>
            <input required type="email" id="email" name="email" value="<?php print san($employeeToEdit['email']); ?>">
        </div>
        <div>
            <label for="extension">Extension:</label>
            <input required type="text" id="extension" name="extension" value="<?php print san($employeeToEdit['extension']); ?>">
        </div>
        <div>
            <input type="hidden" name="employee_id" value="<?php print san($employeeToEdit['employee_id']); ?>">
            <input type="submit" name="update" value="Edit employee">
        </div>
    </form>
    <?php else: ?>

    <form method="POST">
        <h2>Add Employee</h2>

        <div>
            <label for="employee_id">Employee Id:</label>
            <input required type="number" id="employee_id" name="employee_id">
        </div>
        <div>
            <label for="first_name">First name:</label>
            <input required type="text" id="first_name" name="first_name">
        </div>
        <div>
            <label for="last_name">Last name:</label>
            <input required type="text" id="last_name" name="last_name">
        </div>
        <div>
            <label for="email">Email address:</label>
            <input required type="email" id="email" name="email">
        </div>
        <div>
            <label for="extension">Extension:</label>
            <input required type="text" id="extension" name="extension">
        </div>
        <div>
            <input type="submit" name="add" value="Add new employee">
        </div>
    </form>
    <?php endif; ?>

<!-- display any error messages -->
    <?php if(!empty($error)): ?>
    <aside style="width:330px; margin-top:40px; border:ridge 3px #000000;">
        <ul style="margin-right:40px;"><b>Could not complete your request:</b></ul>
        <?php foreach($error as $e): ?>
            <li style="margin-left:20px;"><?php echo $e; ?></li>
        <?php endforeach; ?>
    </aside>
    <?php endif; ?> 

</body>
</html>
