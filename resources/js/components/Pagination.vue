<template>
    <div class="row">
        <div class="col-sm-6">
            <p class="px-4 pt-4">Showing {{ startIndex }} to {{ endIndex }} of {{ totalItems }} records</p>
        </div>
        <div class="col-sm-6">
            <nav aria-label="Page navigation">
                <ul class="pagination d-flex justify-content-end p-3" style="cursor: pointer;">
                    <!-- Show "Previous" button -->
                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                        <a class="page-link" @click="prevPage" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Show numbered page buttons -->
                    <li class="page-item" :class="{ 'active': page === currentPage }" v-for="page in displayedPages" :key="page">
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
</template>

<script>
export default {
    props: {
        currentPage: Number,
        totalPages: Number,
        endIndex: Number,
        startIndex: Number,
        totalItems: Number,
    },
    computed: {
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
    methods: {
        setCurrentPage(page) {
            this.$emit('page-change', page);
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
