<template>
  <div class="post-list-container">
    <h1 class="title">All Posts</h1>
    <div class="card-container">
      <div class="card" v-for="post in posts" :key="post.id">
        <div class="card-header">
          <img :src="getThumbnailUrl(post.thumbnail)" alt="Thumbnail" class="thumbnail"/>
          <h2>{{ post.title }}</h2>
        </div>
        <div class="card-body">
          <p>{{ post.excerpt }}</p>
          <p>{{ post.body }}</p>
          <p>{{ post.category }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getPosts } from '@/services/api';

export default {
  name: 'TopNavbar',
  data() {
    return {
      posts: []
    };
  },
  methods: {
    getThumbnailUrl(filename) {
      return require(`@/assets/thumbnails/${filename}`);
    },
    fetchPosts() {
      getPosts().then(response => {
        this.posts = response.data;
      }).catch(error => {
        console.error(error);
      });
    }
  },
  created() {
    this.fetchPosts();
  }
}
</script>

<style>
.thumbnail {
  width: 100px;
  height: 100px;
  object-fit: cover;
}
</style>