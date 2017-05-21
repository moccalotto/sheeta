<template>
    <div class="box">
        <div class="title">
            <router-link :to="`/sheets/${sheet.id}/${sluggedHeadline}`">{{ sheet.headline }}</router-link>
        </div>
        <p>
            Created <relative-date :date="sheet.created_at"></relative-date> by
            <router-link :to="'/users/' + sheet.user.username">{{ sheet.user.username }}</router-link>
        </p>
        <p>
            Updated <relative-date :date="sheet.updated_at"></relative-date>.
        </p>
        <p>
            Clone, open, etc.
        </p>
    </div>
</template>

<script>
    export default {
        props: ['sheet'],
        computed: {
            sluggedHeadline() {
                return slug(this.sheet.headline);
            }
        }
    }
</script>
