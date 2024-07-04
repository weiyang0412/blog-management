<template>
    <div class="home">
        <!-- <header class="header">
            <h1>Latest <span>Laravel From Scratch</span> News</h1>
        </header> -->
        <div class="buttons">
            <button class="category-button">Categories</button>
            <button class="find-button">Find something</button>
        </div>
        <div class="latest-article">
            <img :src="articles[0].image" alt="Latest Article Image" class="latest-article-image" />
            <div class="latest-article-content">
                <span class="label">PROVIDENT</span>
                <h2 class="article-title">{{ articles[0].title }}</h2>
                <p class="article-date">{{ articles[0].date }}</p>
                <p class="latest-article-description">{{ articles[0].description }}</p>
                <div class="latest-article-footer">
                    <div class="author">
                        <img :src="articles[0].authorImage" alt="Author" class="author-image" />
                        <span>{{ articles[0].author }}</span>
                    </div>
                    <button class="read-more">Read More</button>
                </div>
            </div>
        </div>
        <div class="articles">
            <div class="article" v-for="(article) in articles.slice(1)" :key="article.id">
                <img :src="article.image" alt="Article Image" class="article-image" />
                <div class="article-content">
                    <span class="label">QUOD</span>
                    <h2 class="article-title">{{ article.title }}</h2>
                    <p class="article-date">{{ article.date }}</p>
                    <p class="article-description">{{ article.description }}</p>
                    <div class="article-footer">
                        <div class="author">
                            <img :src="article.authorImage" alt="Author" class="author-image" />
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
export default {
    name: 'HomePage',
    data() {
        return {
            articles: [
                {
                    id: 1,
                    image: require('@/assets/background.jpg'),
                    title: 'Web Techno',
                    date: 'Published 3 weeks ago',
                    description: 'Web Techno',
                    author: 'weiyang',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 2,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 2',
                    date: 'Published 2 weeks ago',
                    description: 'Description of post 2',
                    author: 'author2',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
                {
                    id: 3,
                    image: require('@/assets/background.jpg'),
                    title: 'Post 3',
                    date: 'Published 1 week ago',
                    description: 'Description of post 3',
                    author: 'author3',
                    authorImage: this.getRandomAuthorImage()
                },
            ]
        };
    },
    methods: {
        getRandomAuthorImage() {
            const randomId = Math.floor(Math.random() * 1000);
            return `https://picsum.photos/seed/${randomId}/50/50`;
        }
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