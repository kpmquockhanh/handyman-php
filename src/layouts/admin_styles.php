<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/logov3.jpeg">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/logov3.jpeg">
<style>
    :root {
        --primary-color: #4a90e2;
        --secondary-color: #5cb85c;
        --danger-color: #d9534f;
        --dark-color: #2c3e50;
        --light-color: #ecf0f1;
        --border-radius: 8px;
        --box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Rubik', sans-serif;
        background-color: #f8f9fa;
        color: var(--dark-color);
        line-height: 1.6;
        padding: 20px;
    }

    /* Headers */
    h1, h2 {
        color: var(--dark-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    h1 {
        font-size: 2.5rem;
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 0.5rem;
        margin-bottom: 2rem;
    }

    h2 {
        font-size: 1.8rem;
        color: #34495e;
    }

    /* Forms */
    form {
        background: white;
        /* padding: 2rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow); */
        margin-bottom: 2rem;
        /* border: 1px solid #e1e4e8; */
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #2c3e50;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select,
    textarea {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }

    /* Buttons */
    button,
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    button[type="submit"],
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    button[type="submit"]:hover,
    .btn-primary:hover {
        background-color: #357abd;
        transform: translateY(-1px);
    }

    .btn-danger,
    .logout {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover,
    .logout:hover {
        background-color: #c9302c;
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin: 1.5rem 0;
        overflow: hidden;
    }

    th, td {
        padding: 1rem;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: var(--dark-color);
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    /* Menu Navigation */
    .menu {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .menu a {
        background-color: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: var(--border-radius);
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .menu a:hover {
        background-color: #357abd;
        transform: translateY(-2px);
    }

    /* Messages */
    .success,
    .error {
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Actions Column */
    .actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-start;
        align-items: center;
    }

    .actions a {
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 80px;
        justify-content: center;
        background-color: #3498db;
        color: white;
    }

    .actions a:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Update edit button icon */
    /* .actions a::before {
        content: '\f044';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    } */

    /* Remove the specific first-child and last-child styles */
    .actions a.edit {
        background-color: #3498db;
        color: white;
    }

    .actions a.remove {
        background-color: #e74c3c;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .actions {
            flex-direction: row;
            gap: 0.5rem;
        }
        
        .actions a {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        body {
            padding: 10px;
        }

        .menu {
            flex-direction: column;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        .actions {
            flex-direction: column;
        }
    }

    /* Create Button Styles */
    .create-button {
        margin-bottom: 2rem;
    }

    .create-button .btn {
        background-color: var(--secondary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .create-button .btn:hover {
        background-color: #4cae4c;
        transform: translateY(-1px);
    }

    /* Form Buttons */
    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Hidden Form */
    #createForm {
        margin-bottom: 2rem;
        padding: 2rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: 1px solid #e1e4e8;
    }

    #createForm h2 {
        margin-top: 0;
    }

    /* File Input Styling */
    input[type="file"] {
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px dashed #ddd;
        border-radius: var(--border-radius);
        width: 100%;
    }

    input[type="file"]:hover {
        border-color: var(--primary-color);
    }
</style>