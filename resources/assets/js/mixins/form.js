import _ from 'lodash';
import moment from 'moment';

export default {
    computed: {
        isCreate() {
            return this.$route.name !== 'edit';
        },
    },
    methods: {
        removeNullValues(object) {
            _.each(object, (value, key) => {
                if (value === null
                    || value === undefined
                    || (value.length && value.length === 0)) {
                    delete object[key];
                }
            });

            return object;
        },
        transformData(fields, values) {
            return _.transform(fields, (result, field, name) => {
                result[name] = this.getValue(field, values[name] || null);
            }, {});
        },
        /**
         * For submit
         *
         * @param values
         * @param fields
         */
        getFormValues(values, fields) {
            return _.transform(values, (result, value, key) => {
                if (fields[key].type === 'datetime') {
                    result[key] = moment(value).format('YYYY-MM-DD HH:mm:ss');
                } else {
                    result[key] = value;
                }
            }, {})
        },
        getValue(field, value) {
            let defaultValue = null;

            if (this.isCreate) {
                if (type === 'datetime') {
                    return new Date();
                }
            }

            if (field.isRelation) {
                if (! value || value.length === 0) {
                    return [];
                }

                if (field.type === 'hasMany') {
                    return _.map(value, 'value');
                } else {
                    // [{label: "Carissa Kessler", value: 3}]
                    return value[0].value;
                }
            }

            if (field.type === 'checkbox') {
                defaultValue = false;

                if (value === 1) {
                    value = true;
                }
            }

            if (field.type === 'file' || field.type === 'image') {
                defaultValue = [];

                if (value) {
                    value = _.transform(value.split(','), (result, value, key) => {
                        result.push({
                            name: value,
                            url: value,
                        })
                    }, []);
                }
            }

            return value || defaultValue;
        },
    }
};