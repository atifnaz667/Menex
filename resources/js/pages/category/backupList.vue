<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-1">
            <span class="text-muted fw-light">Categories/</span>
            List
        </h4>
        <!-- Sticky Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-top: 3px solid #7367f0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-label" for="board_id">Category Name</label>
                                <input type="text" name="category" id="category" class="form-control"
                                    placeholder="Enter Category Name" />
                            </div>

                            <div class="col-3 col-sm-1">
                                <label class="form-label" for="board_id">&nbsp;</label>
                                <button type="button" class="btn btn-primary waves-effect waves-light" id="filterButton">
                                    Filter
                                </button>
                            </div>
                            <div class="col-3 col-sm-1">
                                <label class="form-label" for="board_id">&nbsp;&nbsp;</label>
                                <button type="button" class="btn btn-danger waves-effect waves-light ml-3"
                                    id="filterButton">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr#</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr v-for="(category, index) in categories" :key="category.id">
                                    <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                    <td>{{ category.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation" class="pagination-nav">
                        <ul class="pagination d-flex justify-content-end p-3" style="cursor: pointer;">
                            <!-- Show "Previous" button -->
                            <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                <a class="page-link" @click="prevPage" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Show numbered page buttons -->
                            <li class="page-item" :class="{ 'active': page === currentPage }" v-for="page in displayedPages"
                                :key="page">
                                <a class="page-link" @click="setCurrentPage(page)">{{ page }}</a>
                            </li>

                            <!-- Show "Next" button -->
                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                <a class="page-link" @click="nextPage" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";


export default {
    data() {
        return {
            categories: [],
            currentPage: 1,
            perPage: 10,
            totalItems: 0,
        };
    },
    computed: {
        displayedCategories() {
            // Calculate the displayed categories based on currentPage and perPage
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.categories.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.totalItems / this.perPage);
        },
        displayedPages() {
            const maxDisplayedPages = 5; // Adjust this to limit the number of displayed page buttons
            const halfMaxPages = Math.floor(maxDisplayedPages / 2);
            let startPage = Math.max(this.currentPage - halfMaxPages, 1);
            const endPage = Math.min(startPage + maxDisplayedPages - 1, this.totalPages);

            // Adjust startPage if it's less than 1
            if (endPage - startPage < maxDisplayedPages - 1) {
                startPage = Math.max(endPage - maxDisplayedPages + 1, 1);
            }

            return Array.from({ length: endPage - startPage + 1 }, (_, index) => startPage + index);
        },
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            axios
                .get(`categories?page=${this.currentPage}&perPage=${this.perPage}`)
                .then((response) => {
                    this.categories = response.data.categories.data;
                    this.totalItems = response.data.categories.total;
                })
                .catch((error) => {
                    console.error('Error fetching categories:', error);
                });
        },
        setCurrentPage(page) {
            this.currentPage = page;
            this.fetchCategories();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.setCurrentPage(this.currentPage - 1);
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.setCurrentPage(this.currentPage + 1);
            }
        },
    },
};
</script>
