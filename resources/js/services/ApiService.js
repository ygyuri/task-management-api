import axios from 'axios';

// Create an Axios instance with default settings
const ApiService = axios.create({
    baseURL: '/api',  // Laravel backend API base URL
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

// Request Interceptor (Executes before every API call)
ApiService.interceptors.request.use(
    (config) => {
        console.log(`[API REQUEST] ${config.method.toUpperCase()} → ${config.url}`);
        return config;
    },
    (error) => {
        console.error("[API REQUEST ERROR]", error);
        return Promise.reject(error);
    }
);

// Response Interceptor (Handles API responses)
ApiService.interceptors.response.use(
    (response) => {
        console.log("[API RESPONSE SUCCESS]", response);
        return response.data;  // Extracting data for easier use
    },
    (error) => {
        console.error("[API RESPONSE ERROR]", error.response || error);
        return Promise.reject(error);
    }
);

export default ApiService;
