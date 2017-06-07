<template>
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
                @changed="cellChanged($event, rowIdx, colIdx)"
                ></view-cell>
        </tr>
        <template v-if="table.add_rows">
        <tr>
            <td :colspan="table.columns.length">
                <a @click="$emit('row-added', table.columns.length)">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span> Add new row
                </a>
            </td>
        </tr>
    </template>
</tbody>
</table>
</template>
<script>
    import ViewCell from './ViewCell';

    export default {
        props: ['table', 'editable'],
        components: { ViewCell },
        methods: {
            cellChanged($event, rowIdx, colIdx) {
                const value = $event.target.value;
                if ($event.target.validity.valid) {
                    this.$emit('cell-changed', {
                        value,
                        rowIdx,
                        colIdx,
                    });
                }
            },
            styleForCol(col) {
                if (col.width || false) {
                    return {
                        width: (100 * col.width / 12) + '%'
                    }
                }
            },
        }
    }
</script>
