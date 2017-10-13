<template>
    <form class="Filters" @submit.prevent="submit">
        <div class="box" v-loading.body="isLoading">
            <fields :fields="filterableFields" :values.sync="values"></fields>

            <div class="Filters__submit text-right">
                <el-button class="btn btn-primary"
                           type="primary"
                           :native-type="'submit'"
                           :loading="isLoading">
                    <i :class="getIcon('filter')"></i> Filter
                </el-button>
            </div>
        </div>
    </form>
</template>

<script>
    import _ from 'lodash';
    import moment from 'moment';
    import { getIcon } from "../utils";
    import loaderMixin from "../mixins/loader";
    import formMixin from "../mixins/form";
    import Fields from "./Fields";

    export default {
        data() {
            return {
                loading: true,
                values: null,
            }
        },
        props: ['fields'],
        components: {
            Fields
        },
        mixins: [loaderMixin, formMixin],
        beforeMount() {
            this.values = this.transformData(this.fields, {});

            this.hideLoader();
        },
        computed: {
            filterableFields() {
                const filterableFields = {};

                _.each(this.fields, (field, name) => {
                    if (! field.hasFilter) {
                        return;
                    }

                    filterableFields[name] = field;
                });

                return filterableFields;
            },
            valuesNullRemoved() {
                return this.removeNullValues(_.cloneDeep(this.values));
            }
        },
        methods: {
            getIcon,
            submit() {
                this.$emit('filter', this.getFormValues(this.valuesNullRemoved, this.fields));
            }
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
    .Filters {
        &__submit {
            padding: 0 10px 10px;
        }
    }
</style>
