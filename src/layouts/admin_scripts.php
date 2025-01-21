<script>
function toggleForm() {
    const form = document.getElementById('createForm');
    const button = document.querySelector('.create-button .btn');
    
    if (form.style.display === 'none' || !form.style.display) {
        console.log('Form is hidden, showing it', button);
        form.style.display = 'block';
        button.innerHTML = '<i class="fas fa-times"></i> Cancel';
        button.style.backgroundColor = '#dc3545';
    } else {
        form.style.display = 'none';
        button.innerHTML = '<i class="fas fa-plus"></i> Create New';
        button.style.backgroundColor = '#5cb85c';
    }
}

// Reset form on cancel
function resetForm() {
    const form = document.getElementById('createForm').querySelector('form');
    form.reset();
}
</script> 