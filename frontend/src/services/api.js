
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
// export const loginUser = (user) => axios.post(`${API_URL}/login`, user);
// export const createUser = (user) => axios.post(`${API_URL}/register`, user);

// Course
export const getCourses = () => api.get('/courses');
export const updateCourse = (id, course) => api.put(`/courses/${id}`, course);
export const deleteCourse = (id) => api.delete(`/courses/${id}`);
export function createCourse(course) {
    return axios.post(`${API_URL}/registerCourse`, course);
}
// export const createCourse = (course) => axios.post(`${API_URL}/registerCourse`, course);

// Post
export const getPosts = () => api.get('/posts');
export const getPostById = (id) => api.get(`/posts/${id}`);
// export const updatePost = (id, post) => api.put(`/posts/${id}`, post);
export const updatePost = (id, post) => {
    return axios.post(`${API_URL}/posts/${id}`, post, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
};

export const deletePost = (id) => api.delete(`/posts/${id}`);

// export function createPost(post) {
//     return axios.post(`${API_URL}/createPost`, post, {
//         headers: {
//             'Content-Type': 'multipart/form-data',
//         },
//     });
// }

// export function updatePost(id, formData) {
//     return axios.put(`${API_URL}/posts/${id}`, formData);
// }

export function createPost(formData) {
    return axios.post(`${API_URL}/createPost`, formData)
        .then(response => {
            console.log('Create response:', response);
            return response.data;
        })
        .catch(error => {
            console.error('Create error:', error);
            throw error;
        });
}

// ContactUs
export const getAllForms = () => api.get('/contact');
export const getUserForms = (userId) => api.get(`/contact/user/${userId}`);
export const getContactById = (id) => api.get(`/contact/${id}`);
export const submitForm = (form) => api.post('/contact', form);
export const updateContact = (id, form) => api.put(`/contact/${id}`, form);
export const deleteForm = (id) => api.delete(`/contact/${id}`);