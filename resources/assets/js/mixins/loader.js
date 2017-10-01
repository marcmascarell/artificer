export default {
    data() {
        return {
            loading: false
        }
    },
    computed: {
        isLoading() {
            return this.loading;
        }
    },
    methods: {
        showLoader() {
            this.loading = true;
        },
        hideLoader() {
            this.loading = false;
        }
    }
};