<template>
    <table class="table">
        <caption class="title is-3" v-show="table.visible_headline">
            {{ table.headline }}
        </caption>
        <colgroup>
            <col v-for="col in table.columns" :style="styleForCol(col)">
            <col v-if="editable" style="width: 2em; text-align: right">
        </colgroup>
        <thead v-show="table.visible_headers">
            <th v-for="col in table.columns">
                {{ col.headline }}
            </th>
            <th v-if="editable">
            </th>
        </thead>
        <tbody>
            <tr v-for="row,rowIdx in table.rows">
                <view-cell
                v-for="col,colIdx in row"
                :key="colIdx"
                :value="col"
                :user-can-edit="userCanEdit"
                :column="table.columns[colIdx]"
                :edit-mode="edit.rowIdx === rowIdx"
                :focus="edit.colIdx === colIdx"
                @text-clicked="cellClicked($event, rowIdx, colIdx)"
                @changed="cellChanged($event, rowIdx, colIdx)"
                ></view-cell>
            <td v-if="editable">
                &hellip;
            </td>
        </tr>
        <template v-if="editable">
        <tr>
            <td :colspan="table.columns.length + 1">
                <a @click="$emit('row-added', table.columns.length)" class="is-pulled-right">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    Add new row
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
        props: ['table', 'userCanEdit', 'edit'],
        components: { ViewCell },
        computed: {
            editable() {
                return this.table.is_editable && this.userCanEdit;
            },
        },
        methods: {
            cellClicked($event, rowIdx, colIdx) {
                this.$emit('cell-clicked', {
                    rowIdx,
                    colIdx,
                });
            },
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
