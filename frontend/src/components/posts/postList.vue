<template>
    <div class="container">
        <h1 class="my-4">Post List</h1>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Title</th>
                    <th>Excerpt</th>
                    <th>Body</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts" :key="post.id">
                    <td>{{ post.title }}</td>
                    <td>{{ post.excerpt }}</td>
                    <td>{{ post.body }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2" @click="editPost(post.id)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="deletePost(post.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <form @submit.prevent="savePost" class="mt-4">
            <div class="mb-3">
                <label>Title</label>
                <input v-model="form.title" class="form-control" placeholder="Title" required />
            </div>
            <div class="mb-3">
                <label>Excerpt</label>
                <input v-model="form.excerpt" class="form-control" placeholder="Excerpt" required />
            </div>
            <div class="mb-3">
                <label>Body</label>
                <input v-model="form.body" class="form-control" placeholder="Body" required />
            </div>
            <div class="mb-3">
                <label>Category</label>
                <select v-model="form.category" name="category" id="category" placeholder="category" class="form-control" required>
                    <option value="">Select a category</option>
                    <option value="food">Food</option>
                    <option value="lifestyle">Lifestyle</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</template>

<script>
import { getPosts, updatePost, deletePost, createPost } from '@/services/api';

export default {
    data() {
        return {
            posts: [],
            form: {
                id: null,
                title: '',
                excerpt: '',
                body: '',
                category: '',
            }
        };
    },
    methods: {
        fetchPosts() {
            getPosts().then(response => {
                this.posts = response.data;
            });
        },
        savePost() {
            if (this.form.id) {
                updatePost(this.form.id, this.form).then(() => {
                    this.fetchPosts();
                    this.resetForm();
                });
            }  else {
                const loggedInUser = localStorage.getItem('loggedInUser');
                const parsedUser = JSON.parse(loggedInUser);
                this.form.user_id = parsedUser.id;
                console.log('check user id', this.form.user_id)
                createPost(this.form).then(() => {
                    this.fetchPosts();
                    this.resetForm();
                });
            }
        },
        editPost(id) {
            const post = this.posts.find(post => post.id === id);
            this.form = { ...post };
        },
        deletePost(id) {
            deletePost(id).then(() => {
                this.fetchPosts();
            });
        },
        resetForm() {
            this.form = {
                id: null,
                title: '',
                excerpt: '',
                body: '',
                category: ''
            };
        }
    },
    created() {
        this.fetchPosts();
    }
};
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    border: 1px solid #ddd;
}

th {
    background-color: #f4f4f4;
}
</style>