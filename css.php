/* Basic Reset */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1.5rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Header Styling */
h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
}

h2 {
    font-size: 1.75rem;
    color: #555;
    margin-bottom: 1rem;
    font-weight: 500;
    text-align: center;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    font-size: 0.875rem;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s;
    color: #fff;
    text-decoration: none;
}

.btn-primary {
    background-color: #4CAF50;
}

.btn-primary:hover {
    background-color: #45a049;
}

.btn-warning {
    background-color: #f0ad4e;
}

.btn-warning:hover {
    background-color: #ec971f;
}

.btn-danger {
    background-color: #d9534f;
}

.btn-danger:hover {
    background-color: #c9302c;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5rem;
}

thead {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

thead th {
    padding: 1rem;
    text-align: left;
}

tbody tr {
    border-bottom: 1px solid #ddd;
    transition: background-color 0.2s;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

td, th {
    padding: 0.75rem;
    text-align: left;
}

td img {
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 50px;
    height: auto;
}

/* Form */
form {
    max-width: 700px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

form input[type="text"],
form input[type="date"],
form textarea,
form select {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
    color: #333;
}

form button[type="submit"] {
    width: 100%;
    padding: 0.8rem;
    font-size: 1rem;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

/* Footer */
footer {
    text-align: center;
    padding: 1rem 0;
    color: #666;
    font-size: 0.875rem;
}

footer a {
    color: #4CAF50;
    text-decoration: none;
    transition: color 0.3s;
}

footer a:hover {
    color: #45a049;
}
