<style scoped>
.custom-center-screen {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
<template>
    <NavbarComponent />
    
    <div class="container">
        <div class="d-flex justify-content-center custom-center-screen">
            <div class="text-center">
                <h1 class="display-1 fw-bold">404</h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> Halaman tidak ditemukan.</p>
                <p class="lead">
                    Halaman yang Anda cari tidak ada.
                  </p>
                <a href="javascript:history.back()" class="text-light btn btn-dark">
                    <i class="bi bi-house-fill"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</template>