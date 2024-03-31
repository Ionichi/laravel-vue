<template>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <router-link to="/" class="navbar-brand d-flex justify-content-center">
                <img src="../../public/favicon.ico" alt="" class="d-inline-block align-text-top">
                <b class="ms-2">@Ionichi</b>
                <b class="mx-3">|</b>
                <span>Laravel + Vue JS | Laravu</span>
            </router-link>
            <ul class="navbar-nav">
                <li v-if="authStore.authenticate == true" class="nav-item">
                    <router-link to="/" class="nav-link">
                        Welcome, {{ authStore.user.fullname }}
                    </router-link>
                </li>
                <li v-else class="nav-item">
                    <router-link to="/login" class="nav-link">Login</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>
<script>
    import router from '@/router';
    import { useAuthStore } from '@/store';

    
    export default {
        name: 'NavbarComponent',
        setup() {
            const authStore = useAuthStore();
            
            return { authStore }
        }
    }
</script>