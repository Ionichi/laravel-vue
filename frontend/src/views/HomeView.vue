<template>
    <main>
        <NavbarComponent />
        <section class="container">
            <div class="py-5">
                <span class="h1 pb-2 border-bottom border-3 border-dark">List Menu</span>
            </div>
            <ol>
                <li>
                    <router-link to="/siswa" class="nav-link">Data Siswa</router-link>
                </li>
                <li>Data Tutor</li>
                <li>Data Course</li>
            </ol>
        </section>
    </main>
</template>
<script>
</script>