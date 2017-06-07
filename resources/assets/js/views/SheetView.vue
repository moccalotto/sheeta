<template>
    <div v-if="!!sheet" class="menu-parent">
        <h1 class="title is-spaced">
            <template v-if="sheet.visible_headline">
                {{ sheet.headline }}
                <router-link :to="{ name: 'editSheet', params: { id: $route.params.id }}">
                    <span class="icon">
                        <i class="fa fa-pencil"></i>
                    </span>
                </router-link>
            </template>
        </h1>

        <div class="columns is-multiline">

            <div v-for="table, tableIdx in sheet.tables" :class="classForTableColumn(table)">
                <table-view
                    @cell-changed="cellChanged(tableIdx, $event)"
                    :editable="sheet.editable_by_current_user"
                    :table="table"></table-view>
            </div>
        </div>
    </div>
    <pulse-loader v-else></pulse-loader>

</template>

<script>
    import TableView from '../components/TableView';

    export default {
        props: ['id', 'slug'],
        components: { TableView },
        data() {
            return {
                add: { },
                sheet: null,
                cellChanged: null,
                loading: true,
            };
        },
        watch: {
            '$route': 'fetch',
        },
        created() {
            this.fetch();
            window.eventBus.$on('auth.login', () => { this.fetch() });
            window.eventBus.$on('auth.logout', () => { this.fetch() });

            this.cellChanged = _.debounce( (tableIdx, { value, rowIdx, colIdx } ) => {
                const col = this.sheet.tables[tableIdx].columns[colIdx];

                if (col.format == 'number') {
                    value = parseInt(value);
                }

                if (col.max_value !== undefined && value > col.max_value) {
                    value = col.max_value;
                }

                if (col.min_value !== undefined && value < col.min_value) {
                    value = col.min_value;
                }

                const patchData = {
                    version: this.sheet.version,
                    path: ['tables', tableIdx, 'rows', rowIdx, colIdx],
                    value: value,
                };

                axios.patch(`/api/sheets/${this.id}`, patchData).then( ( {data} ) => {
                    this.sheet.version = data.version;
                    this.sheet.tables[tableIdx].rows[rowIdx][colIdx] = value;
                }).catch ( (error) => {
                    switch (error.response.status) {
                        case 401:
                            flash('danger', 'You need to be signed in to see this sheet.', 3000);
                            break;
                        case 403:
                            flash('danger', 'You are not allowed to edit this sheet!', 3000);
                            break;
                        default:
                            flash('danger', `You cannot edit this sheet: ${error.message}`, 3000);
                            break;
                    }
                });
            }, 500);
        },
        methods: {
            classForTableColumn(table) {
                if (table.width || false) {
                    return ['column', `is-${table.width}`];
                }
                return ['column'];

            },
            fetch() {
                this.loading = true;
                axios.get('/api/sheets/' + this.id).then( ({data}) => {
                    this.sheet = data;
                    this.loading = false;
                }).catch ( (error) => {
                    switch (error.response.status) {
                        case 401:
                            flash('danger', 'You need to be signed in to see this sheet.', 3000);
                            break;
                        case 403:
                            flash('danger', 'You are not allowed to see this sheet!', 3000);
                            break;
                        default:
                            flash('danger', `You cannot see this sheet: ${error.message}`, 3000);
                            break;
                    }
                });
            }
        },
    }
</script>
