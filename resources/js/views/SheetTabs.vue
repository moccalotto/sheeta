<template>
    <div class="hero-foot">
        <div class="container">
            <nav class="tabs is-boxed">
                <ul>
                    <router-link tag="li" :to="`/sheets/${id}`">
                        <a>View</a>
                    </router-link>
                    <router-link tag="li" :to="`/sheets/${id}/design`">
                        <a>Design</a>
                    </router-link>
                    <router-link tag="li" :to="`/sheets/${id}/settings`">
                        <a>Settings</a>
                    </router-link>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
    }
</script>
