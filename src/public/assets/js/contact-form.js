document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  
  if (form) {
      form.addEventListener('submit', function(e) {
          e.preventDefault();
          // Clear previous error messages
          clearErrors();
          
          // Validate form
          if (validateForm()) {
              submitForm();
          }
      });
  }
  
  function validateForm() {
      let isValid = true;
      
      // Validate name
      const name = document.getElementById('formName');
      if (name.value.length < 2 || name.value.length > 100) {
          showError('messageName', 'Name must be between 2 and 100 characters');
          isValid = false;
      }
      
      // Validate email
      const email = document.getElementById('formEmail');
      if (!isValidEmail(email.value)) {
          showError('messageEmail', 'Please enter a valid email address');
          isValid = false;
      }
      
      // Validate message
      const message = document.getElementById('formMessage');
      if (message.value.length < 10) {
          showError('messageMessage', 'Message must be at least 10 characters long');
          isValid = false;
      }
      
      return isValid;
  }
  
  function isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
  }
  
  function showError(elementId, message) {
      const errorDiv = document.getElementById(elementId);
      if (errorDiv) {
          errorDiv.textContent = message;
          errorDiv.style.display = 'block';
      }
  }
  
  function clearErrors() {
      const errorDivs = document.querySelectorAll('.error-message');
      errorDivs.forEach(div => {
          div.textContent = '';
          div.style.display = 'none';
      });
  }
  
  function submitForm() {
      const formData = new FormData(form);
      
      fetch('/process-message.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Show success message
              form.reset();
              alert(data.message);
          } else {
              // Show error messages
              data.errors.forEach(error => {
                  alert(error);
              });
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('An error occurred. Please try again later.');
      });
  }
});