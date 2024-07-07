<template>
    <div class="post-list-container">
        <h1 class="title">{{ `Edit Post: ${originalTitle}` }}</h1>
        <div class="container">
            <div class="links">
                <a style="color: black">Links</a>
                <router-link :to="{ path: '/post' }"
                    :class="{ 'active-link': $route.path === '/post', 'inactive-link': $route.path !== '/post' }">All
                    Posts</router-link>
                <router-link :to="{ path: '/post/create' }"
                    :class="{ 'active-link': $route.path === '/post/create', 'inactive-link': $route.path !== '/post/create' }">New
                    Post</router-link>
            </div>
            <div class="card-container">
                <div class="card">
                    <div class="posts">
                        <form @submit.prevent="savePost" enctype="multipart/form-data">
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
                                <select v-model="form.category" name="category" id="category" class="form-control"
                                    required>
                                    <option value="">Select a category</option>
                                    <option value="food">Food</option>
                                    <option value="lifestyle">Lifestyle</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Thumbnail</label>
                                <input type="file" @change="onFileChange" class="form-control" />
                                <div v-if="thumbnailUrl">
                                    <img :src="thumbnailUrl" alt="Thumbnail" class="thumbnail" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getPostById, updatePost, createPost } from '@/services/api';

export default {
    data() {
        return {
            form: {
                id: null,
                title: '',
                excerpt: '',
                body: '',
                category: '',
                thumbnail: null
            },
            originalTitle: '',
            thumbnailUrl: '',
            formData: {
                title: '',
                excerpt: '',
                body: '',
                category: '',
                user_id: '',
            }
        };
    },
    methods: {
        fetchPost() {
            const postId = this.$route.params.id;
            if (postId) {
                getPostById(postId).then(response => {
                    this.form = response.data;
                    this.originalTitle = response.data.title;
                    this.thumbnailUrl = this.getThumbnailUrl(this.form.thumbnail);
                });
            }
        },
        getThumbnailUrl(filename) {
            return filename ? require(`@/assets/thumbnails/${filename}`) : '';
        },
        onFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.thumbnail = file;
                this.thumbnailUrl = URL.createObjectURL(file); // Display thumbnail preview
            }
        },
        savePost() {
            const user = JSON.parse(localStorage.getItem('loggedInUser'));
            const formData = new FormData();
            formData.append('title', this.form.title);
            formData.append('excerpt', this.form.excerpt);
            formData.append('body', this.form.body);
            formData.append('category', this.form.category);
            formData.append('user_id', user.id);
            if (this.form.thumbnail) {
                formData.append('thumbnail', this.form.thumbnail);
            }
            console.log('FormData content:', formData);
            console.log('FormData content:', this.form.thumbnail.name);

            for (let pair of formData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }

            if (this.form.id) {
                // console.log('checking', updatePost(this.form.id, formData));
                updatePost(this.form.id, formData)
                    .then(() => {
                        this.$router.go(-1);
                    })
                    .catch(error => {
                        console.error('Error updating post:', error);
                    });
            } else {
                createPost(formData)
                    .then(() => {
                        this.$router.go(-1);
                    })
                    .catch(error => {
                        console.error('Error creating post:', error);
                    });
            }
        }
    },
    created() {
        this.fetchPost();
    }
}
</script>

<style scoped>
.post-list-container {
    padding: 20px;
}

.title {
    display: inline-block;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    margin-left: 25%;
    width: 50%;
}

.card-container {
    margin: 10px auto;
    padding: 50px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card {
    margin: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.container {
    display: flex;
}

.links {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.links a {
    padding-left: 200px;
    text-decoration: none;
    /* color: blue; */
}

.posts form {
    padding: 10px;
}

.post-card {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin: 5px;
    padding-left: 20px;
    /* width: 10px; */
}

.post-item {
    display: flex;
    /* justify-content: space-between; */
    /* align-items: center; */
    padding-left: 10px;
}

.actions button {
    /* margin-left: 10px; */
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    margin-left: 100px;
    width: 100px;
}

.actions button:hover {
    text-decoration: underline;
}

.post-card:last-child {
    border-bottom: none;
}

.post-item .editButton {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    margin-left: 280px;
    width: 100px;
}

.post-item .deleteButton {
    background-color: transparent;
    border: none;
    color: grey;
    cursor: pointer;
    margin-left: 50px;
    /* width: 100px; */
}

.form-control {
    width: 511px;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-success {
    background-color: #5cb85c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: #4cae4c;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.active-link {
    color: blue;
}

.inactive-link {
    color: black;
}

.thumbnail {
    max-width: 100%;
    height: auto;
    margin-top: 10px;
    border-radius: 4px;
}
</style>