<footer class="admin-footer">
    <div class="footer-buttons">
        <a class="btn back-btn" href="/admin">
            <i class="fas fa-home"></i> Admin Home
        </a>
        <div class="button-group">
            <a class="btn" href="javascript:history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a class="btn logout" href="/auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</footer>

<style>
.admin-footer {
    margin-top: 40px;
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    position: sticky;
    bottom: 0;
}

.footer-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    max-width: 1200px;
    margin: 0 auto;
}

.button-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.back-btn {
    background-color: #4CAF50;
    color: white;
}

.back-btn:hover {
    background-color: #45a049;
}

.btn i {
    font-size: 1.1em;
}

.logout {
    background-color: #dc3545;
    color: white;
}

.logout:hover {
    background-color: #c82333;
}

@media (max-width: 576px) {
    .footer-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .btn {
        text-align: center;
        justify-content: center;
    }
}
</style>