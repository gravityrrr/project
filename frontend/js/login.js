document.addEventListener('DOMContentLoaded', () => {
  checkAuth(); // From auth.js
  
  const loginForm = document.getElementById('loginForm');
  const loginError = document.getElementById('loginError');
  
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      // Clear previous errors
      loginError.textContent = '';
      
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      
      try {
        const response = await fetch('/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ username, password })
        });
        
        const data = await response.json();
        
        if (data.success && data.token) {
          // Save token and redirect
          localStorage.setItem('token', data.token);
          window.location.href = '/dashboard.html';
        } else {
          // Show error message
          loginError.textContent = data.message || 'Invalid username or password';
        }
      } catch (error) {
        console.error('Login error:', error);
        loginError.textContent = 'An error occurred. Please try again.';
      }
    });
  }
});