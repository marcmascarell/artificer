<template>
    <form id="AdminForm"
          class="AdminForm"
          :method="method"
          :action="action"
          @submit.prevent="submit"
          enctype="multipart/form-data">

        <input type="hidden" name="_method" :value="method">

        <div class="box" v-loading.body="isLoading">
            <fields :fields="fields" :values.sync="values"></fields>
        </div>

        <div class="text-right">
            <!--<button class="btn btn-primary" name="_save" type="submit" value="Desar">-->
                <!--<i :class="getIcon('save')"></i> Save-->
            <!--</button>-->

            <el-button class="btn btn-primary"
                       type="primary"
                       :native-type="'submit'"
                       :loading="isLoading">
                <i :class="getIcon('save')"></i> Save
            </el-button>
            <!--{{&#45;&#45;{{ Form::submit('Desar', array('class' => "btn btn-primary", "name" => "_save")) }}&#45;&#45;}}-->
            <!--{{&#45;&#45;{{ Form::submit('Desar i afegir-ne un de nou', array('class' => "btn btn-default", "name" => "_addanother")) }}&#45;&#45;}}-->
            <!--{{&#45;&#45;                    {{ Form::submit('Desar i continuar editant', array('class' => "btn btn-default", "name" => "_continue")) }}&#45;&#45;}}-->
        </div>
    </form>
</template>

<script>
    import _ from 'lodash';
    import moment from 'moment';
    import { getIcon, apiRoute } from "../utils";
    import loaderMixin from "../mixins/loader";
    import formMixin from "../mixins/form";
    import Fields from "./Fields";

    export default {
        data() {
            return {
                pickerWithShortcuts: {
                    shortcuts: [
                        {
                            text: 'Today',
                            onClick(picker) {
                                picker.$emit('pick', new Date());
                            }
                        },
                        {
                            text: 'Yesterday',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24);
                                picker.$emit('pick', date);
                            }
                        },
                        {
                            text: 'A week ago',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                                picker.$emit('pick', date);
                            }
                        }
                    ]
                },
                values: {},
                fields: {}
            }
        },
        mixins: [loaderMixin, formMixin],
        components: {
            Fields
        },
        beforeMount() {
            //this.$refs.modal.open()

            // Make fields observable
            this.fetchData();
        },
        computed: {
            action() {
                if (this.$route.name === 'edit') {
                    return apiRoute('update', {
                        model: this.$route.params.model,
                        id: this.$route.params.id,
                    });
                }

                return apiRoute('store', {
                    model: this.$route.params.model
                })
            },
            method() {
                return (this.$route.name === 'edit') ? 'put' : 'post';
            },
        },
        methods: {
            getIcon,
            fetchData() {
                this.showLoader();

                const route = this.isCreate ? `/admin/api/model/${this.$route.params.model}/create`
                                            : `/admin/api/model/${this.$route.params.model}/${this.$route.params.id}/edit`;

                axios.get(route)
                    .then(response => {
                        this.fields = response.data.fields;
                        this.values = this.transformData(response.data.fields, response.data.values);

                        this.hideLoader();
                    })
            },
            getItemType(item) {
                var TYPES = {
                        'undefined'        : 'undefined',
                        'number'           : 'number',
                        'boolean'          : 'boolean',
                        'string'           : 'string',
                        '[object Function]': 'function',
                        '[object RegExp]'  : 'regexp',
                        '[object Array]'   : 'array',
                        '[object Date]'    : 'date',
                        '[object Error]'   : 'error'
                    },
                    TOSTRING = Object.prototype.toString;

                return TYPES[typeof item] || TYPES[TOSTRING.call(item)] || (item ? 'object' : 'null');
            },
            submit() {
                this.showLoader();

                let form = new FormData(document.getElementById('AdminForm'));

                const values = this.getFormValues(this.values, this.fields);

                // FormData converts all to string (shame!), so we will send the types to backend for casting
                const types = {};

                _.each(values, (value, key) => {
                    if (this.fields[key].type !== 'image' || this.fields[key].type !== 'file') {
                        form.set(key, value);
                        types[key] = this.getItemType(value);
                    }
                });

                form.set('_types', JSON.stringify(types));

                for (var pair of form.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                /**
                 * Always use POST (FormData does not work properly with other than that)
                 * However, we will tell Laravel that its POST/PUT via _method form field
                 */
                axios['post'](this.action, form, {
                    'content-type': 'multipart/form-data'
                })
                    .then(response => {
                        this.hideLoader();

                        this.$message({
                            message: this.isCreate ? 'Created.' : 'Updated.',
                            type: 'success'
                        });
                    })
                    .catch(error => {
                        this.hideLoader();
                        console.log(error);

                        this.$message({
                            message: 'Something went wrong.',
                            type: 'error'
                        });
                    });
            }
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
</style>
