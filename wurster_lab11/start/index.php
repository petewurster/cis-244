<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Classes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Class Viewer</h1>
    <hr>
    <div id="load-info">
        <h2>Loading status</h2>
        <h3 id="credits-page" class="not-loaded">credits.php</h3>
        <h3 id="departments-page" class="not-loaded">departments.php</h3>
        <h3 id="classes-page" class="not-loaded">classes.php</h3>
    </div>

    <div id="filters">
        <h2>Filter By</h2>
        <p><label for="credit-filter">Credits</label>
        <select name="credits" id="credit-filter">
            <option value="">ANY</option>
        </select>
        </p>
        <p>
        <label for="department-filter">Department</label>
        <select name="department" id="department-filter">
            <option value="">ANY</option>
        </select>
        </p>
    </div>
    <table id="class-table">
        <thead>
            <tr>
                <th>Class Id</th>
                <th>Class Name</th>
                <th>Department</th>
                <th>Credits</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <script src="classes.js"></script>
</body>
</html>
