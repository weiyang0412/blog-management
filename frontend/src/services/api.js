
import axios from 'axios';

const API_URL = 'http://localhost:8080';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});


// User
export const getUsers = () => api.get('/users');
export const getUserById = (id) => api.get(`/users/${id}`);
export const updateUser = (id, user) => api.put(`/users/${id}`, user);
export const deleteUser = (id) => api.delete(`/users/${id}`);
export const loginUser = (user) => {
    return axios.post(`${API_URL}/login`, user);
};
export function createUser(user) {
    return axios.post(`${API_URL}/register`, user);
}

// Course
export const getCourses = () => api.get('/courses');
export const updateCourse = (id, course) => api.put(`/courses/${id}`, course);
export const deleteCourse = (id) => api.delete(`/courses/${id}`);
export function createCourse(course) {
    return axios.post(`${API_URL}/registerCourse`, course);
}

// Post
export const getPosts = () => api.get('/posts');
export const getPostById = (id) => api.get(`/posts/${id}`);
export const updatePost = (id, post) => api.put(`/posts/${id}`, post);
export const deletePost = (id) => api.delete(`/posts/${id}`);
export function createPost(post) {
    return axios.post(`${API_URL}/createPost`, post);
}
