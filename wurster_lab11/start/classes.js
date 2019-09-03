'use strict';

document.addEventListener('DOMContentLoaded', () => {
    let creditFilter = document.getElementById('credit-filter');
    let departmentFilter = document.getElementById('department-filter');
    let classRequest = fetch('classes.php');
    classRequest.then((response) => {
        return response.json();
    }).then((jsonData) => {
        document.getElementById('classes-page').className = 'loaded';
        updateTable(jsonData);
    });

    let creditRequest = fetch('credits.php');
    creditRequest.then((response) => {
        return response.json();
    }).then((jsonData) => {
        document.getElementById('credits-page').className = 'loaded';
        addCreditListOptions(creditFilter, jsonData);
    });

    let departmentRequest = fetch('departments.php');
    departmentRequest.then((response) => {
        return response.json();
    }).then((jsonData) => {
        document.getElementById('departments-page').className = 'loaded';
        addDepartmentListOptions(departmentFilter, jsonData);
    });

    creditFilter.addEventListener('change', getAndUpdateClassList);
    departmentFilter.addEventListener('change', getAndUpdateClassList);
});

function updateTable(classesData) {
    let tableBody = document.getElementById('class-table').lastElementChild;
    tableBody.innerHTML = '';
    classesData.forEach((classData) => {
        let row = makeClassRow(classData);
        tableBody.appendChild(row);
    });
}

function makeClassRow(classData) {
    let row = document.createElement('tr');
    let keys = ['class_id', 'name', 'department', 'credits'];
    keys.forEach((key) => {
        let td = document.createElement('td');
        let tdText = document.createTextNode(classData[key]);
        td.appendChild(tdText);
        row.appendChild(td);
    });
    return row;
}

function addCreditListOptions(element, data) {
    data.forEach((value) => {
        let option = document.createElement('option');
        let optionText = document.createTextNode(value);
        option.value = value;
        option.appendChild(optionText);
        element.appendChild(option);
    });
}

function addDepartmentListOptions(element, data) {
    data.forEach((value) => {
        let option = document.createElement('option');
        let optionText = document.createTextNode(value.name);
        option.value = value.department_id;
        option.appendChild(optionText);
        element.appendChild(option);
    });
}

function getAndUpdateClassList(evnt) {
    let creditFilter = document.getElementById('credit-filter');
    let departmentFilter = document.getElementById('department-filter');
    let credits = creditFilter.value;
    let department = departmentFilter.value;

    let url = 'classes.php?';
    if (department !== '') {
        url += 'department=' + department + '&';
    }
    if (credits !== '') {
        url += 'credits=' + credits;
    }

    fetch(url).then((response) => {
        return response.json();
    }).then((jsonData) => {
        updateTable(jsonData);
    });
}
