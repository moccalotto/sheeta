<template>
    <nav class="pagination">

        <a rel="prev" :disabled="onFirstPage" class="pagination-previous" @click.prevent="navigateRel(-1)">Previous</a>
        <a rel="next" :disabled="onLastPage"  class="pagination-next"     @click.prevent="navigateRel(1)">Next page</a>

        <ul v-if="hasManyPages" class="pagination-list">
            <li v-if="currentPage > 3">
                <a @click.prevent="navigate(1)" class="pagination-link">1</a>
            </li>
            <li v-if="currentPage > 3">
                <span class="pagination-ellipsis">&hellip;</span>
            </li>
            <li v-if="currentPage > 1">
                <a @click.prevent="navigateRel(-1)" class="pagination-link">{{ currentPage - 1 }}</a>
            </li>
            <li v-if="currentPage < lastPage">
                <a @click.prevent="" class="pagination-link is-active">{{ currentPage }}</a>
            </li>
            <li v-if="currentPage + 2 < lastPage">
                <a @click.prevent="navigateRel(1)" class="pagination-link">{{ currentPage + 1 }}</a>
            </li>
            <li v-if="currentPage < lastPage">
                <span class="pagination-ellipsis">&hellip;</span>
            </li>
            <li>
                <a @click.prevent="navigate(lastPage)" class="pagination-link">{{ lastPage }}</a>
            </li>
        </ul>
        <ul v-else>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['dataSet'],
        methods: {
            navigateRel(offset) {
                this.$emit('pageChanged', this.dataSet.current_page + offset);
            },
            navigate(newPage) {
                this.$emit('pageChanged', newPage);
            },
        },
        computed: {
            hasManyPages() {
                return this.dataSet.last_page > 7;
            },
            currentPage() {
                return this.dataSet.current_page;
            },
            lastPage() {
                return this.dataSet.last_page;
            },
            onFirstPage() {
                return this.dataSet.current_page == 1;
            },
            onLastPage() {
                return this.dataSet.current_page == this.dataSet.last_page;
            },
        },
    }
</script>
