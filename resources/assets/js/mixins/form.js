import _ from 'lodash';

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
                result[name] = this.getValue(name, field.type, values[name] || null);
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
        getValue(name, type, value) {
            let defaultValue = null;

            if (this.isCreate) {
                if (type === 'datetime') {
                    return new Date();
                }
            }

            if (type === 'hasMany') {

                if (! value || value.length === 0) {
                    return [];
                }

                return _.map(value, 'value');
            }

            if (type === 'checkbox') {
                defaultValue = false;
            }

            return value || defaultValue;
        },
    }
};