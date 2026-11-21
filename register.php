<!Doctype html>
<html>
     <head>
            <title> Register Student </title>
            <link rel="stylesheet" href="css/style.css">
            <style>
                /* Styling the select dropdown */
select {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    background-color: #ffffff; /* Ensures a clean white background */
    color: #334155;            /* Matches your label/text color */
    box-sizing: border-box;
    cursor: pointer;
    appearance: none;          /* Removes the default browser styling */
    -webkit-appearance: none;
    -moz-appearance: none;
    
    /* Adds a custom clean dropdown arrow */
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.4-12.8z%22%2F%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
}

/* Focus state matching your other inputs */
select:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Style for individual options inside the dropdown */
select option {
    padding: 10px;
    color: #334155;
}
            </style>
    </head>
    <body>

    <div class="registration-container">
        <div class="logo-container">
    <img src="image/logo.png" alt="Logo">
</div><br><br>

        <div>
        <center><h1>Student Registration Form</h1></center>
         <form action="save_student.php" method ="POST">

        <label>Registration Number:</label>
        <input type="text" name="registration_number" required><br><br>

        <label>Full Name:</label>
        <input type="text" name="full_name" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Department:</label>
        <input type="text" name="department" required><br><br>
        
        <label>Level:</label>
        <select name="level" required>
            <option value="">Select Level</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="300">300</option>
            <option value="400">400</option>
        </select><br><br>  
        
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

       <center> <button type="submit">Register Student</button></center>
       <center><p>Already have an account? <a href="login.php">Login here</a></p></center>
</form>
</div>
</body>
</html>