<template>
    <sweet-modal class="Modal" ref="modal">
        Yeah
        <slot name="content"></slot>
    </sweet-modal>
</template>

<script>
    import { SweetModal, SweetModalTab } from 'sweet-modal-vue'

    export default {
        mounted() {
            //this.$refs.modal.open()
        },
        components: {
            SweetModal,
            SweetModalTab
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
</style>
