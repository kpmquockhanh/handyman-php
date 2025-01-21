<style>
    .edit-form {
        max-width: 800px;
        margin: 20px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input[type="file"] {
        padding: 10px 0;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #4a90e2;
        outline: none;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .preview-image {
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .preview-image img {
        max-width: 200px;
        height: auto;
        border-radius: 4px;
    }

    .button-group {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .submit-button {
        background: #4CAF50;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .submit-button:hover {
        background: #45a049;
    }

    .cancel-button {
        background: #6c757d;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .cancel-button:hover {
        background: #5a6268;
    }

    .page-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .page-header h1 {
        margin: 0;
        color: #333;
        font-size: 24px;
    }
</style> 