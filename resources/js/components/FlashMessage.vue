<template>
    <div>
        <div :class="classList">
            {{ message }}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message', 'type'],
        computed: {
            classList() {
                return {
                    'notification': true,
                    'is-info': this.type == 'info',
                    'is-warning': this.type == 'warning',
                    'is-danger': this.type == 'danger',
                    'is-success': this.type == 'success',
                };
            },
        },
    }
</script>
