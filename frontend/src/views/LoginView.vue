<template>
    <div>
        <NavbarComponent />
        <div class="container mt-5" style="height: 70vh">
            <div class="row justify-content-center">
                <div class="col-lg-4 mb-5">
                    <main class="form-signin border p-4 rounded bg-light">
                        <h1 class="h3 mb-3 fw-bold text-center">Form Login</h1>
                        <form @submit.prevent="sendLogin">
                            <div class="form-floating">
                                <input type="username" v-model="username" class="form-control"
                                    id="username" placeholder="Your username..." required>
                                <label for="username">Username</label>
                            </div> <br>
    
                            <div class="form-floating">
                                <input type="password" v-model="password" class="form-control" id="password"
                                    placeholder="Your password..." required>
                                <label for="password">Password</label>
                            </div>
                            <button class="w-100 btn btn-lg text-light btn-dark mt-3" type="submit">Login</button>
                        </form>
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { useAuthStore } from '@/store';
    import router from '@/router';

    const authStore = useAuthStore();
    export default {
        name: "LoginView",
        data() {
            return {
                username: '',
                password: '',
            }
        },
        methods: {
            async sendLogin() {
                const passLogin = await authStore.login('/login', {
                    username: this.username,
                    password: this.password
                });

                if(passLogin) {
                    router.push('/');
                }
            }
        }
    }
</script>