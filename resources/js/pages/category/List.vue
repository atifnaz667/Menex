
<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <loader v-if="loading" ></loader>
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
                                    placeholder="Enter Category Name" v-model="categoryName" />
                            </div>

                            <div class="col-3 col-sm-1">
                                <label class="form-label" for="board_id">&nbsp;</label>
                                <button type="button" @click="fetchRecords" class="btn btn-primary waves-effect waves-light" id="filterButton">
                                    Filter
                                </button>
                            </div>
                            <div class="col-3 col-sm-1">
                                <label class="form-label" for="board_id">&nbsp;&nbsp;</label>
                                <button type="button"  class="btn btn-danger waves-effect waves-light ml-3"
                                    id="filterButton" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row">
                        <div class="col-4 col-sm-2 col-md-1 m-1">
                            <select name="" id="" class="form-select" v-model="perPage" @change="changeRecords">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr#</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr v-for="(category, index) in categories" :key="category.id">
                                    <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                    <td>{{ category.name }}</td>
                                    <td>
                                        <a class="btn-icon edit-record" style="cursor:pointer;" @click="editRecord(category.id)"><i class="ti ti-edit"></i></a>
                                        <a class="btn-icon delete-question text-danger" style="cursor:pointer;" @click="deleteRecord(category.id)"><i class="ti ti-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <pagination :current-page="currentPage" :total-pages="totalPages" :start-index="startIndex" :end-index="endIndex" :total-items="totalItems" @page-change="setCurrentPage" ></pagination>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addRecordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                    <h3 class="mb-2">Add New Category</h3>
                    </div>
                    <form id="addNewCCForm" class="row g-3" onsubmit="return false">
                    <div class="col-12">
                        <label class="form-label w-100" for="modalAddCard">Category Name</label>
                        <div class="input-group input-group-merge">
                        <input class="form-control " type="text" placeholder="Enter Category Name" v-model="storeCategoryName" />
                        <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"><span class="card-type"></span></span>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1" @click="storeRecord">Submit</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRecordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                    <h3 class="mb-2">Update Category</h3>
                    </div>
                    <form id="addNewCCForm" class="row g-3" onsubmit="return false">
                    <div class="col-12">
                        <label class="form-label w-100" for="modalAddCard">Category Name</label>
                        <div class="input-group input-group-merge">
                            <input class="form-control " type="text" placeholder="Enter Category Name" v-model="updateCategoryName" />
                            <input class="form-control " type="hidden"  v-model="updateCategoryId" />
                        <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"><span class="card-type"></span></span>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1" @click="updateRecord">Update</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import Pagination from "../../components/Pagination.vue";
import Loader from "../../components/Loader.vue";

export default {
    data() {
        return {
            categories: [],
            currentPage: 1,
            perPage: 10,
            totalItems: 0,
            categoryName: '',
            storeCategoryName: '',
            updateCategoryName: '',
            updateCategoryId: '',
            startIndex:0,
            endIndex:0,
            loading : false
        };
    },
    components: {
    Pagination,
    Loader,
    },
    computed: {
        displayedRecords() {
            // Calculate the displayed categories based on currentPage and perPage
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.categories.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.totalItems / this.perPage);
        },
    },
    mounted() {
        this.fetchRecords();
    },
    methods: {
        fetchRecords() {
            this.loading = true;
            axios.get(`categories?page=${this.currentPage}&perPage=${this.perPage}&categoryName=${this.categoryName}`)
            .then((response) => {
                this.categories = response.data.categories.data;
                this.totalItems = response.data.categories.total;
                this.startIndex = (Number(this.currentPage) - 1) * Number(this.perPage) + 1;
                this.endIndex = Number(this.startIndex) + Number(this.perPage) - 1;
            })
            .catch((error) => {
                showToast("error", error.response.data.message);
            })
            .finally(() => {
                this.loading = false;
            });

        },
        setCurrentPage(page) {
            this.currentPage = page;
            this.fetchRecords();
        },
        storeRecord(){
            this.loading = true;
            axios.post(`add/category?name=${this.storeCategoryName}`)
            .then((response) => {
                this.storeCategoryName = '';
                var status = response.data.status;
                var message = response.data.message;
                showToast(status,message);
                $('#addRecordModal').modal('hide');
                this.fetchRecords();
            })
            .catch((error) => {
                showToast("error", error.response.data.message);
            })
            .finally(() => {
                this.loading = false;
            });
        },
        deleteRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.value) {
                this.loading = true;
                axios.delete(`delete/category/${id}`)
                .then((response) => {
                    var status = response.data.status;
                    var message = response.data.message;
                    showToast(status, message);
                this.fetchRecords();
                })
                .catch((error) => {
                    showToast("error", error.response.data.message);
                })
                    .finally(() => {
                    this.loading = false;
                });
                }
            });
        },
        changeRecords(){
            this.currentPage = 1;
            this.fetchRecords();
        },
        editRecord(id){
            axios.get(`edit/category/${id}`)
            .then((response) => {
                $('#editRecordModal').modal('show');
                this.updateCategoryId = response.data.category.id
                this.updateCategoryName = response.data.category.name
            })
            .catch((error) => {
                showToast("error", error.response.data.message);
            })
                .finally(() => {
                this.loading = false;
            });
        },
        updateRecord(){
            this.loading = true;
            axios.put(`update/category/${this.updateCategoryId}?name=${this.updateCategoryName}`)
            .then((response) => {
                this.storeCategoryName = '';
                var status = response.data.status;
                var message = response.data.message;
                showToast(status,message);
                $('#editRecordModal').modal('hide');
                this.fetchRecords();
            })
            .catch((error) => {
                showToast("error", error.response.data.message);
            })
            .finally(() => {
                this.loading = false;
            });
        }
    },
};
</script>
