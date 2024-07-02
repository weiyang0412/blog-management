<template>
    <div class="container">
        <h1 class="my-4">Course List</h1>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="course in courses" :key="course.id">
                    <td>{{ course.course_code }}</td>
                    <td>{{ course.course_name }}</td>
                    <td>{{ course.credit }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2" @click="editCourse(course.id)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="deleteCourse(course.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <form @submit.prevent="saveCourse" class="mt-4">
            <div class="mb-3">
                <input v-model="form.course_code" class="form-control" placeholder="Course Code" required />
            </div>
            <div class="mb-3">
                <input v-model="form.course_name" class="form-control" placeholder="Course Name" required />
            </div>
            <div class="mb-3">
                <input v-model="form.credit" class="form-control" placeholder="Credit" required />
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</template>

<script>
import { getCourses, updateCourse, deleteCourse, createCourse } from '@/services/api';

export default {
    data() {
        return {
            courses: [],
            form: {
                id: null,
                course_code: '',
                course_name: '',
                credit: ''
            }
        };
    },
    methods: {
        fetchCourses() {
            getCourses().then(response => {
                this.courses = response.data;
            });
        },
        saveCourse() {
            if (this.form.id) {
                updateCourse(this.form.id, this.form).then(() => {
                    this.fetchCourses();
                    this.resetForm();
                });
            }  else {
                createCourse(this.form).then(() => {
                    this.fetchCourses();
                    this.resetForm();
                });
            }
        },
        editCourse(id) {
            const course = this.courses.find(course => course.id === id);
            this.form = { ...course };
        },
        deleteCourse(id) {
            deleteCourse(id).then(() => {
                this.fetchCourses();
            });
        },
        resetForm() {
            this.form = {
                id: null,
                course_code: '',
                course_name: '',
                credit: ''
            };
        }
    },
    created() {
        this.fetchCourses();
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