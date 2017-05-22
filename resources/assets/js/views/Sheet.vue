<template>
    <div>
        <section class="hero">
            {{ $props }}
            <br>
            {{ $data }}
        </section>
    </div>
</template>

<script>
    export default {
        props: ['id', 'slug'],
        data() {
            return {
                sheet: null,
                loading: true,
            };
        },
        created() {
            this.fetch();
        },
        methods: {
            fetch() {
                this.loading = true;
                axios.get('/api/sheets/' + this.id).then( ({data}) => {
                    this.sheet = data;
                    this.loading = false;
                }).catch ( (error) => {
                    console.log(error);
                });
            }
        },
    }
</script>
