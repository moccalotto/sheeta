<template>
    <td>
        <div :class="outerClass">
            <input v-if="cell.is_editable"
            :type="inputType"
            :value="val"
            :min="cell.min_value"
            :max="cell.max_value"
            :pattern="cell.validation"
            :caption="cell.headline"
            @input="changed"
            :class="inputClass">
            <div v-else :class="textClass">
                {{ value }}
            </div>
        </div>
    </td>
</template>
<script>
    export default {
        props: ['cell', 'value'],
        data() {
            return {
                valid: true,
                val: this.value,
            };
        },
        methods: {
            changed($event) {
                this.valid = $event.target.validity.valid;
                this.val = $event.target.value;
                if (this.valid) {
                    this.$emit('changed', $event);
                }
            },
        },
        watch: {
            value() {
                this.val = this.value;
            }
        },
        computed: {
            outerClass() {
                return {
                    'strong': !! this.cell.is_header,
                    'field': !! this.canEdit,
                }
            },
            inputClass() {
                return {
                    'input': true,
                    'is-danger': !this.valid,
                    'is-large': !! this.cell.is_huge,
                    'is-small': ! (this.cell.is_large || this.cell.is_huge),
                };
            },
            textClass() {
                return {
                    'is-large': !! this.cell.is_large,
                };
            },
            inputType() {
                return this.cell.format === 'number' ? 'number' : 'text';
            },
        },
    }
</script>
<style>
.strong {
    font-weight: bold;
}
</style>
