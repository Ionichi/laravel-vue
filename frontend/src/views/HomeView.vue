<template>
    <main>
        <NavbarComponent />
        <HelloWorld msg="You did it!" />
        <div class="container">
        
        </div>
    </main>
</template>

<script setup>
    import HelloWorld from '@/components/HelloWorld.vue';
    import NavbarComponent from '@/components/NavbarComponent.vue';
    import axios from 'axios';
        axios.get('/siswa').then((res) => {
            console.log(res.data);
        });
</script>