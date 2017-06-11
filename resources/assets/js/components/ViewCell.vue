<template>
    <td @click.stop="clicked">
        <div :class="outerClass">
            <input v-if="showInput"
            :type="inputType"
            :value="rawValue"
            :min="column.min_value"
            :max="column.max_value"
            :pattern="column.validation"
            ref="inputEl"
            :caption="column.headline"
            @input="changed"
            :class="inputClass">
            <p v-else :class="textClass" v-text="rawValue">
            </p>
        </div>
    </td>
</template>
<script>
    export default {
        props: ['column', 'value', 'userCanEdit', 'editMode', 'focus'],
        data() {
            return {
                valid: true,
                val: this.value,
            };
        },
        methods: {
            clicked() {
                if (!this.showInput) {
                    this.$emit('text-clicked');
                }
            },
            changed($event) {
                this.valid = $event.target.validity.valid;
                if (this.isAdvanced) {
                    this.val[0] = $event.target.value;
                } else {
                    this.val = $event.target.value;
                }
                if (this.valid) {
                    this.$emit('changed', $event);
                }
            },
        },
        watch: {
            value() {
                this.val = this.value;
            },
        },
        computed: {
            showInput() {
                setTimeout(() => {
                    const el = document.querySelector('.current-focus');
                    if (el) {
                        el.focus();
                    }
                }, 100);
                return this.userCanEdit && (this.editMode || this.column.is_editable);
            },
            rawValue() { // todo find better name for this method.
                return this.isAdvanced ? this.val[0] : this.val;
            },
            isAdvanced() {
                return this.val instanceof Array;
            },
            outerClass() {
                return {
                    'strong': !! this.column.is_header,
                    'field': !! this.column.is_editable,
                }
            },
            inputClass() {
                return {
                    'input': true,
                    'is-danger': !this.valid,
                    'is-large': this.column.size === 'huge',
                    'is-small': [undefined, 'medium'].includes(this.column.size),
                    'current-focus': this.focus,
                };
            },
            textClass() {
                return {
                    'is-large': this.column.size === 'huge',
                    'strong': this.column.size === 'large',
                    'content' : true,
                };
            },
            inputType() {
                return this.column.format === 'number' ? 'number' : 'text';
            },
        },
    }
</script>
<style>
.strong {
    font-weight: bold;
}
</style>
