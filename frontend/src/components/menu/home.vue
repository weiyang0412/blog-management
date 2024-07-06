<template>
    <div class="home">
        <div class="buttons">
            <button class="category-button">Categories</button>
            <button class="find-button">Find something</button>
        </div>
        <div class="latest-article" v-if="articles.length">
            <img :src="getThumbnailUrl(articles[0].thumbnail)" alt="Latest Article Image"
                class="latest-article-image" />
            <div class="latest-article-content">
                <span class="label">PROVIDENT</span>
                <h2 class="article-title">{{ articles[0].title }}</h2>
                <p class="article-date">{{ formatDate(articles[0].created_at) }}</p>
                <p class="latest-article-description">{{ articles[0].excerpt }}</p>
                <div class="latest-article-footer">
                    <div class="author">
                        <img :src="getRandomAuthorImage()" alt="Author" class="author-image" />
                        <span>{{ articles[0].author }}</span>
                    </div>
                    <button class="read-more">Read More</button>
                </div>
            </div>
        </div>
        <div class="articles">
            <div class="article" v-for="(article) in articles.slice(1)" :key="article.id">
                <img :src="getThumbnailUrl(article.thumbnail)" alt="Article Image" class="article-image" />
                <div class="article-content">
                    <span class="label">QUOD</span>
                    <h2 class="article-title">{{ article.title }}</h2>
                    <p class="article-date">{{ formatDate(article.created_at) }}</p>
                    <p class="article-description">{{ article.excerpt }}</p>
                    <div class="article-footer">
                        <div class="author">
                            <img :src="getRandomAuthorImage()" alt="Author" class="author-image" />
                            <span>{{ article.author }}</span>
                        </div>
                        <button class="read-more">Read More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getPosts } from '@/services/api'; // 假设你有一个 API 模块用于获取文章数据

export default {
    name: 'HomePage',
    data() {
        return {
            articles: []
        };
    },
    methods: {
        getRandomAuthorImage() {
            const randomId = Math.floor(Math.random() * 1000);
            return `https://picsum.photos/seed/${randomId}/50/50`;
        },
        getThumbnailUrl(filename) {
            return require(`@/assets/thumbnails/${filename}`);
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        fetchArticles() {
            getPosts().then(response => {
                this.articles = response.data;
            }).catch(error => {
                console.error(error);
            });
        }
    },
    created() {
        this.fetchArticles();
    }
};
</script>

<style>
.home {
    text-align: center;
    padding: 20px;
}

.header h1 {
    font-size: 2.5rem;
    color: #000;
}

.header span {
    color: #007bff;
}

.buttons {
    margin: 20px 0;
}

.category-button,
.find-button {
    background: #f0f0f0;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
}

.latest-article {
    display: flex;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
    overflow: hidden;
    width: 90%;
    margin: auto;
}

.latest-article-image {
    width: 50%;
    height: auto;
    object-fit: cover;
}

.latest-article-content {
    padding: 15px;
    text-align: left;
    width: 50%;
}

.label {
    background: #e0e0e0;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.75rem;
}

.article-title {
    font-size: 1.25rem;
    margin: 10px 0;
}

.article-date,
.article-description {
    margin: 5 px 0;
}

.author {
    display: flex;
    align-items: center;
}

.author-image {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.read-more {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 0.8rem;
    cursor: pointer;
    border-radius: 10px;
}

.articles {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.article {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
    overflow: hidden;
    width: 304px;
}

.article-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.article-content {
    padding: 15px;
    text-align: left;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 10px;
}

.latest-article-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 280px;
}

.latest-article-description {
    margin: 100 px 10;
}
</style>