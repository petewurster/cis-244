<?php
$employeeDb = new PDO('sqlite:employees.db');
if (isset($_POST['add'])) {
    $queryString = "INSERT INTO employee (employee_id, first_name, last_name, email, extension) VALUES (" . $_POST['employee_id'] . ", '" . $_POST['first_name'] . "', '" . $_POST['last_name'] . "', '" . $_POST['email'] . "', '" . $_POST['extension'] . "')";
    $query = $employeeDb->prepare($queryString);
    $query->execute();
}

if (isset($_POST['delete'])) {
    $queryString = "DELETE FROM employee WHERE employee_id = " . $_POST['employee_id'];
    $query = $employeeDb->prepare($queryString);
    $query->execute();
}

if (isset($_POST['update'])) {
    $queryString = "UPDATE employee SET first_name = '" . $_POST['first_name'] . "', last_name = '" . $_POST['last_name'] . "', email = '" . $_POST['email'] . "', extension = '" . $_POST['extension'] . "' WHERE employee_id = " . $_POST['employee_id'];
    // print $queryString;
    $query = $employeeDb->prepare($queryString);
    $query->execute();
    unset($_GET);
}

if (isset($_GET['employee_id'])) {
    $queryString = "SELECT * FROM employee WHERE employee_id = " . $_GET['employee_id'];
    $query = $employeeDb->prepare($queryString);
    $query->execute();
    $employeeToEdit = $query->fetch();
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
        <tr>
            <td><?php print $employee['first_name']; ?></td>
            <td><?php print $employee['last_name']; ?></td>
            <td><a href="mailto:<?php print $employee['email']; ?>"><?php print $employee['email']; ?></a></td>
            <td><?php print $employee['extension']; ?></td>
            <td>
                <form method="GET">
                    <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                    <input type="submit" value="Edit" name="edit">
                </form>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
                    <input type="submit" value="Delete" name="delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if isset($employeeToEdit): ?>
    <form method="POST">
        <h2>Edit Employee</h2>
        <div>
            <label for="first_name">First name:</label>
            <input required type="text" id="first_name" name="first_name" value="<?php print $employee['first_name']; ?>">
        </div>
        <div>
            <label for="last_name">Last name:</label>
            <input required type="text" id="last_name" name="last_name" value="<?php print $employee['last_name']; ?>">
        </div>
        <div>
            <label for="email">Email address:</label>
            <input required type="email" id="email" name="email" value="<?php print $employee['email']; ?>">
        </div>
        <div>
            <label for="extension">Extension:</label>
            <input required type="text" id="extension" name="extension" value="<?php print $employee['extension']; ?>">
        </div>
        <div>
            <input type="hidden" name="employee_id" value="<?php print $employee['employee_id']; ?>">
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

</body>
</html>
