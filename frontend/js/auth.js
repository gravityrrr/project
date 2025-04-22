// Check if user is logged in
function isAuthenticated() {
  return localStorage.getItem('token') !== null;
}

// Redirect to login if not authenticated
function checkAuth() {
  if (!isAuthenticated() && !window.location.href.includes('login.html')) {
    window.location.href = 'login.html';
    return false;
  }
  
  if (isAuthenticated() && window.location.href.includes('login.html')) {
    window.location.href = 'dashboard.html';
    return false;
  }
  
  return true;
}

// Handle logout
function setupLogout() {
  const logoutBtn = document.getElementById('logoutBtn');
  
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      localStorage.removeItem('token');
      window.location.href = 'login.html';
    });
  }
}

// Get authorization headers for API requests
function getAuthHeaders() {
  const token = localStorage.getItem('token');
  return {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  };
}

// Make authenticated API request
async function apiRequest(url, method = 'GET', data = null) {
  try {
    const options = {
      method,
      headers: getAuthHeaders()
    };
    
    if (data && (method === 'POST' || method === 'PUT')) {
      options.body = JSON.stringify(data);
    }
    
    const response = await fetch(url, options);
    
    // Handle unauthorized
    if (response.status === 401) {
      localStorage.removeItem('token');
      window.location.href = 'login.html';
      return null;
    }
    
    return await response.json();
  } catch (error) {
    console.error('API request error:', error);
    return null;
  }
}

// Initialize auth on page load
document.addEventListener('DOMContentLoaded', () => {
  checkAuth();
  setupLogout();
});