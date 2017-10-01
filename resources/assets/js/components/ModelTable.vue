<template>
    <div>
        <template v-if="values.length">
            <filters :fields="fields" @filter="onFilter"></filters>

            <el-table v-loading.body="isLoading"
                      class="table table-responsive"
                      :data="values"
                      @sort-change="onSortChange"
                      border
                      :default-sort="tableSorting">

                <el-table-column v-for="field, name in fields"
                                 :key="name"
                                 v-if="! field.isRelation"
                                 :prop="name"
                                 :label="field.title"
                                 sortable="custom">
                </el-table-column>

                <el-table-column v-for="field, name in fields"
                                 :key="name"
                                 v-if="field.isRelation"
                                 :prop="name"
                                 :label="field.title"
                                 sortable="custom">
                    <template scope="scope">
                        <div v-if="scope.row[name].length === 0">
                            <i class="el-icon-minus"></i>
                        </div>
                        <el-tag v-else
                                v-for="label, value in scope.row[name]" :key="value">
                            {{ label }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="Operations"
                                 :fixed="Object.keys(fields).length > 5 ? 'right' : false" width="150px">
                    <template scope="scope">
                        <div class="text-center">
                            <el-button
                                    size="small"
                                    @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
                            <el-button
                                    size="small"
                                    type="danger"
                                    @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="text-right">
                <el-pagination
                        @current-change="handlePageChange"
                        :current-page.sync="pagination.currentPage"
                        :page-size="pagination.perPage"
                        layout="prev, pager, next"
                        :total="pagination.total">
                </el-pagination>
            </div>
        </template>

        <template v-else-if="isLoading"
                  v-loading.body="isLoading"
                  element-loading-text="Loading..."
                  style="width: 100%; height: 50vh;">
        </template>

        <template v-else-if="isFiltering">
            <filters :fields="fields" @filter="onFilter"></filters>

            <h2>
                No results found for the filters provided. :[
            </h2>
        </template>
        <template v-else>
            <h2>
                No results found. :[
            </h2>
        </template>
    </div>
</template>

<script>
    import _ from 'lodash';
    import { getIcon, apiRoute } from "../utils";
    import loaderMixin from "../mixins/loader";
    import Filters from "./Filters";

    export default {
        data() {
            return {
                fields: [],
                values: [],
                pagination: {
                    currentPage: null,
                    perPage: null,
                    total: null
                },
                sortBy: {
                    column: null,
                    direction: null
                }
            }
        },
        mixins: [loaderMixin],
        components: {
            Filters
        },
        beforeMount() {
            this.fetchData();
        },
        watch: {
            $route() {
                this.fetchData();
            }
        },
        computed: {
            tableSorting() {
                return {
                    prop: this.sortBy.column,
                    order: (this.sortBy.direction === 'asc') ? 'ascending' : 'descending'
                }
            },
            isFiltering() {
                return this.$route.query && this.$route.query.filters !== null;
            }
        },
        methods: {
            pushQuery(query) {
                this.$router.push({
                    query: Object.assign({}, this.$route.query, query)
                });
            },
            syncUrl() {
                if (this.$route.query && this.$route.query.page) {
                    this.pagination.currentPage = parseInt(this.$route.query.page, 10);
                } else {
                    this.pagination.currentPage = 1;
                }

                if (this.$route.query && this.$route.query.perPage) {
                    this.pagination.perPage = parseInt(this.$route.query.perPage, 10);
                } else {
                    this.pagination.perPage = 25;
                }

                if (this.$route.query && this.$route.query.sortBy) {
                    this.sortBy.column = this.$route.query.sortBy;
                } else {
                    this.sortBy.column = 'id';
                }

                if (this.$route.query && this.$route.query.sortByDirection) {
                    this.sortBy.direction = this.$route.query.sortByDirection;
                } else {
                    this.sortBy.column = 'asc';
                }
            },
            onFilter(filters) {
                filters = _.transform(filters, (result, filter, key) => {
                    result.push(JSON.stringify({
                        key,
                        value: filter,
                    }))
                }, []);

                this.$router.push({
                    query: {
                        filters: filters
                    }
                })
            },
            onSortChange({ column, prop, order }) {
                if (prop) {
                    this.pushQuery({
                        sortBy: prop,
                        sortByDirection: (order === 'ascending') ? 'asc' : 'desc'
                    });
                } else {
                    this.pushQuery({
                        sortBy: undefined,
                        sortByDirection: undefined
                    });
                }
            },

            handlePageChange(value) {
                this.pushQuery({ page: value });
            },
            transformData(data) {
                return [].concat(
                    _.transform(data, (result, row) => {
                        const values = {};

                        _.each(row, (value, key) => {
                            // Show relations
                            if (this.fields[key].isRelation) {
                                values[key] = _.map(value, 'label');
                            } else {
                                values[key] = value;
                            }
                        });

                        result.push(values);
                    }, [])
                );
            },
            fetchData() {
                this.syncUrl();
                this.showLoader();

                axios.get(`/admin/api/model/${this.$route.params.model}`, {
                    params: {
                        page: this.pagination.currentPage,
                        perPage: this.pagination.perPage,
                        sortBy: this.sortBy.column,
                        sortByDirection: this.sortBy.direction,
                        filters: this.$route.query && this.$route.query.filters ? this.$route.query.filters : undefined
                    }
                })
                    .then(response => {
                        this.fields = response.data.fields;
                        this.pagination.total = response.data.pagination.total;
                        this.values = this.transformData(response.data.values);

                        this.hideLoader();
                    })
                    .catch(response => {
                        this.$message({
                            message: 'Disallowed action',
                            type: 'error'
                        });
                    })
            },
            getIcon,
            handleEdit(index, row) {
                this.$router.push({
                    name: 'edit',
                    params: {
                        model: this.$route.params.model,
                        id: row.id,
                    }
                });
            },
            handleDelete(index, row) {
                axios['delete'](apiRoute('destroy', {
                    model: this.$route.params.model,
                    id: row.id,
                }))
                    .then(response => {
                        this.$message({
                            message: 'Deleted.',
                            type: 'success'
                        });

                        this.values.splice(index, 1);
                    })
                    .catch(error => {
                        console.log(error);

                        this.$message({
                            message: 'Something went wrong.',
                            type: 'error'
                        });
                    });
            },
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
</style>
