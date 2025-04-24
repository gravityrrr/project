document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const errorElement = document.getElementById('loginError');

  try {
    const response = await fetch('http://localhost:3000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ username, password })
    });

    const data = await response.json();

    if (!response.ok) {
      errorElement.textContent = data.error || 'Login failed';
      return;
    }

    // Save token and redirect
    localStorage.setItem('token', data.token);
    window.location.href = 'dashboard.html';

  } catch (error) {
    errorElement.textContent = 'Network error. Try again.';
    console.error('Login error:', error);
  }
});