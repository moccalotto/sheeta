<template>
    <pulse-loader v-if="loading"></pulse-loader>
    <div v-else-if="loggedIn">
        <router-link :to="`/users/${user.username}`">{{ user.username }}</router-link>
        <div class="field is-grouped is-grouped-right">
            <p class="control">
                <button @click.prevent.stop="logout" type="submit" class="button is-small is-info">Logout</button>
            </p>
        </div>
    </div>
    <div v-else>
        <form @submit.prevent.stop="login">
            <div class="field is-grouped">
                <p class="control has-icons-left">
                    <input ref="email" class="input is-small" type="email" placeholder="Email" v-model="form.email">
                    <span class="icon is-small is-left">
                        <i class="fa fa-user"></i>
                    </span>
                </p>

                <p class="control has-icons-left">
                    <input class="input is-small" type="password" placeholder="Password" v-model="form.password">
                    <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                    </span>
                </p>
            </div>

            <div class="field is-grouped is-grouped-right">

                <p class="control">
                    <button type="submit" class="button is-small is-info">Log in</button>
                </p>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: true,
                user: null,
                form:   {},
            };
        },
        computed: {
            loggedIn() {
                return !! this.user;
            },
        },
        methods: {
            login() {
                this.loading = true;
                axios.post('api/users/login', this.form).then( ({data}) => {
                    this.user = data.user;
                    this.loading = false;
                    this.form = {};
                    flash('info', 'Logged in', 3000);
                }).catch( (error) => {
                    this.loading = false;
                    this.form = {};
                    flash('danger', error.response.data.email || 'Cannot login', 3000);
                });
            },
            logout() {
                this.loading = true;
                axios.post('api/users/logout', this.form).then( ({data}) => {
                    this.user = null;
                    this.loading = false;
                    flash('info', 'Logged out', 3000);
                }).catch( (error) => {
                    this.loading = false;
                    flash('danger', error.response.data.email || 'Cannot logout', 3000);
                });
            },
        },
        created() {
            axios.get('api/users/me').then( ({data}) => {
                this.user = data.user;
                this.loading = false;
            }).catch( (error) => {
                flash('danger', error.response.data.email || 'Cannot authenticate!', 3000);
            });
        },
    }
</script>
