<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <router-link to="/" class="navbar-brand d-flex justify-content-center">
                <img src="../../public/favicon.ico" alt="" class="d-inline-block align-text-top">
                <b class="ms-2">@Ionichi</b>
                <b class="mx-3">|</b>
                <span>Laravu</span>
            </router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" active-class="active" class="nav-link">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/siswa" active-class="active" class="nav-link">Siswa</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/tutor" active-class="active" class="nav-link">Tutor</router-link>
                    </li>
                </ul>
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