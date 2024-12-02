import apiClient from './apiClient';

async function loginUser(email, password) {
  try {
    // Step 1: Get CSRF cookie first
    await apiClient.get('/sanctum/csrf-cookie'); // This sets the CSRF token in the cookie

    // Step 2: Send login request with credentials
    const response = await apiClient.post('/api/login', {
      email: email,
      password: password,
    });

    // Step 3: Handle the response from the API
    console.log('User Logged In:', response.data); // This will log the user data
    // You can store the token or user data here, for example:
    localStorage.setItem('user', JSON.stringify(response.data)); // Save user data or token in local storage
  } catch (error) {
    console.error('Login failed:', error);
    // Handle error, e.g., display a message to the user
  }
}
