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
                <table class="table">
                    <caption class="title is-3" v-show="table.visible_headline">
                        {{ table.headline }}
                    </caption>
                    <colgroup>
                        <col v-for="col in table.columns" :style="styleForCol(col)">
                    </colgroup>
                    <thead v-show="table.visible_headers">
                        <th v-for="col in table.columns">
                            {{ col.headline }}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="row,rowIdx in table.rows">
                            <view-cell
                            v-for="col,colIdx in row"
                            :key="colIdx"
                            :value="col"
                            :cell="table.columns[colIdx]"
                            :readonly="true"
                            @changed="cellChanged($event.target.value, tableIdx, rowIdx, colIdx)"
                            ></view-cell>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <pulse-loader v-else></pulse-loader>

</template>

<script>
    import ViewCell from '../components/ViewCell';

    export default {
        props: ['id', 'slug'],
        components: {
            'view-cell': ViewCell,
        },
        data() {
            return {
                sheet: null,
                cellChanged: null,
                loading: true,
                edit: {
                    event: null,
                    tableIdx: null,
                    rowIdx: null,
                    colIdx: null,
                }
            };
        },
        created() {
            this.fetch();
            this.cellChanged = _.debounce( (newValue, tableIdx, rowIdx, colIdx) => {
                const col = this.sheet.tables[tableIdx].columns[colIdx];

                if (col.format == 'number') {
                    newValue = parseInt(newValue);
                }

                if (col.max_value !== undefined && newValue > col.max_value) {
                    newValue = col.max_value;
                }

                if (col.min_value !== undefined && newValue < col.min_value) {
                    newValue = col.min_value;
                }

                const patchData = {
                    version: this.sheet.version,
                    path: ['tables', tableIdx, 'rows', rowIdx, colIdx],
                    value: newValue,
                };

                axios.patch(`/api/sheets/${this.id}`, patchData).then( ( {data} ) => {
                    this.sheet.version = data.version;
                    this.sheet.tables[tableIdx].rows[rowIdx][colIdx] = newValue;
                }).catch ( (error) => {
                    switch (error.response.status) {
                        case 401:
                            flash('danger', 'You are not allowed to edit this sheet!', 3000);
                            break;
                        case 403:
                            flash('danger', 'You need to be signed in to see this sheet.', 3000);
                            break;
                        default:
                            flash('danger', `You cannot edit this sheet: ${error.message}`, 3000);
                            break;
                    }
                });
            }, 500);
        },
        methods: {
            styleForCol(col) {
                if (col.width || false) {
                    return {
                        width: (100 * col.width / 12) + '%'
                    }
                }
            },
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
                            flash('danger', 'You are not allowed to see this sheet!', 3000);
                            break;
                        case 403:
                            flash('danger', 'You need to be signed in to see this sheet.', 3000);
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
