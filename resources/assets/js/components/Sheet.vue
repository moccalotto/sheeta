<template>
    <div>
        <section class="hero">
            {{ sheet.headline }}
        </section>
    </div>
</template>

<script>
    export default {
        props() {
            return ['sheet'];
        }
    }
</script>
