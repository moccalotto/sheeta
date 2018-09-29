<template>
    <nav class="pagination">

        <a rel="prev" :disabled="onFirstPage" class="pagination-previous" @click.prevent="navigateRel(-1)">Previous</a>
        <a rel="next" :disabled="onLastPage"  class="pagination-next"     @click.prevent="navigateRel(1)">Next page</a>

        <ul v-if="listIs('short')" class="pagination-list">
            <li v-for="page in lastPage">
                <a @click.prevent="navigate(page)" :class="classFor(page)">{{ page }}</a>
            </li>
        </ul>

        <ul v-if="listIs('atBeginning')" class="pagination-list">
            <li v-for="page in 5">
                <a @click.prevent="navigate(page)" :class="classFor(page)">{{ page }}</a>
            </li>

            <li>
                <span class="pagination-ellipsis">&hellip;</span>
            </li>

            <li>
                <a @click.prevent="navigate(lastPage)" :class="classFor(lastPage)">{{ lastPage }}</a>
            </li>
        </ul>

        <ul v-if="listIs('atEnd')" class="pagination-list">
            <li>
                <a @click.prevent="navigate(1)" :class="classFor(1)">{{ 1 }}</a>
            </li>

            <li>
                <span class="pagination-ellipsis">&hellip;</span>
            </li>

            <li v-for="offset in 5">
                <a @click.prevent="navigate(lastPage - 5 + offset)" :class="classFor(lastPage - 5 + offset)">{{ lastPage - 5 + offset }}</a>
            </li>
        </ul>

        <ul v-if="listIs('inMiddle')" class="pagination-list">
            <li>
                <a @click.prevent="navigate(1)" :class="classFor(1)">{{ 1 }}</a>
            </li>

            <li>
                <span class="pagination-ellipsis">&hellip;</span>
            </li>

            <li v-for="offset in 3">
                <a @click.prevent="navigate(currentPage - 2 + offset)" :class="classFor(currentPage - 2 + offset)">{{ currentPage - 2 + offset }}</a>
            </li>

            <li>
                <span class="pagination-ellipsis">&hellip;</span>
            </li>

            <li>
                <a @click.prevent="navigate(lastPage)" :class="classFor(lastPage)">{{ lastPage }}</a>
            </li>
        </ul>

    </nav>
</template>

<script>
    export default {
        props: ['dataSet', 'loading'],
        methods: {
            navigateRel(offset) {
                this.navigate(this.dataSet.current_page + offset);
            },
            navigate(newPage) {
                if (this.loading) {
                    return false;
                }

                if (newPage < 1) {
                    return false;
                }

                if (newPage > this.dataSet.last_page) {
                    return false;
                }

                this.$emit('pageChanged', newPage);
            },
            classFor(page) {
                return {
                    'pagination-link': true,
                    'is-current': this.dataSet.current_page == page,
                    'is-loading': this.loading == page,
                    'button': this.loading == page,
                };
            },
            listIs(name) {
                if (this.dataSet.last_page < 8) {
                    return name === 'short';
                }

                if (this.dataSet.current_page < 5) {
                    return name === 'atBeginning';
                }

                if (this.dataSet.current_page > this.dataSet.last_page - 4) {
                    return name === 'atEnd';
                }

                return name === 'inMiddle';
            }
        },
        computed: {
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
