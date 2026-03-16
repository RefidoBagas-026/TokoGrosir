@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

<style>
    .preloader {
        transition: opacity 0.5s ease !important;
        animation: none !important;
        height: 100vh !important;
    }
    .preloader.animation__fade-out {
        opacity: 0 !important;
        height: 100vh !important;
    }
</style>

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.classList.add('animation__fade-out');
            setTimeout(function () {
                preloader.style.display = 'none';
            }, 500);
        }
    });
</script>
